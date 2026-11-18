<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Project;
use App\Models\AttendanceRecord;
use App\Models\EmployeeAdvance;
use App\Models\PayrollPeriod;
use App\Models\Payroll;
use App\Models\PayrollPayment;
use App\Models\BankAccount;
use App\Models\CompanySetting;
use App\Models\ActivityLog;
use App\Services\PayrollService;
use App\Services\AccountingService;
use App\Services\SequenceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Exception;

class PayrollController extends Controller
{
    // ==========================================
    // ATTENDANCES
    // ==========================================
    public function attendances(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $projectId = $request->input('project_id');

        $query = AttendanceRecord::with(['employee', 'project'])
            ->where('date', $date);

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        $attendances = $query->paginate(20)->withQueryString();
        $projects = Project::whereIn('status', ['preparation', 'in_progress'])->get();
        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        return view('payroll.attendances', compact('attendances', 'projects', 'employees', 'date', 'projectId'));
    }

    public function storeAttendance(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'project_id' => ['required', 'exists:projects,id'],
            'attendances' => ['required', 'array'],
            'attendances.*.employee_id' => ['required', 'exists:employees,id'],
            'attendances.*.status' => ['required', 'in:present,half_day,absent,sick,leave'],
            'attendances.*.overtime_hours' => ['nullable', 'numeric', 'min:0'],
            'attendances.*.notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['attendances'] as $data) {
                $emp = Employee::findOrFail($data['employee_id']);
                $status = $data['status'];
                $ot = (float) ($data['overtime_hours'] ?? 0);

                $dailyWage = in_array($status, ['present', 'half_day']) ? (float) $emp->daily_rate : 0;
                $otWage = $ot > 0 ? (float) ($emp->hourly_rate * 1.5 * $ot) : 0;

                AttendanceRecord::updateOrCreate(
                    [
                        'employee_id' => $emp->id,
                        'date' => $validated['date'],
                    ],
                    [
                        'project_id' => $validated['project_id'],
                        'status' => $status,
                        'overtime_hours' => $ot,
                        'daily_wage' => $dailyWage,
                        'overtime_wage' => $otWage,
                        'notes' => $data['notes'] ?? null,
                    ]
                );
            }
        });

        return back()->with('success', "Absensi lapangan tanggal {$validated['date']} berhasil disimpan.");
    }

    // ==========================================
    // EMPLOYEE ADVANCES (KASBON)
    // ==========================================
    public function advances()
    {
        $advances = EmployeeAdvance::with('employee')->latest()->paginate(15);
        $employees = Employee::where('is_active', true)->get();

        return view('payroll.advances', compact('advances', 'employees'));
    }

    public function storeAdvance(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'advance_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'reason' => ['nullable', 'string'],
        ]);

        $adv = EmployeeAdvance::create([
            'advance_number' => SequenceService::generate('ADV', 'employee_advances', 'advance_number'),
            'employee_id' => $validated['employee_id'],
            'date' => $validated['advance_date'],
            'amount' => $validated['amount'],
            'remaining_amount' => $validated['amount'],
            'status' => 'approved',
            'reason' => $validated['reason'] ?? null,
        ]);

        ActivityLog::record(
            'created',
            'EmployeeAdvance',
            $adv->id,
            "Pemberian kasbon Rp " . number_format($adv->amount, 0, ',', '.') . " kepada karyawan ID {$adv->employee_id}"
        );

        return back()->with('success', "Kasbon {$adv->advance_number} berhasil dicatat.");
    }

    // ==========================================
    // PAYROLL PERIODS & CALCULATION
    // ==========================================
    public function periods()
    {
        $periods = PayrollPeriod::withCount('payrolls')->latest()->paginate(10);
        return view('payroll.periods', compact('periods'));
    }

    public function storePeriod(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $code = SequenceService::generate('PAY', 'payroll_periods', 'period_code');

        $period = PayrollPeriod::create([
            'period_code' => $code,
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'draft',
            'total_gross' => 0,
            'total_deductions' => 0,
            'total_net' => 0,
        ]);

        return redirect()->route('payrolls.show', $period)->with('success', "Periode payroll {$period->period_code} berhasil dibuat.");
    }

    public function calculate(PayrollPeriod $period, PayrollService $payrollService, AccountingService $accountingService)
    {
        $payrollService->calculatePeriod($period);

        // Update totals
        $gross = $period->payrolls()->sum('gross_salary');
        $deductions = $period->payrolls()->sum('advance_deductions');
        $net = $period->payrolls()->sum('net_salary');

        $period->update([
            'total_gross' => $gross,
            'total_deductions' => $deductions,
            'total_net' => $net,
            'status' => 'calculated',
        ]);

        // Post accounting journal for wage accrual
        $accountingService->postPayrollPeriod($period);

        return back()->with('success', "Kalkulasi payroll berhasil & jurnal beban upah telah diposting!");
    }

    public function show(PayrollPeriod $period)
    {
        $period->load(['payrolls.employee', 'payrolls.project']);
        $bankAccounts = BankAccount::where('is_active', true)->get();

        return view('payroll.show', compact('period', 'bankAccounts'));
    }

    public function pay(PayrollPeriod $period, Request $request)
    {
        $validated = $request->validate([
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'payment_date' => ['required', 'date'],
        ]);

        $bankAccount = BankAccount::findOrFail($validated['bank_account_id']);
        $totalNet = (float) $period->total_net;

        if ((float) $bankAccount->current_balance < $totalNet) {
            return back()->with('error', "Saldo rekening {$bankAccount->account_name} tidak mencukupi! Tersedia: Rp " . number_format($bankAccount->current_balance, 0, ',', '.'));
        }

        DB::transaction(function () use ($period, $bankAccount, $validated, $totalNet) {
            // Deduct bank balance
            $bankAccount->current_balance -= $totalNet;
            $bankAccount->save();

            // Record payroll payment
            $paymentNumber = SequenceService::generate('PAY-DISB', 'payroll_payments', 'payment_number');
            PayrollPayment::create([
                'payment_number' => $paymentNumber,
                'payroll_period_id' => $period->id,
                'bank_account_id' => $bankAccount->id,
                'payment_date' => $validated['payment_date'],
                'amount' => $totalNet,
                'notes' => "Pencairan upah periode {$period->title}",
            ]);

            // Update payrolls and period status
            $period->payrolls()->update(['payment_status' => 'paid']);
            $period->update(['status' => 'paid']);

            ActivityLog::record(
                'paid',
                'PayrollPeriod',
                $period->id,
                "Pencairan payroll {$period->period_code} sebesar Rp " . number_format($totalNet, 0, ',', '.') . " melalui {$bankAccount->account_name}"
            );
        });

        return back()->with('success', "Gaji & Upah berhasil dicairkan melalui {$bankAccount->account_name}.");
    }

    public function slipPdf(Payroll $payroll)
    {
        $payroll->load(['employee', 'payrollPeriod', 'project']);
        $company = CompanySetting::instance();

        $pdf = Pdf::loadView('payroll.slip_pdf', compact('payroll', 'company'))
            ->setPaper('a5', 'landscape');

        return $pdf->download("Slip-Gaji-{$payroll->employee?->name}-{$payroll->payrollPeriod?->period_code}.pdf");
    }
}
