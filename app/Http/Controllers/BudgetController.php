<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\BudgetDivision;
use App\Models\BudgetItem;
use App\Models\Project;
use App\Models\Material;
use App\Models\ActivityLog;
use App\Services\BudgetService;
use App\Services\SequenceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Exception;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $query = Budget::with(['project', 'approvedByUser']);

        if ($projectId = $request->input('project_id')) {
            $query->where('project_id', $projectId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $budgets = $query->latest()->paginate(10)->withQueryString();
        $projects = Project::orderBy('name')->get();

        return view('budgets.index', compact('budgets', 'projects'));
    }

    public function create(Request $request)
    {
        $projects = Project::orderBy('name')->get();
        $selectedProject = $request->has('project_id') ? Project::find($request->project_id) : null;

        return view('budgets.create', compact('projects', 'selectedProject'));
    }

    public function store(Request $request, BudgetService $budgetService)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'overhead_percent' => ['required', 'numeric', 'min:0', 'max:50'],
            'contingency_percent' => ['required', 'numeric', 'min:0', 'max:50'],
            'pricing_method' => ['required', 'in:markup,margin'],
            'target_margin_percent' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'target_markup_percent' => ['nullable', 'numeric', 'min:0'],
        ]);

        $budget = DB::transaction(function () use ($validated) {
            $rabNumber = SequenceService::generate('RAB', 'budgets', 'rab_number');

            $budget = Budget::create([
                'project_id' => $validated['project_id'],
                'rab_number' => $rabNumber,
                'version' => 1,
                'title' => $validated['title'],
                'direct_cost' => 0,
                'overhead_percent' => $validated['overhead_percent'],
                'overhead_cost' => 0,
                'contingency_percent' => $validated['contingency_percent'],
                'contingency_cost' => 0,
                'total_rab_amount' => 0,
                'pricing_method' => $validated['pricing_method'],
                'target_margin_percent' => $validated['target_margin_percent'] ?? 15.00,
                'target_markup_percent' => $validated['target_markup_percent'] ?? 17.65,
                'offered_price' => 0,
                'status' => 'draft',
            ]);

            ActivityLog::record(
                'created',
                'Budget',
                $budget->id,
                "Membuat dokumen RAB baru: {$budget->rab_number} ({$budget->title})"
            );

            return $budget;
        });

        return redirect()->route('budgets.show', $budget)->with('success', "RAB {$budget->rab_number} berhasil dibuat. Silakan tambahkan divisi & item pekerjaan.");
    }

    public function show(Budget $budget)
    {
        $budget->load([
            'project.customer',
            'divisions.items.components',
            'approvedByUser',
        ]);

        $materials = Material::where('is_active', true)->orderBy('name')->get();

        return view('budgets.show', compact('budget', 'materials'));
    }

    public function storeDivision(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'division_code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $maxOrder = (int) $budget->divisions()->max('order_number');

        $budget->divisions()->create([
            'division_code' => $validated['division_code'],
            'name' => $validated['name'],
            'order_number' => $maxOrder + 1,
        ]);

        return back()->with('success', "Divisi {$validated['division_code']} berhasil ditambahkan.");
    }

    public function storeItem(Request $request, BudgetDivision $division)
    {
        $validated = $request->validate([
            'item_code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'spec' => ['nullable', 'string'],
            'volume' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:20'],
            'unit_material_price' => ['nullable', 'numeric', 'min:0'],
            'unit_labor_price' => ['nullable', 'numeric', 'min:0'],
            'unit_equipment_price' => ['nullable', 'numeric', 'min:0'],
            'unit_subcon_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $volume = (float) $validated['volume'];
        $mat = (float) ($validated['unit_material_price'] ?? 0);
        $lab = (float) ($validated['unit_labor_price'] ?? 0);
        $eqp = (float) ($validated['unit_equipment_price'] ?? 0);
        $sub = (float) ($validated['unit_subcon_price'] ?? 0);

        $directUnitCost = $mat + $lab + $eqp + $sub;
        $totalDirectCost = $volume * $directUnitCost;

        $budget = $division->budget;

        // Pricing offered price
        $offeredUnitPrice = $directUnitCost;
        if ($budget->pricing_method === 'margin' && (float) $budget->target_margin_percent < 100) {
            $marginFrac = ((float) $budget->target_margin_percent) / 100;
            $offeredUnitPrice = $directUnitCost / (1.0 - $marginFrac);
        } elseif ($budget->pricing_method === 'markup') {
            $markupFrac = ((float) $budget->target_markup_percent) / 100;
            $offeredUnitPrice = $directUnitCost * (1.0 + $markupFrac);
        }

        $totalOfferedPrice = $volume * $offeredUnitPrice;

        $division->items()->create([
            'item_code' => $validated['item_code'],
            'name' => $validated['name'],
            'spec' => $validated['spec'] ?? null,
            'volume' => $volume,
            'unit' => $validated['unit'],
            'unit_material_price' => $mat,
            'unit_labor_price' => $lab,
            'unit_equipment_price' => $eqp,
            'unit_subcon_price' => $sub,
            'direct_unit_cost' => $directUnitCost,
            'total_direct_cost' => $totalDirectCost,
            'offered_unit_price' => round($offeredUnitPrice, 2),
            'total_offered_price' => round($totalOfferedPrice, 2),
        ]);

        // Recalculate parent budget totals
        $budget->recalculateTotals();

        return back()->with('success', "Item {$validated['item_code']} berhasil ditambahkan ke RAB.");
    }

    public function approve(Budget $budget)
    {
        if ($budget->status === 'approved') {
            return back()->with('error', 'RAB ini sudah disetujui sebelumnya.');
        }

        DB::transaction(function () use ($budget) {
            $budget->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Update project's approved budget amount
            $budget->project->update([
                'approved_budget_amount' => $budget->total_rab_amount,
            ]);

            ActivityLog::record(
                'approved',
                'Budget',
                $budget->id,
                "Menyetujui RAB {$budget->rab_number} versi {$budget->version} dengan total Rp " . number_format($budget->total_rab_amount, 2)
            );
        });

        return back()->with('success', "RAB {$budget->rab_number} telah disetujui & dialokasikan ke Proyek.");
    }

    public function revise(Request $request, Budget $budget, BudgetService $budgetService)
    {
        $validated = $request->validate([
            'revision_notes' => ['required', 'string', 'max:500'],
        ]);

        try {
            $newBudget = $budgetService->createRevision($budget, $validated['revision_notes']);
            return redirect()->route('budgets.show', $newBudget)->with('success', "Revisi RAB versi {$newBudget->version} berhasil dibuat.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function exportPdf(Budget $budget)
    {
        $budget->load([
            'project.customer',
            'divisions.items',
            'approvedByUser',
        ]);

        $company = \App\Models\CompanySetting::instance();

        $pdf = Pdf::loadView('budgets.pdf', compact('budget', 'company'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("RAB-{$budget->rab_number}.pdf");
    }
}
