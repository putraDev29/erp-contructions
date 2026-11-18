<?php

namespace App\Services;

use App\Models\Project;
use App\Models\JournalLine;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SubcontractorContract;
use Illuminate\Support\Facades\DB;

class ProjectCostService
{
    /**
     * Get detailed cost control breakdown for a project.
     */
    public function getProjectCostSummary(Project $project): array
    {
        $activeBudget = (float) $project->active_budget_amount;

        // Actual costs from posted journals
        $actualMaterial = (float) JournalLine::whereHas('journalEntry', function ($q) use ($project) {
            $q->where('project_id', $project->id)->where('status', 'posted');
        })->whereHas('coa', function ($q) {
            $q->where('code', 'like', '5-101%'); // Material
        })->sum('debit');

        $actualLabor = (float) JournalLine::whereHas('journalEntry', function ($q) use ($project) {
            $q->where('project_id', $project->id)->where('status', 'posted');
        })->whereHas('coa', function ($q) {
            $q->where('code', 'like', '5-102%'); // Labor
        })->sum('debit');

        $actualSubcon = (float) JournalLine::whereHas('journalEntry', function ($q) use ($project) {
            $q->where('project_id', $project->id)->where('status', 'posted');
        })->whereHas('coa', function ($q) {
            $q->where('code', 'like', '5-103%'); // Subcontractor
        })->sum('debit');

        $actualEquipment = (float) JournalLine::whereHas('journalEntry', function ($q) use ($project) {
            $q->where('project_id', $project->id)->where('status', 'posted');
        })->whereHas('coa', function ($q) {
            $q->where('code', 'like', '5-104%'); // Equipment
        })->sum('debit');

        $actualOther = (float) JournalLine::whereHas('journalEntry', function ($q) use ($project) {
            $q->where('project_id', $project->id)->where('status', 'posted');
        })->whereHas('coa', function ($q) {
            $q->where('type', 'direct_expense')
              ->where('code', 'not like', '5-101%')
              ->where('code', 'not like', '5-102%')
              ->where('code', 'not like', '5-103%')
              ->where('code', 'not like', '5-104%');
        })->sum('debit');

        $totalActualCost = $actualMaterial + $actualLabor + $actualSubcon + $actualEquipment + $actualOther;

        // PO Commitments (approved POs not yet received/invoiced)
        $committedPO = (float) PurchaseOrderItem::whereHas('purchaseOrder', function ($q) use ($project) {
            $q->where('project_id', $project->id)->whereIn('status', ['approved', 'received_partial']);
        })->select(DB::raw('SUM((qty - received_qty) * unit_price) as unfulfilled_amount'))
          ->value('unfulfilled_amount') ?? 0;

        $totalCommitted = $totalActualCost + (float) $committedPO;
        $remainingBudget = max(0, $activeBudget - $totalActualCost);
        $variance = $activeBudget - $totalActualCost; // Positif = hemat/under budget, Negatif = over budget
        $absorptionPercent = $activeBudget > 0 ? round(($totalActualCost / $activeBudget) * 100, 2) : 0;

        // Revenue recognition and profit
        $recognizedRevenue = (float) JournalLine::whereHas('journalEntry', function ($q) use ($project) {
            $q->where('project_id', $project->id)->where('status', 'posted');
        })->whereHas('coa', function ($q) {
            $q->where('type', 'revenue');
        })->sum('credit');

        $grossProfit = $recognizedRevenue - $totalActualCost;
        $profitMarginPercent = $recognizedRevenue > 0 ? round(($grossProfit / $recognizedRevenue) * 100, 2) : 0;

        return [
            'active_budget' => $activeBudget,
            'actual_material' => $actualMaterial,
            'actual_labor' => $actualLabor,
            'actual_subcon' => $actualSubcon,
            'actual_equipment' => $actualEquipment,
            'actual_other' => $actualOther,
            'total_actual_cost' => $totalActualCost,
            'committed_po' => (float) $committedPO,
            'total_committed' => $totalCommitted,
            'remaining_budget' => $remainingBudget,
            'variance' => $variance,
            'absorption_percent' => $absorptionPercent,
            'recognized_revenue' => $recognizedRevenue,
            'gross_profit' => $grossProfit,
            'profit_margin_percent' => $profitMarginPercent,
            'is_over_budget' => $totalActualCost > $activeBudget && $activeBudget > 0,
        ];
    }
}
