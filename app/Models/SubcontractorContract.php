<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubcontractorContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_number',
        'subcontractor_id',
        'project_id',
        'scope_of_work',
        'work_scope',
        'contract_amount',
        'retention_percent',
        'down_payment_amount',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'contract_amount' => 'decimal:2',
            'retention_percent' => 'decimal:2',
            'down_payment_amount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function getWorkScopeAttribute($val)
    {
        return $val ?: $this->attributes['scope_of_work'] ?? null;
    }

    public function subcontractor(): BelongsTo
    {
        return $this->belongsTo(Subcontractor::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function progressRecords(): HasMany
    {
        return $this->hasMany(SubcontractorProgress::class);
    }

    // Alias for progressRecords
    public function progressUpdates(): HasMany
    {
        return $this->hasMany(SubcontractorProgress::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(SubcontractorInvoice::class);
    }
}
