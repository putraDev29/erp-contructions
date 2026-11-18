<?php

namespace App\Services;

use App\Models\AccountMapping;
use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Exception;

class AccountingService
{
    /**
     * Post a balanced journal entry with multiple debit/credit lines.
     * Enforces debit == credit and idempotent posting by checking reference.
     */
    public function createAndPostEntry(
        string $description,
        string $referenceType,
        ?int $referenceId = null,
        ?int $projectId = null,
        array $lines = [],
        ?string $entryDate = null
    ): JournalEntry {
        // Prevent duplicate posting for referenced documents
        if ($referenceId) {
            $existing = JournalEntry::where('reference_type', $referenceType)
                ->where('reference_id', $referenceId)
                ->where('status', 'posted')
                ->first();

            if ($existing) {
                return $existing;
            }
        }

        $date = $entryDate ?: date('Y-m-d');
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($lines as $line) {
            $totalDebit += (float) ($line['debit'] ?? 0);
            $totalCredit += (float) ($line['credit'] ?? 0);
        }

        $diff = abs($totalDebit - $totalCredit);
        if ($diff > 0.01) {
            throw new Exception("Jurnal tidak seimbang! Total Debit: Rp " . number_format($totalDebit, 2) . ", Total Kredit: Rp " . number_format($totalCredit, 2));
        }

        $entryNumber = SequenceService::generate('JE', 'journal_entries', 'entry_number');

        return DB::transaction(function () use ($entryNumber, $date, $referenceType, $referenceId, $projectId, $description, $totalDebit, $totalCredit, $lines) {
            $entry = JournalEntry::create([
                'entry_number' => $entryNumber,
                'entry_date' => $date,
                'posting_date' => $date,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'project_id' => $projectId,
                'description' => $description,
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'status' => 'posted',
                'created_by' => auth()->id(),
            ]);

            foreach ($lines as $line) {
                if (($line['debit'] ?? 0) > 0 || ($line['credit'] ?? 0) > 0) {
                    JournalLine::create([
                        'journal_entry_id' => $entry->id,
                        'chart_of_account_id' => $line['coa_id'],
                        'debit' => $line['debit'] ?? 0,
                        'credit' => $line['credit'] ?? 0,
                        'description' => $line['description'] ?? $description,
                    ]);
                }
            }

            ActivityLog::record(
                'posted',
                'JournalEntry',
                $entry->id,
                "Posting Jurnal Umum: {$entry->entry_number} ({$description})"
            );

            return $entry;
        });
    }

    /**
     * Post journal for Goods Receipt (Purchase on Credit)
     * Debit: Persediaan Material
     * Credit: Utang Supplier
     */
    public function postGoodsReceipt(\App\Models\GoodsReceipt $receipt): ?JournalEntry
    {
        $totalValue = 0;
        foreach ($receipt->items as $item) {
            $unitCost = $item->poItem ? (float) $item->poItem->unit_price : 0;
            $totalValue += ((float) $item->received_qty) * $unitCost;
        }

        if ($totalValue <= 0) {
            return null;
        }

        $inventoryCoaId = AccountMapping::getAccountId('inventory_material');
        $apCoaId = AccountMapping::getAccountId('ap_supplier');

        if (!$inventoryCoaId || !$apCoaId) {
            return null;
        }

        $lines = [
            [
                'coa_id' => $inventoryCoaId,
                'debit' => $totalValue,
                'credit' => 0,
                'description' => "Penerimaan Material {$receipt->receipt_number}",
            ],
            [
                'coa_id' => $apCoaId,
                'debit' => 0,
                'credit' => $totalValue,
                'description' => "Hutang Supplier atas {$receipt->receipt_number}",
            ]
        ];

        return $this->createAndPostEntry(
            "Penerimaan Material dari PO {$receipt->purchaseOrder?->po_number} (Surat Jalan: {$receipt->delivery_note_number})",
            'goods_receipt',
            $receipt->id,
            $receipt->purchaseOrder?->project_id,
            $lines,
            $receipt->receipt_date->format('Y-m-d')
        );
    }

