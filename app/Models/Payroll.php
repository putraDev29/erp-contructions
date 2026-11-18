<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_period_id',
        'employee_id',
        'project_id',
        'work_days',
        'overtime_hours',
        'regular_earnings',
        'overtime_earnings',
        'bonuses',
        'gross_salary',
        'deductions',
        'advance_deductions',
        'net_salary',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'work_days' => 'decimal:2',
            'overtime_hours' => 'decimal:2',
            'regular_earnings' => 'decimal:2',
            'overtime_earnings' => 'decimal:2',
            'bonuses' => 'decimal:2',
            'gross_salary' => 'decimal:2',
            'deductions' => 'decimal:2',
            'advance_deductions' => 'decimal:2',
            'net_salary' => 'decimal:2',
        ];
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PayrollPayment::class);
    }
}
