<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\Project;
use App\Services\AccountingService;
use App\Services\SequenceService;
use Illuminate\Support\Facades\DB;
use Exception;

class AccountingController extends Controller
{
    // ==========================================
    // CHART OF ACCOUNTS (COA)
    // ==========================================
    public function coa(Request $request)
    {
        $accounts = ChartOfAccount::with('children')->orderBy('code')->get();

        // Calculate current balance for each account from posted journal lines
        $balances = DB::table('journal_lines')
            ->join('journal_entries', 'journal_lines.journal_entry_id', '=', 'journal_entries.id')
            ->where('journal_entries.status', 'posted')
            ->select(
                'journal_lines.chart_of_account_id as coa_id',
                DB::raw('SUM(journal_lines.debit) as total_debit'),
                DB::raw('SUM(journal_lines.credit) as total_credit')
            )
            ->groupBy('journal_lines.chart_of_account_id')
            ->get()
            ->keyBy('coa_id');

        return view('accounting.chart_of_accounts', compact('accounts', 'balances'));
    }

    // ==========================================
    // JOURNAL ENTRIES
    // ==========================================
    public function journals(Request $request)
    {
        $query = JournalEntry::with(['lines.coa', 'project', 'reversedByEntry']);

        if ($projectId = $request->input('project_id')) {
            $query->where('project_id', $projectId);
        }

        if ($startDate = $request->input('start_date')) {
            $query->whereDate('entry_date', '>=', $startDate);
        }

        if ($endDate = $request->input('end_date')) {
            $query->whereDate('entry_date', '<=', $endDate);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('entry_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $journals = $query->latest('entry_date')->latest('id')->paginate(15)->withQueryString();
        $projects = Project::orderBy('name')->get();

        return view('accounting.journals', compact('journals', 'projects'));
    }

    public function createJournal()
    {
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('code')->get();
        $projects = Project::whereIn('status', ['preparation', 'in_progress'])->get();

        return view('accounting.journal_create', compact('accounts', 'projects'));
    }

    public function storeJournal(Request $request, AccountingService $accountingService)
    {
        $validated = $request->validate([
            'entry_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.coa_id' => ['required', 'exists:chart_of_accounts,id'],
            'lines.*.debit' => ['required', 'numeric', 'min:0'],
            'lines.*.credit' => ['required', 'numeric', 'min:0'],
            'lines.*.description' => ['nullable', 'string', 'max:255'],
        ]);

        $totalDebit = 0;
        $totalCredit = 0;
        foreach ($validated['lines'] as $line) {
            $totalDebit += (float) $line['debit'];
            $totalCredit += (float) $line['credit'];
        }

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withInput()->with('error', "Jurnal tidak seimbang! Total Debit (Rp " . number_format($totalDebit, 2) . ") harus sama dengan Total Kredit (Rp " . number_format($totalCredit, 2) . ")");
        }

        try {
            $entry = $accountingService->createAndPostEntry(
                $validated['description'],
                'manual_entry',
                null,
                $validated['project_id'] ?? null,
                $validated['lines'],
                $validated['entry_date']
            );

            return redirect()->route('accounting.journals')->with('success', "Jurnal umum {$entry->entry_number} berhasil diposting.");
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function reverseJournal(JournalEntry $journalEntry, Request $request, AccountingService $accountingService)
    {
        $validated = $request->validate([
            'reversal_reason' => ['required', 'string', 'min:5', 'max:255'],
        ]);

        try {
            $reversal = $accountingService->reverseJournalEntry($journalEntry->id, $validated['reversal_reason']);
            return back()->with('success', "Jurnal pembalik {$reversal->entry_number} berhasil diposting untuk membatalkan {$journalEntry->entry_number}.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ==========================================
    // GENERAL LEDGER (BUKU BESAR)
    // ==========================================
    public function generalLedger(Request $request)
    {
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('code')->get();
        $selectedCoaId = $request->input('coa_id', $accounts->first()?->id);

        $selectedAccount = ChartOfAccount::find($selectedCoaId);
        $lines = collect();

        if ($selectedAccount) {
            $lines = JournalLine::with(['journalEntry.project'])
                ->where('journal_lines.chart_of_account_id', $selectedAccount->id)
                ->whereHas('journalEntry', function ($q) {
                    $q->where('status', 'posted');
                })
                ->join('journal_entries', 'journal_lines.journal_entry_id', '=', 'journal_entries.id')
                ->orderBy('journal_entries.entry_date')
                ->orderBy('journal_entries.id')
                ->select('journal_lines.*')
                ->paginate(30)
                ->withQueryString();
        }

        return view('accounting.general_ledger', compact('accounts', 'selectedAccount', 'lines', 'selectedCoaId'));
    }

    // ==========================================
    // TRIAL BALANCE (NERACA SALDO)
    // ==========================================
    public function trialBalance(Request $request)
    {
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('code')->get();

        $journalData = DB::table('journal_lines')
            ->join('journal_entries', 'journal_lines.journal_entry_id', '=', 'journal_entries.id')
            ->where('journal_entries.status', 'posted')
            ->select(
                'journal_lines.chart_of_account_id as coa_id',
                DB::raw('SUM(journal_lines.debit) as sum_debit'),
                DB::raw('SUM(journal_lines.credit) as sum_credit')
            )
            ->groupBy('journal_lines.chart_of_account_id')
            ->get()
            ->keyBy('coa_id');

        $trialData = [];
        $grandDebit = 0;
        $grandCredit = 0;

        foreach ($accounts as $acc) {
            $data = $journalData->get($acc->id);
            $sumDebit = $data ? (float) $data->sum_debit : 0;
            $sumCredit = $data ? (float) $data->sum_credit : 0;

            if ($sumDebit == 0 && $sumCredit == 0) {
                continue;
            }

            $normalDebit = in_array($acc->type, ['asset', 'expense', 'cogs']);
            $net = $sumDebit - $sumCredit;

            if ($normalDebit) {
                $finalDebit = $net >= 0 ? $net : 0;
                $finalCredit = $net < 0 ? abs($net) : 0;
            } else {
                $finalCredit = ($sumCredit - $sumDebit) >= 0 ? ($sumCredit - $sumDebit) : 0;
                $finalDebit = ($sumCredit - $sumDebit) < 0 ? abs($sumCredit - $sumDebit) : 0;
            }

            $grandDebit += $finalDebit;
            $grandCredit += $finalCredit;

            $trialData[] = [
                'account' => $acc,
                'debit' => $finalDebit,
                'credit' => $finalCredit,
            ];
        }

        return view('accounting.trial_balance', compact('trialData', 'grandDebit', 'grandCredit'));
    }
}
