@extends('layouts.app')

@section('title', 'Neraca Saldo (Trial Balance)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Neraca Saldo (Trial Balance)</h1>
            <p class="text-sm text-slate-500 mt-1">Pemeriksaan keseimbangan saldo debit dan kredit seluruh akun buku besar.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('accounting.coa') }}" class="btn-secondary text-sm">Bagan Akun (COA)</a>
            <a href="{{ route('accounting.journals') }}" class="btn-secondary text-sm">Jurnal Umum</a>
        </div>
    </div>

    <!-- Balance Status Banner -->
    @php
        $isBalanced = abs($grandDebit - $grandCredit) < 0.01;
    @endphp
    <div class="p-4 rounded-xl flex items-center justify-between {{ $isBalanced ? 'bg-emerald-50 text-emerald-900 border border-emerald-200' : 'bg-rose-50 text-rose-900 border border-rose-200' }}">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg {{ $isBalanced ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }} flex items-center justify-center">
                <i data-lucide="{{ $isBalanced ? 'check-circle' : 'alert-circle' }}" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm">{{ $isBalanced ? 'NERACA SALDO SEIMBANG (BALANCED)' : 'NERACA SALDO TIDAK SEIMBANG' }}</h4>
                <p class="text-xs text-slate-600 mt-0.5">
                    {{ $isBalanced ? 'Total saldo debit sama persis dengan total saldo kredit sesuai standar akuntansi double-entry.' : 'Terjadi ketidakseimbangan saldo antara sisi debit dan kredit. Harap periksa jurnal!' }}
                </p>
            </div>
        </div>
        <div class="text-right">
            <span class="text-xs uppercase tracking-wider text-slate-500 block">Total Keseimbangan</span>
            <span class="font-mono text-lg font-extrabold {{ $isBalanced ? 'text-emerald-700' : 'text-rose-700' }}">@rupiah($grandDebit)</span>
        </div>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-36">Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Klasifikasi</th>
                        <th class="text-right w-44">Saldo Debit (IDR)</th>
                        <th class="text-right w-44">Saldo Kredit (IDR)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trialData as $row)
                        <tr>
                            <td class="font-mono text-xs font-semibold text-slate-600">{{ $row['account']->code }}</td>
                            <td class="font-medium text-navy-900">{{ $row['account']->name }}</td>
                            <td>
                                <span class="badge-neutral text-[10px] uppercase">{{ $row['account']->type }}</span>
                            </td>
                            <td class="text-right font-mono text-sm {{ $row['debit'] > 0 ? 'text-navy-950 font-bold' : 'text-slate-300' }}">
                                {{ $row['debit'] > 0 ? 'Rp ' . number_format($row['debit'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-right font-mono text-sm {{ $row['credit'] > 0 ? 'text-navy-950 font-bold' : 'text-slate-300' }}">
                                {{ $row['credit'] > 0 ? 'Rp ' . number_format($row['credit'], 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                <i data-lucide="scale" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada saldo pada akun buku besar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-navy-950 text-white font-bold">
                    <tr>
                        <td colspan="3" class="p-4 text-right uppercase text-xs tracking-wider text-slate-300">TOTAL NERACA SALDO:</td>
                        <td class="p-4 text-right font-mono text-base text-construction-400">@rupiah($grandDebit)</td>
                        <td class="p-4 text-right font-mono text-base text-construction-400">@rupiah($grandCredit)</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
