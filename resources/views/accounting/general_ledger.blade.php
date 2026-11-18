@extends('layouts.app')

@section('title', 'Buku Besar (General Ledger)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Buku Besar (General Ledger)</h1>
            <p class="text-sm text-slate-500 mt-1">Rincian mutasi debit, kredit, dan saldo berjalan per akun akuntansi.</p>
        </div>
        <a href="{{ route('accounting.coa') }}" class="btn-secondary text-sm">Bagan Akun (COA)</a>
    </div>

    <!-- Account Selector -->
    <div class="card p-4">
        <form method="GET" action="{{ route('accounting.general-ledger') }}" class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex-1">
                <label class="form-label text-xs">Pilih Akun Buku Besar</label>
                <select name="coa_id" class="form-select text-sm" onchange="this.form.submit()">
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}" {{ $selectedCoaId == $acc->id ? 'selected' : '' }}>
                            {{ $acc->code }} - {{ $acc->name }} ({{ ucfirst($acc->type) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary text-sm">Tampilkan</button>
            </div>
        </form>
    </div>

    @if($selectedAccount)
        <div class="card overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-bold text-navy-950">{{ $selectedAccount->code }} - {{ $selectedAccount->name }}</h3>
                    <p class="text-xs text-slate-500">Saldo Normal: <strong class="uppercase text-navy-900">{{ $selectedAccount->normal_balance }}</strong> | Klasifikasi: {{ ucfirst($selectedAccount->type) }}</p>
                </div>
            </div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Jurnal</th>
                            <th>Keterangan / Uraian</th>
                            <th>Proyek</th>
                            <th class="text-right">Debit (IDR)</th>
                            <th class="text-right">Kredit (IDR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalDeb = 0;
                            $totalCrd = 0;
                        @endphp
                        @forelse($lines as $line)
                            @php
                                $totalDeb += (float)$line->debit;
                                $totalCrd += (float)$line->credit;
                            @endphp
                            <tr>
                                <td>@dateid($line->journalEntry?->entry_date)</td>
                                <td class="font-mono text-xs font-bold text-navy-900">{{ $line->journalEntry?->entry_number }}</td>
                                <td class="text-xs text-slate-700">{{ $line->description ?: $line->journalEntry?->description }}</td>
                                <td>
                                    @if($line->journalEntry?->project)
                                        <span class="badge-neutral text-[10px]">{{ $line->journalEntry->project->name }}</span>
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="text-right font-mono text-xs font-medium {{ $line->debit > 0 ? 'text-navy-950 font-bold' : 'text-slate-300' }}">
                                    {{ $line->debit > 0 ? 'Rp ' . number_format($line->debit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="text-right font-mono text-xs font-medium {{ $line->credit > 0 ? 'text-navy-950 font-bold' : 'text-slate-300' }}">
                                    {{ $line->credit > 0 ? 'Rp ' . number_format($line->credit, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400">
                                    <i data-lucide="book-open" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                    Belum ada transaksi pembukuan untuk akun ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($lines->count() > 0)
                        @php
                            $isNormDebit = in_array($selectedAccount->type, ['asset', 'expense', 'cogs']);
                            $netBal = $isNormDebit ? ($totalDeb - $totalCrd) : ($totalCrd - $totalDeb);
                        @endphp
                        <tfoot class="bg-slate-50 font-bold border-t border-slate-200">
                            <tr>
                                <td colspan="4" class="p-3 text-right uppercase text-xs text-slate-500">Total Mutasi & Saldo Akhir:</td>
                                <td class="p-3 text-right font-mono text-navy-950">@rupiah($totalDeb)</td>
                                <td class="p-3 text-right font-mono text-navy-950">@rupiah($totalCrd)</td>
                            </tr>
                            <tr class="bg-navy-900 text-white">
                                <td colspan="4" class="p-3 text-right uppercase text-xs font-bold text-slate-300">Saldo Akhir Akun Ini:</td>
                                <td colspan="2" class="p-3 text-right font-mono text-base font-bold text-construction-400">@rupiah($netBal)</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            @if($lines instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="p-4 border-t border-slate-100">
                    {{ $lines->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
