<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcontractor;
use App\Models\SubcontractorContract;
use App\Models\SubcontractorProgress;
use App\Models\SubcontractorInvoice;
use App\Models\SubcontractorPayment;
use App\Models\Project;
use App\Models\BankAccount;
use App\Models\ActivityLog;
use App\Services\SequenceService;
use App\Services\AccountingService;
use Illuminate\Support\Facades\DB;
use Exception;

class SubcontractorController extends Controller
{
    public function index(Request $request)
    {
        $query = Subcontractor::withCount('contracts');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('specialty', 'like', "%{$search}%");
        }

        $subcontractors = $query->paginate(15)->withQueryString();
        $projects = Project::whereIn('status', ['preparation', 'in_progress'])->get();

        return view('subcontractors.index', compact('subcontractors', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'specialty' => ['required', 'string', 'max:100'],
            'contact_person' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string', 'max:50'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_account_holder' => ['nullable', 'string', 'max:100'],
        ]);

        $code = SequenceService::generate('SUB', 'subcontractors', 'code');

        $data = array_merge($validated, [
            'code' => $code,
            'bank_account_no' => $validated['bank_account_number'] ?? null,
            'bank_account_name' => $validated['bank_account_holder'] ?? null,
            'is_active' => true,
        ]);

        $sub = Subcontractor::create($data);

        ActivityLog::record(
            'created',
            'Subcontractor',
            $sub->id,
            "Mendaftarkan mitra subkontraktor {$sub->name} ({$sub->specialty})"
        );

        return back()->with('success', "Subkontraktor {$sub->name} ({$sub->code}) berhasil didaftarkan.");
    }

    public function contracts(Request $request)
    {
        $query = SubcontractorContract::with(['subcontractor', 'project', 'progressUpdates', 'invoices']);

        if ($projectId = $request->input('project_id')) {
            $query->where('project_id', $projectId);
        }

        $contracts = $query->latest()->paginate(15)->withQueryString();
        $subcontractors = Subcontractor::all();
        $projects = Project::whereIn('status', ['preparation', 'in_progress'])->get();

        return view('subcontractors.contracts', compact('contracts', 'subcontractors', 'projects'));
    }

    public function storeContract(Request $request)
    {
        $validated = $request->validate([
            'subcontractor_id' => ['required', 'exists:subcontractors,id'],
            'project_id' => ['required', 'exists:projects,id'],
            'work_scope' => ['required', 'string'],
            'contract_amount' => ['required', 'numeric', 'min:1000'],
            'retention_percent' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $contractNumber = SequenceService::generate('SPK-SUB', 'subcontractor_contracts', 'contract_number');

        $contract = SubcontractorContract::create([
            'contract_number' => $contractNumber,
            'subcontractor_id' => $validated['subcontractor_id'],
            'project_id' => $validated['project_id'],
            'scope_of_work' => $validated['work_scope'],
            'work_scope' => $validated['work_scope'],
            'contract_amount' => $validated['contract_amount'],
            'retention_percent' => $validated['retention_percent'] ?? 5,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'active',
        ]);

        ActivityLog::record(
            'created',
            'SubcontractorContract',
            $contract->id,
            "Membuat SPK Subkontraktor {$contract->contract_number} senilai Rp " . number_format($contract->contract_amount, 0, ',', '.')
        );

        return back()->with('success', "Kontrak SPK {$contract->contract_number} berhasil dibuat.");
    }

    public function storeProgress(Request $request)
    {
        $validated = $request->validate([
            'subcontractor_contract_id' => ['required', 'exists:subcontractor_contracts,id'],
            'inspection_date' => ['required', 'date'],
            'progress_percent' => ['required', 'numeric', 'min:1', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $contract = SubcontractorContract::findOrFail($validated['subcontractor_contract_id']);
        $progressPercent = (float) $validated['progress_percent'];
        $certifiedAmount = ($contract->contract_amount * ($progressPercent / 100));

        $bapNumber = SequenceService::generate('BAP-SUB', 'subcontractor_progress', 'progress_number');

        $progress = SubcontractorProgress::create([
            'progress_number' => $bapNumber,
            'bpa_number' => $bapNumber,
            'subcontractor_contract_id' => $contract->id,
            'inspection_date' => $validated['inspection_date'],
            'progress_date' => $validated['inspection_date'],
            'claimed_progress_percent' => $progressPercent,
            'approved_progress_percent' => $progressPercent,
            'progress_percent' => $progressPercent,
            'certified_amount' => $certifiedAmount,
            'status' => 'certified',
            'approved_by' => auth()->id() ?? 1,
            'notes' => $validated['notes'] ?? null,
        ]);

        ActivityLog::record(
            'created',
            'SubcontractorProgress',
            $progress->id,
            "Sertifikasi BAP Progres Subkon {$progress->progress_number} ({$progressPercent}%) senilai Rp " . number_format($certifiedAmount, 0, ',', '.')
        );

        return back()->with('success', "Berita Acara Progres (BAP) {$progress->progress_number} berhasil disahkan.");
    }
}
