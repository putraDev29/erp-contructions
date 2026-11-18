<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BudgetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_division_id',
        'item_code',
        'name',
        'spec',
        'volume',
        'unit',
        'unit_material_price',
        'unit_labor_price',
        'unit_equipment_price',
        'unit_subcon_price',
        'direct_unit_cost',
        'total_direct_cost',
        'offered_unit_price',
        'total_offered_price',
    ];

    protected function casts(): array
    {
        return [
            'volume' => 'decimal:4',
            'unit_material_price' => 'decimal:2',
            'unit_labor_price' => 'decimal:2',
            'unit_equipment_price' => 'decimal:2',
            'unit_subcon_price' => 'decimal:2',
            'direct_unit_cost' => 'decimal:2',
            'total_direct_cost' => 'decimal:2',
            'offered_unit_price' => 'decimal:2',
            'total_offered_price' => 'decimal:2',
        ];
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(BudgetDivision::class, 'budget_division_id');
    }

    public function components(): HasMany
    {
        return $this->hasMany(BudgetItemComponent::class);
    }
}
