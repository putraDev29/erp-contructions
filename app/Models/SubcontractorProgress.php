<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubcontractorProgress extends Model
{
    use HasFactory;

    protected $table = 'subcontractor_progress';

    protected $fillable = [
        'subcontractor_contract_id',
        'progress_date',
        'inspection_date',
        'claimed_progress_percent',
        'approved_progress_percent',
        'progress_percent',
        'certified_amount',
        'bpa_number',
        'progress_number',
        'status',
        'approved_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'progress_date' => 'date',
            'inspection_date' => 'date',
            'claimed_progress_percent' => 'decimal:2',
            'approved_progress_percent' => 'decimal:2',
            'progress_percent' => 'decimal:2',
            'certified_amount' => 'decimal:2',
        ];
    }

    public function getProgressNumberAttribute($val)
    {
        return $val ?: $this->attributes['bpa_number'] ?? null;
    }

    public function getProgressPercentAttribute($val)
    {
        return $val !== null ? (float) $val : (float) ($this->attributes['approved_progress_percent'] ?? 0);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(SubcontractorContract::class, 'subcontractor_contract_id');
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
