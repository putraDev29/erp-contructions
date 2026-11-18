<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\EquipmentUsage;
use App\Models\Project;
use App\Models\ActivityLog;
use App\Services\SequenceService;
use Illuminate\Support\Facades\DB;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::with(['project', 'usages']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($ownership = $request->input('ownership_status')) {
            $query->where('ownership_status', $ownership);
        }

        if ($projectId = $request->input('project_id')) {
            $query->where('project_id', $projectId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('asset_number', 'like', "%{$search}%")
                  ->orWhere('model_spec', 'like', "%{$search}%");
            });
        }

        $equipmentList = $query->latest()->paginate(15)->withQueryString();
        $projects = Project::whereIn('status', ['preparation', 'in_progress', 'contract'])->orderBy('name')->get();

        // Statistics
        $totalEquipment = Equipment::count();
        $inUseCount = Equipment::where('status', 'in_use')->count();
        $availableCount = Equipment::where('status', 'available')->count();
        $maintenanceCount = Equipment::whereIn('status', ['maintenance', 'breakdown'])->count();

        // Recent Usages
        $recentUsages = EquipmentUsage::with(['equipment', 'project'])->latest()->take(10)->get();

        return view('equipment.index', compact(
            'equipmentList',
            'projects',
            'totalEquipment',
            'inUseCount',
            'availableCount',
            'maintenanceCount',
            'recentUsages'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'model_spec' => ['nullable', 'string', 'max:150'],
            'asset_number' => ['nullable', 'string', 'max:100'],
            'ownership_status' => ['required', 'in:owned,rented'],
            'status' => ['required', 'in:available,in_use,maintenance,breakdown'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'current_location' => ['nullable', 'string', 'max:150'],
            'operator_name' => ['nullable', 'string', 'max:100'],
            'fuel_type' => ['nullable', 'string', 'max:50'],
            'daily_rental_rate' => ['required', 'numeric', 'min:0'],
            'hourly_rental_rate' => ['required', 'numeric', 'min:0'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'purchase_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $code = SequenceService::generate('EQP', 'equipment', 'code');

        $equipment = Equipment::create(array_merge($validated, ['code' => $code]));

        ActivityLog::record(
            'created',
            'Equipment',
            $equipment->id,
            "Mendaftarkan alat/kendaraan baru {$equipment->code} - {$equipment->name}"
        );

        return back()->with('success', "Alat/kendaraan {$equipment->name} ({$equipment->code}) berhasil ditambahkan.");
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'model_spec' => ['nullable', 'string', 'max:150'],
            'asset_number' => ['nullable', 'string', 'max:100'],
            'ownership_status' => ['required', 'in:owned,rented'],
            'status' => ['required', 'in:available,in_use,maintenance,breakdown'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'current_location' => ['nullable', 'string', 'max:150'],
            'operator_name' => ['nullable', 'string', 'max:100'],
            'fuel_type' => ['nullable', 'string', 'max:50'],
            'daily_rental_rate' => ['required', 'numeric', 'min:0'],
            'hourly_rental_rate' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $equipment->update($validated);

        ActivityLog::record(
            'updated',
            'Equipment',
            $equipment->id,
            "Memperbarui data dan status alat {$equipment->code} - {$equipment->name}"
        );

        return back()->with('success', "Data alat {$equipment->name} berhasil diperbarui.");
    }

    public function storeUsage(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'usage_date' => ['required', 'date'],
            'hours_used' => ['required', 'numeric', 'min:0.5', 'max:24'],
            'fuel_liters' => ['nullable', 'numeric', 'min:0'],
            'fuel_cost' => ['nullable', 'numeric', 'min:0'],
            'operator_cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $fuelCost = (float) ($validated['fuel_cost'] ?? 0);
        $operatorCost = (float) ($validated['operator_cost'] ?? 0);
        $hourlyRateCost = (float) $equipment->hourly_rental_rate * (float) $validated['hours_used'];
        $totalCost = $hourlyRateCost + $fuelCost + $operatorCost;

        DB::transaction(function () use ($equipment, $validated, $fuelCost, $operatorCost, $totalCost) {
            $usage = EquipmentUsage::create([
                'equipment_id' => $equipment->id,
                'project_id' => $validated['project_id'],
                'usage_date' => $validated['usage_date'],
                'hours_used' => $validated['hours_used'],
                'fuel_liters' => $validated['fuel_liters'] ?? 0,
                'fuel_cost' => $fuelCost,
                'operator_cost' => $operatorCost,
                'total_cost' => $totalCost,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update equipment current project and status to in_use
            $equipment->update([
                'project_id' => $validated['project_id'],
                'status' => 'in_use',
            ]);

            ActivityLog::record(
                'created',
                'EquipmentUsage',
                $usage->id,
                "Mencatat pemakaian alat {$equipment->name} di proyek ({$usage->hours_used} jam, total biaya Rp " . number_format($totalCost, 0, ',', '.') . ")"
            );
        });

        return back()->with('success', "Catatan pemakaian operasional alat {$equipment->name} berhasil disimpan.");
    }

    public function destroy(Equipment $equipment)
    {
        $name = $equipment->name;
        $equipment->delete();

        return back()->with('success', "Alat {$name} berhasil dihapus dari sistem.");
    }
}
