<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BudgetDivision extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'division_code',
        'name',
        'order_number',
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function getTotalDivisionCostAttribute(): float
    {
        return (float) $this->items()->sum('total_direct_cost');
    }
}
