<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\BudgetDivision;
use App\Models\BudgetItem;
use App\Models\BudgetItemComponent;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Exception;

class BudgetService
{
    /**
     * Calculate offered price based on pricing method.
     */
    public function calculatePricing(float $totalCost, string $method, float $percentage): array
    {
        if ($method === 'margin') {
            if ($percentage >= 100.0) {
                throw new Exception("Target margin harus lebih kecil dari 100%!");
            }
            $marginFraction = $percentage / 100.0;
            $offeredPrice = $marginFraction < 1.0 ? ($totalCost / (1.0 - $marginFraction)) : $totalCost;
            $markupPercent = $totalCost > 0 ? (($offeredPrice - $totalCost) / $totalCost) * 100 : 0;
            return [
                'offered_price' => round($offeredPrice, 2),
                'margin_percent' => $percentage,
                'markup_percent' => round($markupPercent, 2),
            ];
        }

        // Markup method
        $markupFraction = $percentage / 100.0;
        $offeredPrice = $totalCost * (1.0 + $markupFraction);
        $marginPercent = $offeredPrice > 0 ? (($offeredPrice - $totalCost) / $offeredPrice) * 100 : 0;

        return [
            'offered_price' => round($offeredPrice, 2),
            'margin_percent' => round($marginPercent, 2),
            'markup_percent' => $percentage,
        ];
    }

    /**
     * Create a new budget revision version from an approved budget.
     */
    public function createRevision(Budget $existingBudget, string $revisionNotes): Budget
    {
        return DB::transaction(function () use ($existingBudget, $revisionNotes) {
            $nextVersion = $existingBudget->version + 1;
            $newRabNumber = preg_replace('/-V\d+$/', '', $existingBudget->rab_number) . "-V{$nextVersion}";

            $newBudget = Budget::create([
                'project_id' => $existingBudget->project_id,
                'rab_number' => $newRabNumber,
                'version' => $nextVersion,
                'title' => "{$existingBudget->title} (Revisi {$nextVersion})",
                'direct_cost' => $existingBudget->direct_cost,
                'overhead_percent' => $existingBudget->overhead_percent,
                'overhead_cost' => $existingBudget->overhead_cost,
                'contingency_percent' => $existingBudget->contingency_percent,
                'contingency_cost' => $existingBudget->contingency_cost,
                'total_rab_amount' => $existingBudget->total_rab_amount,
                'pricing_method' => $existingBudget->pricing_method,
                'target_margin_percent' => $existingBudget->target_margin_percent,
                'target_markup_percent' => $existingBudget->target_markup_percent,
                'offered_price' => $existingBudget->offered_price,
                'status' => 'draft',
                'revision_notes' => $revisionNotes,
            ]);

            // Clone divisions, items, and components
            foreach ($existingBudget->divisions as $division) {
                $newDivision = BudgetDivision::create([
                    'budget_id' => $newBudget->id,
                    'division_code' => $division->division_code,
                    'name' => $division->name,
                    'order_number' => $division->order_number,
                ]);

                foreach ($division->items as $item) {
                    $newItem = BudgetItem::create([
                        'budget_division_id' => $newDivision->id,
                        'item_code' => $item->item_code,
                        'name' => $item->name,
                        'spec' => $item->spec,
                        'volume' => $item->volume,
                        'unit' => $item->unit,
                        'unit_material_price' => $item->unit_material_price,
                        'unit_labor_price' => $item->unit_labor_price,
                        'unit_equipment_price' => $item->unit_equipment_price,
                        'unit_subcon_price' => $item->unit_subcon_price,
                        'direct_unit_cost' => $item->direct_unit_cost,
                        'total_direct_cost' => $item->total_direct_cost,
                        'offered_unit_price' => $item->offered_unit_price,
                        'total_offered_price' => $item->total_offered_price,
                    ]);

                    foreach ($item->components as $comp) {
                        BudgetItemComponent::create([
                            'budget_item_id' => $newItem->id,
                            'component_type' => $comp->component_type,
                            'material_id' => $comp->material_id,
                            'description' => $comp->description,
                            'coefficient' => $comp->coefficient,
                            'unit' => $comp->unit,
                            'unit_price' => $comp->unit_price,
                            'total_cost' => $comp->total_cost,
                        ]);
                    }
                }
            }

            // Mark old budget as revised
            $existingBudget->update(['status' => 'revised']);

            ActivityLog::record(
                'created',
                'Budget',
                $newBudget->id,
                "Membuat revisi RAB versi {$nextVersion} ({$newBudget->rab_number}). Catatan: {$revisionNotes}"
            );

            return $newBudget;
        });
    }
}
