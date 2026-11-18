<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VariationOrder;
use App\Models\Project;
use App\Models\ActivityLog;
use App\Services\SequenceService;
use Illuminate\Support\Facades\DB;

class VariationOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = VariationOrder::with(['project', 'approvedByUser']);

        if ($projectId = $request->input('project_id')) {
            $query->where('project_id', $projectId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $variationOrders = $query->latest()->paginate(10)->withQueryString();
        $projects = Project::whereIn('status', ['contract', 'preparation', 'in_progress'])->orderBy('name')->get();

        return view('variation_orders.index', compact('variationOrders', 'projects'));
    }

    public function create(Request $request)
    {
        $projects = Project::whereIn('status', ['contract', 'preparation', 'in_progress'])->orderBy('name')->get();
        $selectedProject = $request->has('project_id') ? Project::find($request->project_id) : null;

        return view('variation_orders.create', compact('projects', 'selectedProject'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'vo_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'reason' => ['nullable', 'string'],
            'contract_amount_change' => ['required', 'numeric'],
            'budget_amount_change' => ['required', 'numeric'],
            'schedule_days_change' => ['nullable', 'integer'],
            'customer_approval_ref' => ['nullable', 'string', 'max:100'],
        ]);

        $voNumber = SequenceService::generate('VO', 'variation_orders', 'vo_number');

        $vo = VariationOrder::create(array_merge($validated, [
            'vo_number' => $voNumber,
            'status' => 'draft',
        ]));

        ActivityLog::record(
            'created',
            'VariationOrder',
            $vo->id,
            "Membuat pengajuan Variation Order {$vo->vo_number} pada proyek ID {$vo->project_id}"
        );

        return redirect()->route('variation-orders.index', ['project_id' => $vo->project_id])
            ->with('success', "Variation Order {$vo->vo_number} berhasil diajukan sebagai DRAFT.");
    }

    public function approve(VariationOrder $variationOrder)
    {
        if ($variationOrder->status === 'approved') {
            return back()->with('error', 'Variation Order ini sudah disetujui sebelumnya.');
        }

        DB::transaction(function () use ($variationOrder) {
            $variationOrder->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Adjust project deadline if days change
            if ($variationOrder->schedule_days_change != 0 && $variationOrder->project?->deadline_date) {
                $newDeadline = $variationOrder->project->deadline_date->addDays($variationOrder->schedule_days_change);
                $variationOrder->project->update(['deadline_date' => $newDeadline]);
            }

            ActivityLog::record(
                'approved',
                'VariationOrder',
                $variationOrder->id,
                "Menyetujui Variation Order {$variationOrder->vo_number}. Nilai Kontrak berubah: Rp " . number_format($variationOrder->contract_amount_change, 2)
            );
        });

        return back()->with('success', "Variation Order {$variationOrder->vo_number} telah DISETUJUI.");
    }

    public function reject(Request $request, VariationOrder $variationOrder)
    {
        $variationOrder->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        ActivityLog::record(
            'rejected',
            'VariationOrder',
            $variationOrder->id,
            "Menolak Variation Order {$variationOrder->vo_number}"
        );

        return back()->with('success', "Variation Order {$variationOrder->vo_number} telah DITOLAK.");
    }
}
