@extends('layouts.app')

@section('title', 'Cost Control & Anggaran Proyek')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Cost Control (Anggaran vs Realisasi)</h1>
            <p class="text-sm text-slate-500 mt-1">Pengawasan penyerapan anggaran RAB, komitmen purchase order yang belum terealisasi, dan varians biaya proyek.</p>
        </div>
    </div>

    <!-- Project Selector -->
    <div class="card p-4">
        <form method="GET" action="{{ route('reports.cost-control') }}" class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex-1">
                <label class="form-label text-xs">Pilih Proyek Konstruksi</label>
                <select name="project_id" class="form-select text-sm" onchange="this.form.submit()">
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ $selectedProjectId == $p->id ? 'selected' : '' }}>
                            {{ $p->code }} - {{ $p->name }} (Status: {{ ucfirst($p->status) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary text-sm">Lihat Analisis</button>
            </div>
        </form>
    </div>

    @if($costData && $selectedProject)
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="card p-5">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Anggaran Disetujui (RAB)</span>
                <h3 class="text-xl font-bold text-navy-950 font-mono mt-1">@rupiah($costData['active_budget'])</h3>
                <p class="text-xs text-slate-400 mt-1">Basis kontrol biaya resmi</p>
            </div>

            <div class="card p-5">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Biaya Aktual Terjadi</span>
                <h3 class="text-xl font-bold {{ $costData['is_over_budget'] ? 'text-rose-600' : 'text-navy-950' }} font-mono mt-1">
                    @rupiah($costData['total_actual_cost'])
                </h3>
                <p class="text-xs text-slate-400 mt-1">Penyerapan: <strong class="text-construction-600">{{ $costData['absorption_percent'] }}%</strong></p>
            </div>

            <div class="card p-5">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Komitmen PO Terbuka</span>
                <h3 class="text-xl font-bold text-amber-600 font-mono mt-1">@rupiah($costData['committed_po'])</h3>
                <p class="text-xs text-slate-400 mt-1">PO disetujui belum diterima</p>
            </div>

            <div class="card p-5">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Sisa Anggaran Tersedia</span>
                <h3 class="text-xl font-bold {{ $costData['variance'] < 0 ? 'text-rose-600' : 'text-emerald-600' }} font-mono mt-1">
                    @rupiah($costData['variance'])
                </h3>
                <p class="text-xs {{ $costData['variance'] < 0 ? 'text-rose-400' : 'text-emerald-500' }} mt-1">
                    {{ $costData['variance'] < 0 ? 'OVER BUDGET!' : 'Under budget / aman' }}
                </p>
            </div>
        </div>

        <!-- Progress Absorption Bar -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-navy-950">Tingkat Penyerapan Anggaran Proyek</span>
                <span class="text-sm font-extrabold font-mono text-construction-600">{{ $costData['absorption_percent'] }}%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                <div class="h-3 rounded-full {{ $costData['is_over_budget'] ? 'bg-rose-600' : 'bg-gradient-to-r from-construction-500 to-emerald-500' }}"
                     style="width: {{ min(100, $costData['absorption_percent']) }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-slate-400 mt-2 font-mono">
                <span>Rp 0</span>
                <span>Anggaran RAB: @rupiah($costData['active_budget'])</span>
            </div>
        </div>

        <!-- Breakdown by Cost Category -->
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-navy-950">Komposisi Biaya Aktual Proyek: {{ $selectedProject->name }}</h3>
            </div>
            <div class="table-container">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="table-th">Kategori Biaya Operasional</th>
                            <th class="table-th text-right">Realisasi Aktual (IDR)</th>
                            <th class="table-th text-right">Porsi Biaya</th>
                            <th class="table-th">Status Pencatatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-bold text-navy-900">
                                <i data-lucide="boxes" class="w-4 h-4 inline mr-2 text-construction-500"></i>
                                Biaya Material Lapangan (Bahan Konstruksi)
                            </td>
                            <td class="table-td text-right font-mono font-bold text-navy-950">@rupiah($costData['actual_material'])</td>
                            <td class="table-td text-right font-mono text-xs">
                                {{ $costData['total_actual_cost'] > 0 ? round(($costData['actual_material'] / $costData['total_actual_cost']) * 100, 1) : 0 }}%
                            </td>
                            <td class="table-td"><span class="badge badge-success text-xs">Tercatat Jurnal</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-bold text-navy-900">
                                <i data-lucide="users" class="w-4 h-4 inline mr-2 text-blue-500"></i>
                                Biaya Tenaga Kerja & Mandor (Upah & Lembur)
                            </td>
                            <td class="table-td text-right font-mono font-bold text-navy-950">@rupiah($costData['actual_labor'])</td>
                            <td class="table-td text-right font-mono text-xs">
                                {{ $costData['total_actual_cost'] > 0 ? round(($costData['actual_labor'] / $costData['total_actual_cost']) * 100, 1) : 0 }}%
                            </td>
                            <td class="table-td"><span class="badge badge-success text-xs">Tercatat Jurnal</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-bold text-navy-900">
                                <i data-lucide="hammer" class="w-4 h-4 inline mr-2 text-indigo-500"></i>
                                Biaya Subkontraktor & Pekerjaan Borongan
                            </td>
                            <td class="table-td text-right font-mono font-bold text-navy-950">@rupiah($costData['actual_subcon'])</td>
                            <td class="table-td text-right font-mono text-xs">
                                {{ $costData['total_actual_cost'] > 0 ? round(($costData['actual_subcon'] / $costData['total_actual_cost']) * 100, 1) : 0 }}%
                            </td>
                            <td class="table-td"><span class="badge badge-success text-xs">Tercatat Jurnal</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-bold text-navy-900">
                                <i data-lucide="truck" class="w-4 h-4 inline mr-2 text-amber-500"></i>
                                Biaya Sewa Alat Berat & Peralatan Proyek
                            </td>
                            <td class="table-td text-right font-mono font-bold text-navy-950">@rupiah($costData['actual_equipment'])</td>
                            <td class="table-td text-right font-mono text-xs">
                                {{ $costData['total_actual_cost'] > 0 ? round(($costData['actual_equipment'] / $costData['total_actual_cost']) * 100, 1) : 0 }}%
                            </td>
                            <td class="table-td"><span class="badge badge-success text-xs">Tercatat Jurnal</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-bold text-navy-900">
                                <i data-lucide="fuel" class="w-4 h-4 inline mr-2 text-slate-500"></i>
                                Biaya Operasional Lapangan & Transportasi
                            </td>
                            <td class="table-td text-right font-mono font-bold text-navy-950">@rupiah($costData['actual_other'])</td>
                            <td class="table-td text-right font-mono text-xs">
                                {{ $costData['total_actual_cost'] > 0 ? round(($costData['actual_other'] / $costData['total_actual_cost']) * 100, 1) : 0 }}%
                            </td>
                            <td class="table-td"><span class="badge badge-success text-xs">Tercatat Jurnal</span></td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-slate-50 font-bold border-t border-slate-200">
                        <tr>
                            <td class="table-td uppercase text-xs font-bold text-slate-600">TOTAL BIAYA AKTUAL TEREALISASI:</td>
                            <td class="table-td text-right font-mono text-base font-black text-navy-950">@rupiah($costData['total_actual_cost'])</td>
                            <td class="table-td text-right font-mono text-xs font-bold">100%</td>
                            <td class="table-td"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
