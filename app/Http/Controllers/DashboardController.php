<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\CustomerInvoice;
use App\Models\SupplierInvoice;
use App\Models\BankAccount;
use App\Models\PurchaseOrder;
use App\Models\Material;
use App\Models\JournalLine;
use App\Models\Budget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedProjectId = $request->input('project_id');
        $selectedYear = $request->input('year', date('Y'));

        // Query scopes for projects
        $projectQuery = Project::query();
        if ($selectedProjectId) {
            $projectQuery->where('id', $selectedProjectId);
        }

        $allProjects = (clone $projectQuery)->get();

        // 1. Core KPIs
        $totalContractValue = (float) $allProjects->sum(fn($p) => $p->active_contract_amount);
        $totalApprovedBudget = (float) $allProjects->sum(fn($p) => $p->active_budget_amount);

        $activeProjectsCount = (clone $projectQuery)->whereIn('status', ['in_progress', 'contract', 'preparation'])->count();
        $completedProjectsCount = (clone $projectQuery)->where('status', 'completed')->count();
        $delayedProjectsCount = (clone $projectQuery)->where('status', 'in_progress')
            ->whereNotNull('deadline_date')
            ->where('deadline_date', '<', now()->toDateString())
            ->count();

        // 2. Financial Metrics from Posted Journals & Invoices
        $journalQuery = JournalLine::whereHas('journalEntry', function ($q) use ($selectedProjectId, $selectedYear) {
            $q->where('status', 'posted');
            if ($selectedProjectId) {
                $q->where('project_id', $selectedProjectId);
            }
            if ($selectedYear) {
                $q->whereYear('entry_date', $selectedYear);
            }
        });

        // Actual Costs by category
        $actualMaterialCost = (float) (clone $journalQuery)->whereHas('coa', function ($q) {
            $q->where('code', 'like', '5-101%');
        })->sum('debit');

        $actualLaborCost = (float) (clone $journalQuery)->whereHas('coa', function ($q) {
            $q->where('code', 'like', '5-102%');
        })->sum('debit');

        $actualSubconCost = (float) (clone $journalQuery)->whereHas('coa', function ($q) {
            $q->where('code', 'like', '5-103%');
        })->sum('debit');

        $actualEquipmentCost = (float) (clone $journalQuery)->whereHas('coa', function ($q) {
            $q->where('code', 'like', '5-104%');
        })->sum('debit');

        $totalActualCost = (float) (clone $journalQuery)->whereHas('coa', function ($q) {
            $q->where('type', 'direct_expense');
        })->sum('debit');

        $recognizedRevenue = (float) (clone $journalQuery)->whereHas('coa', function ($q) {
            $q->where('type', 'revenue');
        })->sum('credit');

        $projectGrossProfit = $recognizedRevenue - $totalActualCost;
        $profitMarginPercent = $recognizedRevenue > 0 ? round(($projectGrossProfit / $recognizedRevenue) * 100, 2) : 0;

        // Cash & Bank balance (all accounts)
        $totalCashBankBalance = (float) BankAccount::where('is_active', true)->sum('current_balance');

        // Customer AR & Supplier AP
        $customerArQuery = CustomerInvoice::whereIn('status', ['sent', 'partially_paid', 'overdue']);
        if ($selectedProjectId) {
            $customerArQuery->where('project_id', $selectedProjectId);
        }
        $totalCustomerReceivables = (float) $customerArQuery->sum('remaining_amount');

        $supplierApQuery = SupplierInvoice::whereIn('status', ['verified', 'partially_paid']);
        if ($selectedProjectId) {
            $supplierApQuery->whereHas('purchaseOrder', fn($q) => $q->where('project_id', $selectedProjectId));
        }
        $totalSupplierPayables = (float) $supplierApQuery->sum('remaining_amount');

        // Total payments received from customers
        $customerPaidQuery = CustomerInvoice::whereNotIn('status', ['draft', 'cancelled']);
        if ($selectedProjectId) {
            $customerPaidQuery->where('project_id', $selectedProjectId);
        }
        $totalCustomerReceipts = (float) $customerPaidQuery->sum('paid_amount');

        // Total disbursements to suppliers
        $supplierPaidQuery = SupplierInvoice::whereNotIn('status', ['draft', 'cancelled']);
        if ($selectedProjectId) {
            $supplierPaidQuery->whereHas('purchaseOrder', fn($q) => $q->where('project_id', $selectedProjectId));
        }
        $totalSupplierDisbursements = (float) $supplierPaidQuery->sum('paid_amount');

        // 3. Alerts & Pending Items
        $dueCustomerInvoices = CustomerInvoice::with(['project', 'customer'])
            ->whereIn('status', ['sent', 'partially_paid', 'overdue'])
            ->where('due_date', '<=', now()->addDays(14)->toDateString())
            ->orderBy('due_date')
            ->take(5)
            ->get();

        $dueSupplierInvoices = SupplierInvoice::with(['supplier', 'purchaseOrder.project'])
            ->whereIn('status', ['verified', 'partially_paid'])
            ->where('due_date', '<=', now()->addDays(14)->toDateString())
            ->orderBy('due_date')
            ->take(5)
            ->get();

        $pendingPOs = PurchaseOrder::with(['supplier', 'project'])
            ->where('status', 'pending_approval')
            ->latest()
            ->take(5)
            ->get();

        $lowStockMaterials = Material::with(['category', 'unit', 'stocks'])
            ->get()
            ->filter(fn($m) => $m->isLowStock())
            ->take(5);

        // 4. Project Rankings
        $rankedProjects = Project::whereIn('status', ['in_progress', 'completed'])->get();
        $topAbsorptionProject = $rankedProjects->sortByDesc(fn($p) => $p->budget_absorption_percent)->first();
        $topMarginProject = $rankedProjects->sortByDesc(fn($p) => $p->profit_margin_percent)->first();
        $lowestMarginProject = $rankedProjects->sortBy(fn($p) => $p->profit_margin_percent)->first();

        // 5. Monthly Chart Data (Jan - Dec)
        $monthlyRevenue = [];
        $monthlyExpense = [];
        $monthlyProfit = [];

        for ($m = 1; $m <= 12; $m++) {
            $rev = (float) JournalLine::whereHas('journalEntry', function ($q) use ($selectedProjectId, $selectedYear, $m) {
                $q->where('status', 'posted')
                  ->whereYear('entry_date', $selectedYear)
                  ->whereMonth('entry_date', $m);
                if ($selectedProjectId) {
                    $q->where('project_id', $selectedProjectId);
                }
            })->whereHas('coa', fn($q) => $q->where('type', 'revenue'))->sum('credit');

            $exp = (float) JournalLine::whereHas('journalEntry', function ($q) use ($selectedProjectId, $selectedYear, $m) {
                $q->where('status', 'posted')
                  ->whereYear('entry_date', $selectedYear)
                  ->whereMonth('entry_date', $m);
                if ($selectedProjectId) {
                    $q->where('project_id', $selectedProjectId);
                }
            })->whereHas('coa', fn($q) => $q->where('type', 'direct_expense'))->sum('debit');

            $monthlyRevenue[] = $rev;
            $monthlyExpense[] = $exp;
            $monthlyProfit[] = $rev - $exp;
        }

        // Project cost composition for chart
        $costComposition = [
            'material' => $actualMaterialCost,
            'labor' => $actualLaborCost,
            'subcon' => $actualSubconCost,
            'equipment' => $actualEquipmentCost,
        ];

        return view('dashboard.index', compact(
            'totalContractValue',
            'totalApprovedBudget',
            'activeProjectsCount',
            'completedProjectsCount',
            'delayedProjectsCount',
            'actualMaterialCost',
            'actualLaborCost',
            'actualSubconCost',
            'actualEquipmentCost',
            'totalActualCost',
            'recognizedRevenue',
            'projectGrossProfit',
            'profitMarginPercent',
            'totalCashBankBalance',
            'totalCustomerReceivables',
            'totalSupplierPayables',
            'totalCustomerReceipts',
            'totalSupplierDisbursements',
            'dueCustomerInvoices',
            'dueSupplierInvoices',
            'pendingPOs',
            'lowStockMaterials',
            'topAbsorptionProject',
            'topMarginProject',
            'lowestMarginProject',
            'monthlyRevenue',
            'monthlyExpense',
            'monthlyProfit',
            'costComposition',
            'allProjects',
            'selectedProjectId',
            'selectedYear'
        ));
    }
}
