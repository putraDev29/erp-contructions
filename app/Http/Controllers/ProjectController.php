<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Customer;
use App\Models\User;
use App\Models\ActivityLog;
use App\Services\SequenceService;
use App\Services\ProjectCostService;
use Illuminate\Support\Facades\DB;
use Exception;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['customer', 'projectManager']);

        // Project scoping for Site Managers
        $user = auth()->user();
        if ($user && $user->hasRole('site_manager')) {
            $query->where(function ($q) use ($user) {
                $q->where('site_manager_id', $user->id)
                  ->orWhereHas('assignedUsers', fn($sq) => $sq->where('users.id', $user->id));
            });
        }

        // Filters
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('project_code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $projects = $query->latest()->paginate(10)->withQueryString();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $projectManagers = User::whereHas('role', fn($q) => $q->whereIn('name', ['project_manager', 'super_admin', 'director']))->get();
        $siteManagers = User::whereHas('role', fn($q) => $q->whereIn('name', ['site_manager', 'mandor']))->get();

        return view('projects.create', compact('customers', 'projectManagers', 'siteManagers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:residential,commercial,steel_construction,renovation,civil_works,others'],
            'customer_id' => ['required', 'exists:customers,id'],
            'location' => ['nullable', 'string'],
            'coordinates' => ['nullable', 'string'],
            'project_manager_id' => ['nullable', 'exists:users,id'],
            'site_manager_id' => ['nullable', 'exists:users,id'],
            'tender_date' => ['nullable', 'date'],
            'start_date' => ['nullable', 'date'],
            'deadline_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'contract_amount' => ['required', 'numeric', 'min:0'],
            'approved_budget_amount' => ['nullable', 'numeric', 'min:0'],
            'target_margin_percent' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'contract_method' => ['required', 'in:lump_sum,unit_price,cost_plus'],
            'payment_system' => ['nullable', 'string'],
            'retention_percent' => ['nullable', 'numeric', 'min:0', 'max:50'],
            'down_payment_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $project = DB::transaction(function () use ($validated) {
            $projectCode = SequenceService::generateProjectCode();

            $contractAmount = (float) $validated['contract_amount'];
            $downPaymentPercent = (float) ($validated['down_payment_percent'] ?? 0);
            $retentionPercent = (float) ($validated['retention_percent'] ?? 0);

            $downPaymentAmount = round(($contractAmount * ($downPaymentPercent / 100)), 2);
            $retentionAmount = round(($contractAmount * ($retentionPercent / 100)), 2);

            $project = Project::create(array_merge($validated, [
                'project_code' => $projectCode,
                'down_payment_amount' => $downPaymentAmount,
                'retention_amount' => $retentionAmount,
                'status' => 'draft',
                'physical_progress_percent' => 0.00,
            ]));

            // Assign PM and Site Manager to project
            if ($project->project_manager_id) {
                $project->assignedUsers()->syncWithoutDetaching([$project->project_manager_id => ['role_in_project' => 'project_manager']]);
            }
            if ($project->site_manager_id) {
                $project->assignedUsers()->syncWithoutDetaching([$project->site_manager_id => ['role_in_project' => 'site_manager']]);
            }

            ActivityLog::record(
                'created',
                'Project',
                $project->id,
                "Membuat proyek baru {$project->project_code} - {$project->name}"
            );

            return $project;
        });

        return redirect()->route('projects.show', $project)->with('success', "Proyek {$project->project_code} berhasil dibuat.");
    }

    public function show(Project $project, ProjectCostService $costService)
    {
        $project->load([
            'customer',
            'projectManager',
            'siteManager',
            'assignedUsers',
            'tasks.children',
            'contracts',
            'variationOrders',
            'budgets',
            'customerInvoices',
            'purchaseOrders.supplier',
            'subcontractorContracts.subcontractor',
        ]);

        $costSummary = $costService->getProjectCostSummary($project);

        return view('projects.show', compact('project', 'costSummary'));
    }

    public function edit(Project $project)
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $projectManagers = User::whereHas('role', fn($q) => $q->whereIn('name', ['project_manager', 'super_admin', 'director']))->get();
        $siteManagers = User::whereHas('role', fn($q) => $q->whereIn('name', ['site_manager', 'mandor']))->get();

        return view('projects.edit', compact('project', 'customers', 'projectManagers', 'siteManagers'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:residential,commercial,steel_construction,renovation,civil_works,others'],
            'customer_id' => ['required', 'exists:customers,id'],
            'location' => ['nullable', 'string'],
            'project_manager_id' => ['nullable', 'exists:users,id'],
            'site_manager_id' => ['nullable', 'exists:users,id'],
            'start_date' => ['nullable', 'date'],
            'deadline_date' => ['nullable', 'date'],
            'actual_end_date' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,estimation,tender,pending_approval,contract,preparation,in_progress,on_hold,completed,cancelled'],
            'contract_amount' => ['required', 'numeric', 'min:0'],
            'approved_budget_amount' => ['nullable', 'numeric', 'min:0'],
            'target_margin_percent' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'notes' => ['nullable', 'string'],
        ]);

        $oldValues = $project->toArray();

        $project->update($validated);

        ActivityLog::record(
            'updated',
            'Project',
            $project->id,
            "Memperbarui proyek {$project->project_code}",
            $oldValues,
            $project->toArray()
        );

        return redirect()->route('projects.show', $project)->with('success', 'Data proyek berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        // Prevent deletion if financial transactions exist
        if ($project->customerInvoices()->exists() || $project->purchaseOrders()->exists() || $project->journalEntries()->exists()) {
            return back()->with('error', 'Proyek tidak dapat dihapus karena sudah memiliki transaksi keuangan/pembelian terkait.');
        }

        $project->delete();

        ActivityLog::record(
            'deleted',
            'Project',
            $project->id,
            "Menghapus proyek {$project->project_code}"
        );

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dihapus.');
    }
}
