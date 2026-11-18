<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\EmployeeAdvance;
use App\Models\Payroll;
use App\Models\PayrollPeriod;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    /**
     * Calculate and generate payroll entries for all active employees for the given period.
     */
    public function calculatePeriod(PayrollPeriod $period): void
    {
        DB::transaction(function () use ($period) {
            $employees = Employee::where('is_active', true)->get();

            foreach ($employees as $emp) {
                // Fetch attendance records within period
                $attendances = AttendanceRecord::where('employee_id', $emp->id)
                    ->whereBetween('date', [$period->start_date, $period->end_date])
                    ->get();

                $workDays = 0;
                $overtimeHours = 0;
                $regularEarnings = 0;
                $overtimeEarnings = 0;
                $lastProjectId = null;

                foreach ($attendances as $att) {
                    if (in_array($att->status, ['present', 'half_day'])) {
                        $factor = $att->status === 'half_day' ? 0.5 : 1.0;
                        $workDays += $factor;
                        $dailyWage = (float) ($att->daily_wage > 0 ? $att->daily_wage : $emp->daily_rate);
                        $regularEarnings += ($dailyWage * $factor);
                    }

                    $otHours = (float) $att->overtime_hours;
                    if ($otHours > 0) {
                        $overtimeHours += $otHours;
                        $otWage = (float) ($att->overtime_wage > 0 ? $att->overtime_wage : ($emp->hourly_rate * 1.5 * $otHours));
                        $overtimeEarnings += $otWage;
                    }

                    if ($att->project_id) {
                        $lastProjectId = $att->project_id;
                    }
                }

                $grossSalary = $regularEarnings + $overtimeEarnings;

                // Check active advance (kasbon)
                $advances = EmployeeAdvance::where('employee_id', $emp->id)
                    ->where('remaining_amount', '>', 0)
                    ->whereIn('status', ['approved', 'paid'])
                    ->get();

                $advanceDeduction = 0;
                foreach ($advances as $adv) {
                    $available = (float) $adv->remaining_amount;
                    // Deduct up to 50% of gross salary or full advance if small
                    $maxDeductible = max(0, $grossSalary * 0.5);
                    $toDeduct = min($available, $maxDeductible);

                    if ($toDeduct > 0) {
                        $advanceDeduction += $toDeduct;
                        $adv->remaining_amount -= $toDeduct;
                        if ($adv->remaining_amount <= 0) {
                            $adv->status = 'repaid';
                        }
                        $adv->save();
                    }
                }

                $netSalary = max(0, $grossSalary - $advanceDeduction);

                Payroll::updateOrCreate(
                    [
                        'payroll_period_id' => $period->id,
                        'employee_id' => $emp->id,
                    ],
                    [
                        'project_id' => $lastProjectId,
                        'work_days' => $workDays,
                        'overtime_hours' => $overtimeHours,
                        'regular_earnings' => round($regularEarnings, 2),
                        'overtime_earnings' => round($overtimeEarnings, 2),
                        'bonuses' => 0,
                        'gross_salary' => round($grossSalary, 2),
                        'deductions' => 0,
                        'advance_deductions' => round($advanceDeduction, 2),
                        'net_salary' => round($netSalary, 2),
                        'payment_status' => 'pending',
                    ]
                );
            }

            $period->update(['status' => 'calculated']);

            ActivityLog::record(
                'calculated',
                'PayrollPeriod',
                $period->id,
                "Kalkulasi Payroll periode {$period->period_code} ({$period->title})"
            );
        });
    }
}