    /**
     * Post journal when material is issued to project
     * Debit: Beban Material Proyek
     * Credit: Persediaan Material
     */
    public function postMaterialIssuanceToProject(\App\Models\InventoryTransaction $trx): ?JournalEntry
    {
        $totalCost = 0;
        foreach ($trx->items as $item) {
            $totalCost += (float) $item->total_cost;
        }

        if ($totalCost <= 0 || !$trx->project_id) {
            return null;
        }

        $cogsMaterialCoaId = AccountMapping::getAccountId('cogs_material');
        $inventoryCoaId = AccountMapping::getAccountId('inventory_material');

        if (!$cogsMaterialCoaId || !$inventoryCoaId) {
            return null;
        }

        $lines = [
            [
                'coa_id' => $cogsMaterialCoaId,
                'debit' => $totalCost,
                'credit' => 0,
                'description' => "Pemakaian material ke Proyek",
            ],
            [
                'coa_id' => $inventoryCoaId,
                'debit' => 0,
                'credit' => $totalCost,
                'description' => "Pengeluaran stok material dari Gudang",
            ]
        ];

        return $this->createAndPostEntry(
            "Pengeluaran Material ke Proyek: {$trx->transaction_number}",
            'inventory_transaction',
            $trx->id,
            $trx->project_id,
            $lines,
            $trx->transaction_date->format('Y-m-d')
        );
    }

    /**
     * Post journal for Customer Invoice (Termin / Milestone / Retensi)
     * Debit: Piutang Usaha Pelanggan
     * Credit: Pendapatan Jasa Konstruksi
     */
    public function postCustomerInvoice(\App\Models\CustomerInvoice $invoice): ?JournalEntry
    {
        $amount = (float) $invoice->net_amount;
        if ($amount <= 0) {
            return null;
        }

        $arCoaId = AccountMapping::getAccountId('ar_customer');
        $revenueCoaId = AccountMapping::getAccountId('revenue_construction');

        if (!$arCoaId || !$revenueCoaId) {
            return null;
        }

        $lines = [
            [
                'coa_id' => $arCoaId,
                'debit' => $amount,
                'credit' => 0,
                'description' => "Tagihan {$invoice->invoice_number} ({$invoice->milestone_description})",
            ],
            [
                'coa_id' => $revenueCoaId,
                'debit' => 0,
                'credit' => $amount,
                'description' => "Pengakuan Pendapatan Konstruksi ({$invoice->project?->name})",
            ]
        ];

        return $this->createAndPostEntry(
            "Penerbitan Invoice {$invoice->invoice_number} ke Pelanggan {$invoice->customer?->name}",
            'customer_invoice',
            $invoice->id,
            $invoice->project_id,
            $lines,
            $invoice->invoice_date->format('Y-m-d')
        );
    }

    /**
     * Post journal for Customer Payment
     * Debit: Kas / Bank (Tergantung rekening penerima)
     * Credit: Piutang Usaha Pelanggan
     */
    public function postCustomerPayment(\App\Models\CustomerPayment $payment): ?JournalEntry
    {
        $amount = (float) $payment->amount;
        if ($amount <= 0) {
            return null;
        }

        $bankCoaId = $payment->bankAccount?->coa_id ?: AccountMapping::getAccountId('bank_default');
        $arCoaId = AccountMapping::getAccountId('ar_customer');

        if (!$bankCoaId || !$arCoaId) {
            return null;
        }

        $lines = [
            [
                'coa_id' => $bankCoaId,
                'debit' => $amount,
                'credit' => 0,
                'description' => "Penerimaan Pembayaran ke {$payment->bankAccount?->account_name}",
            ],
            [
                'coa_id' => $arCoaId,
                'debit' => 0,
                'credit' => $amount,
                'description' => "Pelunasan Piutang Invoice {$payment->invoice?->invoice_number}",
            ]
        ];

        return $this->createAndPostEntry(
            "Penerimaan Pembayaran Pelanggan: {$payment->payment_number}",
            'customer_payment',
            $payment->id,
            $payment->invoice?->project_id,
            $lines,
            $payment->payment_date->format('Y-m-d')
        );
    }

    /**
     * Post journal for Supplier Payment
     * Debit: Utang Usaha Supplier
     * Credit: Kas / Bank
     */
    public function postSupplierPayment(\App\Models\SupplierPayment $payment): ?JournalEntry
    {
        $amount = (float) $payment->amount;
        if ($amount <= 0) {
            return null;
        }

        $apCoaId = AccountMapping::getAccountId('ap_supplier');
        $bankCoaId = $payment->bankAccount?->coa_id ?: AccountMapping::getAccountId('bank_default');

        if (!$apCoaId || !$bankCoaId) {
            return null;
        }

        $lines = [
            [
                'coa_id' => $apCoaId,
                'debit' => $amount,
                'credit' => 0,
                'description' => "Pelunasan Hutang Supplier {$payment->invoice?->supplier?->name}",
            ],
            [
                'coa_id' => $bankCoaId,
                'debit' => 0,
                'credit' => $amount,
                'description' => "Pengeluaran dari {$payment->bankAccount?->account_name}",
            ]
        ];

        return $this->createAndPostEntry(
            "Pembayaran Hutang Supplier: {$payment->payment_number}",
            'supplier_payment',
            $payment->id,
            $payment->invoice?->purchaseOrder?->project_id,
            $lines,
            $payment->payment_date->format('Y-m-d')
        );
    }

