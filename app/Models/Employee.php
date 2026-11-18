<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nik',
        'employee_number',
        'name',
        'position',
        'role_title',
        'phone',
        'address',
        'join_date',
        'hire_date',
        'employment_type',
        'daily_rate',
        'hourly_rate',
        'piece_rate',
        'payment_method',
        'bank_name',
        'bank_account_no',
        'bank_account_number',
        'bank_account_name',
        'bank_account_holder',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'join_date' => 'date',
            'hire_date' => 'date',
            'daily_rate' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'piece_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Accessors for transparent fallback between nik/employee_number and position/role_title
    public function getEmployeeNumberAttribute($val)
    {
        return $val ?: $this->attributes['nik'] ?? null;
    }

    public function getRoleTitleAttribute($val)
    {
        return $val ?: $this->attributes['position'] ?? null;
    }

    public function getBankAccountNumberAttribute($val)
    {
        return $val ?: $this->attributes['bank_account_no'] ?? null;
    }

    public function getBankAccountHolderAttribute($val)
    {
        return $val ?: $this->attributes['bank_account_name'] ?? null;
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function employeeAdvances(): HasMany
    {
        return $this->hasMany(EmployeeAdvance::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function getActiveAdvanceBalanceAttribute(): float
    {
        return (float) $this->employeeAdvances()
            ->whereIn('status', ['approved', 'paid'])
            ->sum('remaining_amount');
    }
}
