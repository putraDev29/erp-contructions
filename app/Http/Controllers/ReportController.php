<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\CustomerInvoice;
use App\Models\SupplierInvoice;
use App\Models\BankAccount;
use App\Models\CashTransaction;
use App\Models\ChartOfAccount;
use App\Models\JournalLine;
use App\Models\CompanySetting;
use App\Services\ProjectCostService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // ==========================================
    // COST CONTROL (ANGGARAN VS REALISASI)
    // ==========================================
    public function costControl(Request $request, ProjectCostService $costService)
    {
        $projects = Project::whereIn('status', ['preparation', 'in_progress', 'completed'])->get();
        $selectedProjectId = $request->input('project_id', $projects->first()?->id);

        $selectedProject = Project::find($selectedProjectId);
        $costData = null;

        if ($selectedProject) {
            $costData = $costService->getProjectCostSummary($selectedProject);
        }

        return view('reports.cost_control', compact('projects', 'selectedProject', 'costData', 'selectedProjectId'));
    }

    // ==========================================
    // LABA RUGI PROYEK (PROJECT P&L)
    // ==========================================
    public function projectProfitLoss(Request $request, ProjectCostService $costService)
    {
        $projects = Project::whereIn('status', ['preparation', 'in_progress', 'completed'])->get();

        $projectReports = [];
        foreach ($projects as $p) {
            $summary = $costService->getProjectCostSummary($p);
            $projectReports[] = [
                'project' => $p,
                'summary' => $summary,
            ];
        }

        return view('reports.project_pl', compact('projectReports'));
    }

    // ==========================================
    // LAPORAN KEUANGAN PERUSAHAAN (P&L & NERACA)
    // ==========================================
    public function financial(Request $request)
    {
        // Income Statement: Revenue vs Direct Costs vs Overhead Expenses
        $revenues = ChartOfAccount::where('type', 'revenue')
            ->with(['journalLines' => function ($q) {
                $q->whereHas('journalEntry', fn($e) => $e->where('status', 'posted'));
            }])
            ->get();

        $directCosts = ChartOfAccount::whereIn('type', ['cogs', 'direct_expense'])
            ->with(['journalLines' => function ($q) {
                $q->whereHas('journalEntry', fn($e) => $e->where('status', 'posted'));
            }])
            ->get();

        $overheadExpenses = ChartOfAccount::whereIn('type', ['expense', 'indirect_expense'])
            ->with(['journalLines' => function ($q) {
                $q->whereHas('journalEntry', fn($e) => $e->where('status', 'posted'));
            }])
            ->get();

        $totalRevenue = 0;
        foreach ($revenues as $r) {
            $totalRevenue += ($r->journalLines->sum('credit') - $r->journalLines->sum('debit'));
        }

        $totalDirectCost = 0;
        foreach ($directCosts as $c) {
            $totalDirectCost += ($c->journalLines->sum('debit') - $c->journalLines->sum('credit'));
        }

        $grossProfit = $totalRevenue - $totalDirectCost;

        $totalOverhead = 0;
        foreach ($overheadExpenses as $o) {
            $totalOverhead += ($o->journalLines->sum('debit') - $o->journalLines->sum('credit'));
        }

        $netOperatingProfit = $grossProfit - $totalOverhead;

        return view('reports.financial', compact(
            'revenues',
            'directCosts',
            'overheadExpenses',
            'totalRevenue',
            'totalDirectCost',
            'grossProfit',
            'totalOverhead',
            'netOperatingProfit'
        ));
    }

    // ==========================================
    // ARUS KAS (CASH FLOW STATEMENT)
    // ==========================================
    public function cashFlow(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-01-01'));
        $endDate = $request->input('end_date', date('Y-12-31'));

        // Inflows: Customer payments & Cash in
        $clientReceipts = (float) DB::table('customer_payments')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        $otherInflows = (float) DB::table('cash_transactions')
            ->where('type', 'in')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $totalInflows = $clientReceipts + $otherInflows;

        // Outflows: Supplier payments, Payroll disbursements, Cash out
        $supplierDisbursements = (float) DB::table('supplier_payments')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        $payrollDisbursements = (float) DB::table('payroll_payments')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        $otherOutflows = (float) DB::table('cash_transactions')
            ->where('type', 'out')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $totalOutflows = $supplierDisbursements + $payrollDisbursements + $otherOutflows;
        $netCashFlow = $totalInflows - $totalOutflows;

        $currentTotalCash = BankAccount::where('is_active', true)->sum('current_balance');

        return view('reports.cash_flow', compact(
            'startDate',
            'endDate',
            'clientReceipts',
            'otherInflows',
            'totalInflows',
            'supplierDisbursements',
            'payrollDisbursements',
            'otherOutflows',
            'totalOutflows',
            'netCashFlow',
            'currentTotalCash'
        ));
    }

    // ==========================================
    // AGING UTANG (AP) & PIUTANG (AR)
    // ==========================================
    public function aging(Request $request)
    {
        $today = time();

        // Aging Piutang (AR)
        $unpaidInvoices = CustomerInvoice::with(['customer', 'project'])
            ->where('remaining_amount', '>', 0)
            ->get();

        $arAging = [
            'current' => 0,
            '1_30' => 0,
            '31_60' => 0,
            '61_90' => 0,
            'over_90' => 0,
            'total' => 0,
            'items' => [],
        ];

        foreach ($unpaidInvoices as $inv) {
            $due = strtotime($inv->due_date);
            $diffDays = (int) floor(($today - $due) / (60 * 60 * 24));
            $rem = (float) $inv->remaining_amount;

            $bucket = 'current';
            if ($diffDays <= 0) {
                $arAging['current'] += $rem;
                $bucket = 'Belum Jatuh Tempo';
            } elseif ($diffDays <= 30) {
                $arAging['1_30'] += $rem;
                $bucket = '1 - 30 Hari';
            } elseif ($diffDays <= 60) {
                $arAging['31_60'] += $rem;
                $bucket = '31 - 60 Hari';
            } elseif ($diffDays <= 90) {
                $arAging['61_90'] += $rem;
                $bucket = '61 - 90 Hari';
            } else {
                $arAging['over_90'] += $rem;
                $bucket = '> 90 Hari';
            }
            $arAging['total'] += $rem;

            $arAging['items'][] = [
                'type' => 'AR',
                'ref' => $inv->invoice_number,
                'party' => $inv->customer?->name,
                'project' => $inv->project?->name,
                'due_date' => $inv->due_date,
                'days' => max(0, $diffDays),
                'bucket' => $bucket,
                'amount' => $rem,
            ];
        }

        // Aging Hutang Supplier (AP)
        $unpaidSupplierInvoices = SupplierInvoice::with(['supplier', 'purchaseOrder.project'])
            ->where('remaining_amount', '>', 0)
            ->get();

        $apAging = [
            'current' => 0,
            '1_30' => 0,
            '31_60' => 0,
            '61_90' => 0,
            'over_90' => 0,
            'total' => 0,
            'items' => [],
        ];

        foreach ($unpaidSupplierInvoices as $inv) {
            $due = strtotime($inv->due_date);
            $diffDays = (int) floor(($today - $due) / (60 * 60 * 24));
            $rem = (float) $inv->remaining_amount;

            $bucket = 'current';
            if ($diffDays <= 0) {
                $apAging['current'] += $rem;
                $bucket = 'Belum Jatuh Tempo';
            } elseif ($diffDays <= 30) {
                $apAging['1_30'] += $rem;
                $bucket = '1 - 30 Hari';
            } elseif ($diffDays <= 60) {
                $apAging['31_60'] += $rem;
                $bucket = '31 - 60 Hari';
            } elseif ($diffDays <= 90) {
                $apAging['61_90'] += $rem;
                $bucket = '61 - 90 Hari';
            } else {
                $apAging['over_90'] += $rem;
                $bucket = '> 90 Hari';
            }
            $apAging['total'] += $rem;

            $apAging['items'][] = [
                'type' => 'AP',
                'ref' => $inv->invoice_number,
                'party' => $inv->supplier?->name,
                'project' => $inv->purchaseOrder?->project?->name ?? 'Gudang Utama',
                'due_date' => $inv->due_date,
                'days' => max(0, $diffDays),
                'bucket' => $bucket,
                'amount' => $rem,
            ];
        }

        return view('reports.aging', compact('arAging', 'apAging'));
    }
}
