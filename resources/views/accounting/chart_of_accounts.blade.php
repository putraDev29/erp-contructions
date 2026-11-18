@extends('layouts.app')

@section('title', 'Bagan Akun Standar (Chart of Accounts)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Bagan Akun (Chart of Accounts)</h1>
            <p class="text-sm text-slate-500 mt-1">Struktur akun akuntansi double-entry standar industri jasa konstruksi Indonesia.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('accounting.journals') }}" class="btn-secondary inline-flex items-center gap-2">
                <i data-lucide="book-open" class="w-4 h-4"></i>
                <span>Jurnal Umum</span>
            </a>
            <a href="{{ route('accounting.trial-balance') }}" class="btn-primary inline-flex items-center gap-2">
                <i data-lucide="scale" class="w-4 h-4"></i>
                <span>Neraca Saldo</span>
            </a>
        </div>
    </div>

    <!-- COA Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th w-36">Kode Akun</th>
                        <th class="table-th">Nama Akun</th>
                        <th class="table-th">Klasifikasi</th>
                        <th class="table-th text-center">Saldo Normal</th>
                        <th class="table-th text-right">Total Debit</th>
                        <th class="table-th text-right">Total Kredit</th>
                        <th class="table-th text-right">Saldo Bersih</th>
                        <th class="table-th text-center">Buku Besar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $acc)
                        @php
                            $bal = $balances->get($acc->id);
                            $deb = $bal ? (float)$bal->total_debit : 0;
                            $crd = $bal ? (float)$bal->total_credit : 0;
                            $isNormalDebit = in_array($acc->type, ['asset', 'expense', 'cogs']);
                            $net = $isNormalDebit ? ($deb - $crd) : ($crd - $deb);
                            $isHeader = $acc->parent_id === null && $acc->children->count() > 0;
                        @endphp
                        <tr class="{{ $isHeader ? 'bg-slate-100/70 font-bold text-navy-950' : 'hover:bg-slate-50/70 transition-colors' }}">
                            <td class="table-td font-mono text-xs {{ $isHeader ? 'font-black text-navy-950' : 'text-slate-600 pl-6' }}">
                                {{ $acc->code }}
                            </td>
                            <td class="table-td">
                                <span class="{{ $isHeader ? 'text-navy-950 font-bold uppercase text-xs tracking-wider' : 'text-slate-800 text-sm font-medium' }}">
                                    {{ $acc->name }}
                                </span>
                            </td>
                            <td class="table-td">
                                <span class="badge badge-neutral text-[10px] uppercase font-semibold">
                                    {{ $acc->type }}
                                </span>
                            </td>
                            <td class="table-td text-center text-xs font-mono font-bold text-slate-600">
                                {{ strtoupper($acc->normal_balance) }}
                            </td>
                            <td class="table-td text-right font-mono text-xs text-slate-600 whitespace-nowrap">
                                {{ $deb > 0 ? 'Rp ' . number_format($deb, 0, ',', '.') : '-' }}
                            </td>
                            <td class="table-td text-right font-mono text-xs text-slate-600 whitespace-nowrap">
                                {{ $crd > 0 ? 'Rp ' . number_format($crd, 0, ',', '.') : '-' }}
                            </td>
                            <td class="table-td text-right font-mono font-black text-navy-950 whitespace-nowrap">
                                @if($deb > 0 || $crd > 0)
                                    Rp {{ number_format($net, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                <a href="{{ route('accounting.general-ledger', ['account_id' => $acc->id]) }}" class="btn-secondary btn-xs text-[11px]" title="Lihat Buku Besar">
                                    <i data-lucide="book-open" class="w-3 h-3"></i>
                                    <span>Ledger</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
