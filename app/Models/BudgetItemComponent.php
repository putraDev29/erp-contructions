<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetItemComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_item_id',
        'component_type',
        'material_id',
        'description',
        'coefficient',
        'unit',
        'unit_price',
        'total_cost',
    ];

    protected function casts(): array
    {
        return [
            'coefficient' => 'decimal:4',
            'unit_price' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(BudgetItem::class, 'budget_item_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
