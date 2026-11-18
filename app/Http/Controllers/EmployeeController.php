<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\ActivityLog;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($type = $request->input('employment_type')) {
            $query->where('employment_type', $type);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%")
                  ->orWhere('role_title', 'like', "%{$search}%");
            });
        }

        $employees = $query->latest()->paginate(15)->withQueryString();

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => ['required', 'string', 'max:50', 'unique:employees,employee_number'],
            'name' => ['required', 'string', 'max:150'],
            'role_title' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'hire_date' => ['required', 'date'],
            'employment_type' => ['required', 'in:permanent,contract,daily_worker,borongan'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'bank_name' => ['nullable', 'string', 'max:50'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_account_holder' => ['nullable', 'string', 'max:100'],
        ]);

        $data = array_merge($validated, [
            'nik' => $validated['employee_number'],
            'position' => $validated['role_title'],
            'join_date' => $validated['hire_date'],
            'bank_account_no' => $validated['bank_account_number'] ?? null,
            'bank_account_name' => $validated['bank_account_holder'] ?? null,
            'is_active' => true,
        ]);

        $employee = Employee::create($data);

        ActivityLog::record(
            'created',
            'Employee',
            $employee->id,
            "Mendaftarkan tenaga kerja baru {$employee->name} ({$employee->role_title})"
        );

        return redirect()->route('employees.index')->with('success', "Tenaga kerja {$employee->name} berhasil didaftarkan.");
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_number' => ['required', 'string', 'max:50', 'unique:employees,employee_number,' . $employee->id],
            'name' => ['required', 'string', 'max:150'],
            'role_title' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'hire_date' => ['required', 'date'],
            'employment_type' => ['required', 'in:permanent,contract,daily_worker,borongan'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'bank_name' => ['nullable', 'string', 'max:50'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_account_holder' => ['nullable', 'string', 'max:100'],
            'is_active' => ['required', 'boolean'],
        ]);

        $data = array_merge($validated, [
            'nik' => $validated['employee_number'],
            'position' => $validated['role_title'],
            'join_date' => $validated['hire_date'],
            'bank_account_no' => $validated['bank_account_number'] ?? null,
            'bank_account_name' => $validated['bank_account_holder'] ?? null,
        ]);

        $employee->update($data);

        ActivityLog::record(
            'updated',
            'Employee',
            $employee->id,
            "Memperbarui profil tenaga kerja {$employee->name}"
        );

        return redirect()->route('employees.index')->with('success', "Data tenaga kerja {$employee->name} diperbarui.");
    }
}
