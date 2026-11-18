@extends('layouts.app')

@section('title', 'Laporan Keuangan Perusahaan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Laporan Laba Rugi Komprehensif</h1>
            <p class="text-sm text-slate-500 mt-1">Laporan kinerja laba rugi konsolidasi perusahaan kontraktor dari pembukuan buku besar.</p>
        </div>
        <a href="{{ route('accounting.trial-balance') }}" class="btn-secondary text-sm">Neraca Saldo</a>
    </div>

    <!-- Income Statement Card -->
    <div class="card overflow-hidden">
        <div class="bg-navy-950 px-6 py-4 text-white flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg text-white">PT KONSTRUKSI MAJU NUSANTARA</h3>
                <p class="text-xs text-slate-400">Laporan Laba Rugi Operasional (Tahun Berjalan 2026)</p>
            </div>
            <div class="text-right font-mono text-xs text-construction-400 font-bold">
                Mata Uang: IDR (Rupiah)
            </div>
        </div>

        <div class="p-6 space-y-6 text-sm">
            <!-- 1. PENDAPATAN JASA KONSTRUKSI -->
            <div>
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400 mb-3 border-b border-slate-200 pb-1">
                    1. PENDAPATAN USAHA (REVENUE)
                </h4>
                <div class="space-y-2">
                    @foreach($revenues as $r)
                        @php
                            $revBal = $r->journalLines->sum('credit') - $r->journalLines->sum('debit');
                        @endphp
                        @if($revBal != 0)
                            <div class="flex justify-between text-slate-700">
                                <span class="pl-4">{{ $r->code }} - {{ $r->name }}</span>
                                <span class="font-mono font-medium">@rupiah($revBal)</span>
                            </div>
                        @endif
                    @endforeach
                    <div class="flex justify-between font-bold text-navy-950 pt-2 border-t border-slate-200">
                        <span>TOTAL PENDAPATAN KONSTRUKSI:</span>
                        <span class="font-mono text-emerald-600">@rupiah($totalRevenue)</span>
                    </div>
                </div>
            </div>

            <!-- 2. BEBAN POKOK PENDAPATAN (HPP KONSTRUKSI) -->
            <div>
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400 mb-3 border-b border-slate-200 pb-1">
                    2. BEBAN POKOK PENDAPATAN (COGS / BIAYA LANGSUNG PROYEK)
                </h4>
                <div class="space-y-2">
                    @foreach($directCosts as $c)
                        @php
                            $costBal = $c->journalLines->sum('debit') - $c->journalLines->sum('credit');
                        @endphp
                        @if($costBal != 0)
                            <div class="flex justify-between text-slate-700">
                                <span class="pl-4">{{ $c->code }} - {{ $c->name }}</span>
                                <span class="font-mono font-medium">@rupiah($costBal)</span>
                            </div>
                        @endif
                    @endforeach
                    <div class="flex justify-between font-bold text-navy-950 pt-2 border-t border-slate-200">
                        <span>TOTAL BIAYA LANGSUNG PROYEK (HPP):</span>
                        <span class="font-mono text-rose-600">@rupiah($totalDirectCost)</span>
                    </div>
                </div>
            </div>

            <!-- LABA KOTOR -->
            <div class="p-3 bg-slate-100 rounded-lg flex justify-between font-extrabold text-navy-950 text-base">
                <span>LABA KOTOR (GROSS PROFIT):</span>
                <span class="font-mono text-construction-600">@rupiah($grossProfit)</span>
            </div>

            <!-- 3. BEBAN OPERASIONAL & OVERHEAD -->
            <div>
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400 mb-3 border-b border-slate-200 pb-1">
                    3. BEBAN UMUM, ADMINISTRASI & OVERHEAD KANTOR
                </h4>
                <div class="space-y-2">
                    @foreach($overheadExpenses as $o)
                        @php
                            $overBal = $o->journalLines->sum('debit') - $o->journalLines->sum('credit');
                        @endphp
                        @if($overBal != 0)
                            <div class="flex justify-between text-slate-700">
                                <span class="pl-4">{{ $o->code }} - {{ $o->name }}</span>
                                <span class="font-mono font-medium">@rupiah($overBal)</span>
                            </div>
                        @endif
                    @endforeach
                    <div class="flex justify-between font-bold text-navy-950 pt-2 border-t border-slate-200">
                        <span>TOTAL BEBAN OVERHEAD OPERASIONAL:</span>
                        <span class="font-mono text-rose-600">@rupiah($totalOverhead)</span>
                    </div>
                </div>
            </div>

            <!-- LABA BERSIH OPERASIONAL -->
            <div class="p-4 bg-gradient-to-r from-navy-950 to-navy-900 text-white rounded-xl flex items-center justify-between">
                <div>
                    <span class="text-xs uppercase tracking-wider text-slate-300 font-semibold block">LABA BERSIH OPERASIONAL PERUSAHAAN (NET PROFIT):</span>
                    <span class="text-xs text-construction-400 mt-0.5 block">
                        Net Margin: {{ $totalRevenue > 0 ? round(($netOperatingProfit / $totalRevenue) * 100, 2) : 0 }}%
                    </span>
                </div>
                <span class="font-mono text-2xl font-black text-construction-400">@rupiah($netOperatingProfit)</span>
            </div>
        </div>
    </div>
</div>
@endsection
