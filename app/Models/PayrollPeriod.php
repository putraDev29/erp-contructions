<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_code',
        'title',
        'start_date',
        'end_date',
        'status',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function getTotalGrossAttribute(): float
    {
        return (float) $this->payrolls()->sum('gross_salary');
    }

    public function getTotalDeductionsAttribute(): float
    {
        return (float) ($this->payrolls()->sum('advance_deductions') + $this->payrolls()->sum('deductions'));
    }

    public function getTotalNetAttribute(): float
    {
        return (float) $this->payrolls()->sum('net_salary');
    }
}
