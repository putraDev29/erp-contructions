<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\CashTransaction;
use App\Models\BankTransfer;
use App\Models\CustomerInvoice;
use App\Models\CustomerPayment;
use App\Models\SupplierInvoice;
use App\Models\SupplierPayment;
use App\Models\Project;
use App\Models\Customer;
use App\Models\ActivityLog;
use App\Services\SequenceService;
use App\Services\AccountingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Exception;

class FinanceController extends Controller
{
    // ==========================================
    // KAS & REKENING BANK
    // ==========================================
    public function cashBank(Request $request)
    {
        $accounts = BankAccount::where('is_active', true)->with('coa')->get();
        $totalBalance = $accounts->sum('current_balance');

        $query = CashTransaction::with(['bankAccount', 'project']);
        if ($accId = $request->input('bank_account_id')) {
            $query->where('bank_account_id', $accId);
        }
        if ($projId = $request->input('project_id')) {
            $query->where('project_id', $projId);
        }
        $transactions = $query->latest()->paginate(15)->withQueryString();

        $transfers = BankTransfer::with(['fromAccount', 'toAccount'])->latest()->take(5)->get();
        $projects = Project::whereIn('status', ['preparation', 'in_progress'])->get();

        return view('finance.cash_bank', compact('accounts', 'totalBalance', 'transactions', 'transfers', 'projects'));
    }

