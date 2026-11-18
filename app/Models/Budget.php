<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'rab_number',
        'version',
        'title',
        'direct_cost',
        'overhead_percent',
        'overhead_cost',
        'contingency_percent',
        'contingency_cost',
        'total_rab_amount',
        'pricing_method',
        'target_margin_percent',
        'target_markup_percent',
        'offered_price',
        'status',
        'approved_by',
        'approved_at',
        'revision_notes',
    ];

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'direct_cost' => 'decimal:2',
            'overhead_percent' => 'decimal:2',
            'overhead_cost' => 'decimal:2',
            'contingency_percent' => 'decimal:2',
            'contingency_cost' => 'decimal:2',
            'total_rab_amount' => 'decimal:2',
            'target_margin_percent' => 'decimal:2',
            'target_markup_percent' => 'decimal:2',
            'offered_price' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function divisions(): HasMany
    {
        return $this->hasMany(BudgetDivision::class)->orderBy('order_number');
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function recalculateTotals(): void
    {
        $directCost = 0;
        foreach ($this->divisions as $division) {
            foreach ($division->items as $item) {
                $directCost += (float) $item->total_direct_cost;
            }
        }

        $this->direct_cost = $directCost;
        $this->overhead_cost = round(($directCost * ((float) $this->overhead_percent / 100)), 2);
        $this->contingency_cost = round(($directCost * ((float) $this->contingency_percent / 100)), 2);
        $this->total_rab_amount = $this->direct_cost + $this->overhead_cost + $this->contingency_cost;

        if ($this->pricing_method === 'margin') {
            $marginFraction = ((float) $this->target_margin_percent) / 100;
            if ($marginFraction < 1.0) {
                $this->offered_price = round($this->total_rab_amount / (1.0 - $marginFraction), 2);
            } else {
                $this->offered_price = $this->total_rab_amount;
            }
        } else {
            // markup
            $markupFraction = ((float) $this->target_markup_percent) / 100;
            $this->offered_price = round($this->total_rab_amount * (1.0 + $markupFraction), 2);
        }

        $this->save();
    }
}
