@extends('layouts.app')

@section('title', 'Laba Rugi Proyek (Project P&L)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Laba Rugi Proyek (Project P&L)</h1>
            <p class="text-sm text-slate-500 mt-1">Laporan kinerja laba/rugi setiap proyek berdasarkan pendapatan diakui vs biaya aktual riil.</p>
        </div>
        <a href="{{ route('reports.cost-control') }}" class="btn-secondary text-sm">Cost Control Proyek</a>
    </div>

    @php
        $grandContract = 0;
        $grandRevenue = 0;
        $grandCost = 0;
        $grandProfit = 0;
        foreach($projectReports as $report) {
            $grandContract += (float)$report['project']->contract_amount;
            $grandRevenue += (float)$report['summary']['recognized_revenue'];
            $grandCost += (float)$report['summary']['total_actual_cost'];
            $grandProfit += (float)$report['summary']['gross_profit'];
        }
    @endphp

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5 border-l-4 border-navy-700">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Nilai Kontrak Proyek</span>
            <h3 class="text-xl font-black text-navy-950 font-mono mt-1">@rupiah($grandContract)</h3>
            <p class="text-xs text-slate-400 mt-1">Total seluruh proyek aktif</p>
        </div>
        <div class="card p-5 border-l-4 border-sky-500">
            <span class="text-xs font-bold uppercase tracking-wider text-sky-700">Pendapatan Diakui</span>
            <h3 class="text-xl font-black text-sky-950 font-mono mt-1">@rupiah($grandRevenue)</h3>
            <p class="text-xs text-slate-400 mt-1">Berdasarkan progres sertifikasi</p>
        </div>
        <div class="card p-5 border-l-4 border-rose-500">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-700">Total Biaya Riil (COGS)</span>
            <h3 class="text-xl font-black text-rose-600 font-mono mt-1">@rupiah($grandCost)</h3>
            <p class="text-xs text-slate-400 mt-1">Material, tukang, subkon & alat</p>
        </div>
        <div class="card p-5 border-l-4 border-emerald-500">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Laba Kotor Konsolidasi</span>
            <h3 class="text-xl font-black text-emerald-600 font-mono mt-1">@rupiah($grandProfit)</h3>
            <p class="text-xs text-emerald-600 font-bold mt-1">
                Margin: {{ $grandRevenue > 0 ? round(($grandProfit / $grandRevenue) * 100, 2) : 0 }}%
            </p>
        </div>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th">Kode & Nama Proyek</th>
                        <th class="table-th">Pelanggan</th>
                        <th class="table-th text-right">Nilai Kontrak</th>
                        <th class="table-th text-right">Pendapatan Diakui</th>
                        <th class="table-th text-right">Biaya Aktual</th>
                        <th class="table-th text-right">Laba Kotor Proyek</th>
                        <th class="table-th text-right">Margin (%)</th>
                        <th class="table-th text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projectReports as $report)
                        @php
                            $p = $report['project'];
                            $s = $report['summary'];
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td">
                                <a href="{{ route('projects.show', $p) }}" class="font-bold text-navy-900 hover:text-construction-600 transition-colors">
                                    {{ $p->name }}
                                </a>
                                <div class="text-xs font-mono text-slate-400">{{ $p->code }}</div>
                            </td>
                            <td class="table-td">
                                <div class="text-xs font-semibold text-slate-800">{{ $p->customer?->name }}</div>
                            </td>
                            <td class="table-td text-right font-mono text-xs whitespace-nowrap">@rupiah($p->contract_amount)</td>
                            <td class="table-td text-right font-mono font-medium text-navy-950 whitespace-nowrap">@rupiah($s['recognized_revenue'])</td>
                            <td class="table-td text-right font-mono text-xs text-rose-600 whitespace-nowrap">@rupiah($s['total_actual_cost'])</td>
                            <td class="table-td text-right font-mono font-bold whitespace-nowrap {{ $s['gross_profit'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                @rupiah($s['gross_profit'])
                            </td>
                            <td class="table-td text-right font-mono font-black whitespace-nowrap {{ $s['profit_margin_percent'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $s['profit_margin_percent'] }}%
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                @if($p->status === 'completed')
                                    <span class="badge badge-success text-[10px]">Selesai</span>
                                @elseif($p->status === 'in_progress')
                                    <span class="badge badge-primary text-[10px]">Berjalan</span>
                                @else
                                    <span class="badge badge-neutral text-[10px]">{{ ucfirst($p->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <i data-lucide="pie-chart" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada proyek dengan kontrak aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-navy-950 text-white font-bold">
                    <tr>
                        <td colspan="2" class="p-4 text-right uppercase text-xs tracking-wider text-slate-300">KONSOLIDASI SELURUH PROYEK:</td>
                        <td class="p-4 text-right font-mono text-xs text-slate-300">@rupiah($grandContract)</td>
                        <td class="p-4 text-right font-mono text-sm text-white">@rupiah($grandRevenue)</td>
                        <td class="p-4 text-right font-mono text-xs text-rose-300">@rupiah($grandCost)</td>
                        <td class="p-4 text-right font-mono text-sm text-construction-400">@rupiah($grandProfit)</td>
                        <td class="p-4 text-right font-mono text-xs text-construction-400">
                            {{ $grandRevenue > 0 ? round(($grandProfit / $grandRevenue) * 100, 2) : 0 }}%
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