    public function storeCashTransaction(Request $request)
    {
        $validated = $request->validate([
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'type' => ['required', 'in:in,out'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'transaction_date' => ['required', 'date'],
            'category' => ['required', 'string', 'max:100'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $account = BankAccount::findOrFail($validated['bank_account_id']);
        $amount = (float) $validated['amount'];

        if ($validated['type'] === 'out' && (float) $account->current_balance < $amount) {
            return back()->with('error', "Saldo rekening {$account->account_name} tidak mencukupi untuk pengeluaran ini!");
        }

        DB::transaction(function () use ($validated, $account, $amount) {
            $prefix = $validated['type'] === 'in' ? 'BKM' : 'BKK';
            $trxNumber = SequenceService::generate($prefix, 'cash_transactions', 'transaction_number');

            $trx = CashTransaction::create([
                'transaction_number' => $trxNumber,
                'bank_account_id' => $account->id,
                'project_id' => $validated['project_id'] ?? null,
                'type' => $validated['type'],
                'amount' => $amount,
                'transaction_date' => $validated['transaction_date'],
                'category' => $validated['category'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Adjust bank balance
            if ($validated['type'] === 'in') {
                $account->increment('current_balance', $amount);
            } else {
                $account->decrement('current_balance', $amount);
            }

            ActivityLog::record(
                'created',
                'CashTransaction',
                $trx->id,
                "Mencatat transaksi {$trx->type} {$trx->transaction_number} senilai Rp " . number_format($amount, 0, ',', '.') . " pada {$account->account_name}"
            );
        });

        return back()->with('success', "Transaksi kas/bank berhasil dicatat.");
    }

    public function storeTransfer(Request $request)
    {
        $validated = $request->validate([
            'from_bank_account_id' => ['required', 'exists:bank_accounts,id', 'different:to_bank_account_id'],
            'to_bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'transfer_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $from = BankAccount::findOrFail($validated['from_bank_account_id']);
        $to = BankAccount::findOrFail($validated['to_bank_account_id']);
        $amount = (float) $validated['amount'];
        $fee = (float) ($validated['fee'] ?? 0);
        $totalDeduction = $amount + $fee;

        if ((float) $from->current_balance < $totalDeduction) {
            return back()->with('error', "Saldo rekening asal {$from->account_name} tidak mencukupi!");
        }

        DB::transaction(function () use ($from, $to, $amount, $fee, $validated) {
            $from->decrement('current_balance', $amount + $fee);
            $to->increment('current_balance', $amount);

            $transferNumber = SequenceService::generate('TRF-BNK', 'bank_transfers', 'transfer_number');
            $transfer = BankTransfer::create([
                'transfer_number' => $transferNumber,
                'from_bank_account_id' => $from->id,
                'to_bank_account_id' => $to->id,
                'transfer_date' => $validated['transfer_date'],
                'amount' => $amount,
                'fee' => $fee,
                'notes' => $validated['notes'] ?? null,
            ]);

            ActivityLog::record(
                'created',
                'BankTransfer',
                $transfer->id,
                "Transfer internal Rp " . number_format($amount, 0, ',', '.') . " dari {$from->account_name} ke {$to->account_name}"
            );
        });

        return back()->with('success', "Transfer internal antar rekening berhasil.");
    }

    // ==========================================
    // INVOICE PELANGGAN & PIUTANG (AR)
    // ==========================================
    public function customerInvoices(Request $request)
    {
        $query = CustomerInvoice::with(['customer', 'project']);

        if ($projectId = $request->input('project_id')) {
            $query->where('project_id', $projectId);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $invoices = $query->latest()->paginate(15)->withQueryString();
        $projects = Project::whereIn('status', ['contract', 'preparation', 'in_progress', 'completed'])->get();

        return view('finance.customer_invoices', compact('invoices', 'projects'));
    }

    public function createCustomerInvoice()
    {
        $projects = Project::with('customer')->whereIn('status', ['contract', 'preparation', 'in_progress'])->get();
        return view('finance.customer_invoice_create', compact('projects'));
    }

    public function storeCustomerInvoice(Request $request, AccountingService $accountingService)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:invoice_date'],
            'milestone_description' => ['required', 'string', 'max:200'],
            'progress_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'gross_amount' => ['required', 'numeric', 'min:1000'],
            'retention_amount' => ['nullable', 'numeric', 'min:0'],
            'tax_percent' => ['nullable', 'numeric', 'min:0', 'max:50'],
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $gross = (float) $validated['gross_amount'];
        $retention = (float) ($validated['retention_amount'] ?? 0);
        $taxPercent = (float) ($validated['tax_percent'] ?? 11);
        $taxAmount = round(($gross - $retention) * ($taxPercent / 100), 2);
        $netAmount = ($gross - $retention) + $taxAmount;

        $invoice = DB::transaction(function () use ($validated, $project, $gross, $retention, $taxPercent, $taxAmount, $netAmount, $accountingService) {
            $invNumber = SequenceService::generate('INV-CLI', 'customer_invoices', 'invoice_number');

            $invoice = CustomerInvoice::create([
                'invoice_number' => $invNumber,
                'project_id' => $project->id,
                'customer_id' => $project->customer_id,
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'invoice_type' => 'milestone_progress',
                'milestone_description' => $validated['milestone_description'],
                'progress_percent' => $validated['progress_percent'] ?? 0,
                'invoice_amount' => $gross,
                'retention_deducted' => $retention,
                'tax_percent' => $taxPercent,
                'tax_amount' => $taxAmount,
                'net_amount' => $netAmount,
                'paid_amount' => 0,
                'remaining_amount' => $netAmount,
                'status' => 'sent',
            ]);

            // Post double-entry accounting journal (Debit: Piutang Pelanggan / Credit: Pendapatan Konstruksi)
            $accountingService->postCustomerInvoice($invoice);

            ActivityLog::record(
                'created',
                'CustomerInvoice',
                $invoice->id,
                "Menerbitkan invoice piutang {$invoice->invoice_number} senilai Rp " . number_format($netAmount, 0, ',', '.') . " ke {$project->customer?->name}"
            );

            return $invoice;
        });

        return redirect()->route('customer-invoices.show', $invoice)->with('success', "Invoice tagihan {$invoice->invoice_number} berhasil diterbitkan.");
    }

    public function showCustomerInvoice(CustomerInvoice $customerInvoice)
    {
        $customerInvoice->load(['project', 'customer', 'payments.bankAccount']);
        $bankAccounts = BankAccount::where('is_active', true)->get();

        return view('finance.customer_invoice_show', compact('customerInvoice', 'bankAccounts'));
    }

    public function storeCustomerPayment(CustomerInvoice $customerInvoice, Request $request, AccountingService $accountingService)
    {
        $validated = $request->validate([
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:1000', 'max:' . $customerInvoice->remaining_amount],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $bankAccount = BankAccount::findOrFail($validated['bank_account_id']);
        $amount = (float) $validated['amount'];

        DB::transaction(function () use ($customerInvoice, $bankAccount, $amount, $validated, $accountingService) {
            $paymentNumber = SequenceService::generate('KWT-CLI', 'customer_payments', 'payment_number');

            $payment = CustomerPayment::create([
                'payment_number' => $paymentNumber,
                'customer_invoice_id' => $customerInvoice->id,
                'bank_account_id' => $bankAccount->id,
                'payment_date' => $validated['payment_date'],
                'amount' => $amount,
                'payment_method' => 'transfer',
                'reference_number' => $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id() ?? 1,
            ]);

            // Increase bank account balance
            $bankAccount->increment('current_balance', $amount);

            // Update invoice balances
            $newPaid = (float) $customerInvoice->paid_amount + $amount;
            $newRemaining = max(0, (float) $customerInvoice->net_amount - $newPaid);
            $newStatus = $newRemaining <= 0 ? 'paid' : 'partially_paid';

            $customerInvoice->update([
                'paid_amount' => $newPaid,
                'remaining_amount' => $newRemaining,
                'status' => $newStatus,
            ]);

            // Post double-entry accounting journal (Debit: Kas/Bank / Credit: Piutang Pelanggan)
            $accountingService->postCustomerPayment($payment);

            ActivityLog::record(
                'paid',
                'CustomerPayment',
                $payment->id,
                "Menerima pelunasan piutang {$payment->payment_number} Rp " . number_format($amount, 0, ',', '.') . " untuk invoice {$customerInvoice->invoice_number}"
            );
        });

        return back()->with('success', "Pembayaran dari pelanggan berhasil diterima dan saldo kas/bank bertambah.");
    }

    // ==========================================
    // PEMBAYARAN HUTANG SUPPLIER (AP)
    // ==========================================
    public function storeSupplierPayment(SupplierInvoice $supplierInvoice, Request $request, AccountingService $accountingService)
    {
        $validated = $request->validate([
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:1000', 'max:' . $supplierInvoice->remaining_amount],
            'payment_method' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $bankAccount = BankAccount::findOrFail($validated['bank_account_id']);
        $amount = (float) $validated['amount'];

        if ((float) $bankAccount->current_balance < $amount) {
            return back()->with('error', "Saldo rekening {$bankAccount->account_name} tidak mencukupi untuk pembayaran ini!");
        }

        DB::transaction(function () use ($supplierInvoice, $bankAccount, $amount, $validated, $accountingService) {
            $paymentNumber = SequenceService::generate('KWT-SUP', 'supplier_payments', 'payment_number');

            $payment = SupplierPayment::create([
                'payment_number' => $paymentNumber,
                'supplier_invoice_id' => $supplierInvoice->id,
                'bank_account_id' => $bankAccount->id,
                'payment_date' => $validated['payment_date'],
                'amount' => $amount,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id() ?? 1,
            ]);

            // Deduct bank account balance
            $bankAccount->decrement('current_balance', $amount);

            // Update supplier invoice balances
            $newPaid = (float) $supplierInvoice->paid_amount + $amount;
            $newRemaining = max(0, (float) $supplierInvoice->total_amount - $newPaid);
            $newStatus = $newRemaining <= 0 ? 'paid' : 'partially_paid';

            $supplierInvoice->update([
                'paid_amount' => $newPaid,
                'remaining_amount' => $newRemaining,
                'status' => $newStatus,
            ]);

            // Post double-entry accounting journal (Debit: Hutang Supplier / Credit: Kas/Bank)
            $accountingService->postSupplierPayment($payment);

            ActivityLog::record(
                'paid',
                'SupplierPayment',
                $payment->id,
                "Membayar hutang supplier {$payment->payment_number} Rp " . number_format($amount, 0, ',', '.') . " untuk faktur {$supplierInvoice->invoice_number}"
            );
        });

        return back()->with('success', "Pembayaran hutang supplier berhasil diproses dan jurnal akuntansi telah diposting.");
    }
}
