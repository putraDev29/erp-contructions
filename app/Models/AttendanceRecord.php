<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'project_id',
        'date',
        'status',
        'check_in',
        'check_out',
        'regular_hours',
        'overtime_hours',
        'daily_wage',
        'overtime_wage',
        'verified_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'regular_hours' => 'decimal:2',
            'overtime_hours' => 'decimal:2',
            'daily_wage' => 'decimal:2',
            'overtime_wage' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function verifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getTotalWageAttribute(): float
    {
        return (float) $this->daily_wage + (float) $this->overtime_wage;
    }
}