    /**
     * Post journal for Payroll Period
     * Debit: Beban Upah Proyek (gross salary)
     * Credit: Uang Muka Karyawan / Kasbon (advance deductions)
     * Credit: Utang Gaji & Upah (net payable)
     */
    public function postPayrollPeriod(\App\Models\PayrollPeriod $period): ?JournalEntry
    {
        $totalGross = (float) $period->total_gross;
        $totalAdvance = (float) $period->payrolls()->sum('advance_deductions');
        $totalNet = (float) $period->total_net;

        if ($totalGross <= 0) {
            return null;
        }

        $cogsLaborCoaId = AccountMapping::getAccountId('cogs_labor');
        $advanceCoaId = AccountMapping::getAccountId('advance_employee');
        $wagePayableCoaId = AccountMapping::getAccountId('wage_payable');

        if (!$cogsLaborCoaId || !$wagePayableCoaId) {
            return null;
        }

        $lines = [
            [
                'coa_id' => $cogsLaborCoaId,
                'debit' => $totalGross,
                'credit' => 0,
                'description' => "Beban Upah & Tenaga Kerja {$period->title}",
            ]
        ];

        if ($totalAdvance > 0 && $advanceCoaId) {
            $lines[] = [
                'coa_id' => $advanceCoaId,
                'debit' => 0,
                'credit' => $totalAdvance,
                'description' => "Pengembalian / Pemotongan Kasbon Pekerja",
            ];
        }

        $lines[] = [
            'coa_id' => $wagePayableCoaId,
            'debit' => 0,
            'credit' => $totalNet,
            'description' => "Kewajiban Utang Upah Bersih Pekerja",
        ];

        return $this->createAndPostEntry(
            "Pengakuan Beban Upah Tenaga Kerja: {$period->title}",
            'payroll_period',
            $period->id,
            null,
            $lines,
            $period->end_date->format('Y-m-d')
        );
    }

    /**
     * Reverse a posted journal entry with strict reversal tracking.
     */
    public function reverseJournalEntry(int $journalEntryId, string $reversalReason): JournalEntry
    {
        $original = JournalEntry::with('lines')->findOrFail($journalEntryId);

        if ($original->status !== 'posted') {
            throw new Exception("Hanya jurnal berstatus POSTED yang dapat dibalik!");
        }

        if ($original->is_reversal) {
            throw new Exception("Jurnal pembalik tidak dapat dibalik kembali!");
        }

        return DB::transaction(function () use ($original, $reversalReason) {
            $entryNumber = SequenceService::generate('REV', 'journal_entries', 'entry_number');

            $reversal = JournalEntry::create([
                'entry_number' => $entryNumber,
                'entry_date' => date('Y-m-d'),
                'posting_date' => date('Y-m-d'),
                'reference_type' => $original->reference_type,
                'reference_id' => $original->reference_id,
                'project_id' => $original->project_id,
                'description' => "Pembalik untuk {$original->entry_number}: {$reversalReason}",
                'total_debit' => $original->total_credit,
                'total_credit' => $original->total_debit,
                'status' => 'posted',
                'is_reversal' => true,
                'reversed_entry_id' => $original->id,
                'created_by' => auth()->id(),
            ]);

            foreach ($original->lines as $line) {
                // Invert debits and credits
                JournalLine::create([
                    'journal_entry_id' => $reversal->id,
                    'chart_of_account_id' => $line->chart_of_account_id,
                    'debit' => $line->credit,
                    'credit' => $line->debit,
                    'description' => "Pembalik: {$line->description}",
                ]);
            }

            $original->update(['status' => 'reversed']);

            ActivityLog::record(
                'reversed',
                'JournalEntry',
                $original->id,
                "Jurnal {$original->entry_number} dibalik dengan nomor {$reversal->entry_number}. Alasan: {$reversalReason}"
            );

            return $reversal;
        });
    }
}
