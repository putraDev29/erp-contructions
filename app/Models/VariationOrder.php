<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VariationOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'vo_number',
        'project_id',
        'vo_date',
        'description',
        'reason',
        'contract_amount_change',
        'budget_amount_change',
        'schedule_days_change',
        'customer_approval_ref',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'vo_date' => 'date',
            'contract_amount_change' => 'decimal:2',
            'budget_amount_change' => 'decimal:2',
            'schedule_days_change' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(VariationOrderItem::class);
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
