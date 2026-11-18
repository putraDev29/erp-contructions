@extends('layouts.app')

@section('title', $project->name)
@section('page_title', 'Detail Proyek: ' . $project->project_code)

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'wbs', updateModalOpen: false, activeTask: null, newTaskModalOpen: false }">

    <!-- Top Project Banner -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-lg bg-navy-950 text-white">
                        {{ $project->project_code }}
                    </span>
                    @php
                        $badgeClass = match($project->status) {
                            'in_progress' => 'badge-primary',
                            'completed' => 'badge-success',
                            'tender', 'contract' => 'badge-info',
                            'preparation' => 'badge-warning',
                            'on_hold', 'cancelled' => 'badge-danger',
                            default => 'badge-neutral',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium uppercase tracking-wider">
                        {{ ucfirst(str_replace('_', ' ', $project->type)) }}
                    </span>
                </div>
                <h1 class="text-lg sm:text-2xl font-black text-navy-950 mt-2">{{ $project->name }}</h1>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 mt-1">
                    <span class="flex items-center gap-1">
                        <i data-lucide="building" class="w-3.5 h-3.5 text-slate-400"></i>
                        <strong class="text-slate-700">{{ $project->customer?->name }}</strong>
                    </span>
                    <span class="flex items-center gap-1">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>{{ $project->location ?: 'Lokasi belum diset' }}</span>
                    </span>
                    <span class="flex items-center gap-1">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>Target: @dateid($project->deadline_date)</span>
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 self-start md:self-auto">
                <a href="{{ route('projects.edit', $project) }}" class="btn-secondary text-xs">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    <span>Edit Proyek</span>
                </a>
                <a href="{{ route('budgets.index', ['project_id' => $project->id]) }}" class="btn-primary text-xs">
                    <i data-lucide="calculator" class="w-4 h-4"></i>
                    <span>Kelola RAB</span>
                </a>
            </div>
        </div>

        <!-- 6 Summary Stat Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 mt-5 text-xs">
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                <span class="text-slate-400 font-medium block">Nilai Kontrak Aktif</span>
                <span class="font-extrabold text-navy-950 text-sm mt-0.5 block truncate">@rupiah($project->active_contract_amount)</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                <span class="text-slate-400 font-medium block">Pagu Anggaran RAB</span>
                <span class="font-extrabold text-slate-800 text-sm mt-0.5 block truncate">@rupiah($project->active_budget_amount)</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                <span class="text-slate-400 font-medium block">Biaya Aktual (Journal)</span>
                <span class="font-extrabold text-rose-600 text-sm mt-0.5 block truncate">@rupiah($costSummary['total_actual_cost'])</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                <span class="text-slate-400 font-medium block">Sisa Anggaran</span>
                <span class="font-extrabold text-emerald-700 text-sm mt-0.5 block truncate">@rupiah($costSummary['remaining_budget'])</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                <span class="text-slate-400 font-medium block">Penyerapan Anggaran</span>
                <span class="font-extrabold text-construction-600 text-sm mt-0.5 block">{{ $costSummary['absorption_percent'] }}%</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                <span class="text-slate-400 font-medium block">Progres Fisik WBS</span>
                <span class="font-extrabold text-navy-900 text-sm mt-0.5 block">{{ $project->physical_progress_percent }}%</span>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-slate-200">
        <nav class="flex space-x-2 sm:space-x-6 overflow-x-auto text-xs sm:text-sm font-semibold">
            <button @click="activeTab = 'wbs'" 
                    :class="activeTab === 'wbs' ? 'border-construction-600 text-construction-600 border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="py-3 px-2 flex items-center gap-2 whitespace-nowrap transition-colors">
                <i data-lucide="kanban" class="w-4 h-4"></i>
                <span>WBS & Progres Lapangan</span>
            </button>
            <button @click="activeTab = 'cost_control'" 
                    :class="activeTab === 'cost_control' ? 'border-construction-600 text-construction-600 border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="py-3 px-2 flex items-center gap-2 whitespace-nowrap transition-colors">
                <i data-lucide="trending-up" class="w-4 h-4"></i>
                <span>Cost Control & Realisasi</span>
            </button>
            <button @click="activeTab = 'invoices'" 
                    :class="activeTab === 'invoices' ? 'border-construction-600 text-construction-600 border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="py-3 px-2 flex items-center gap-2 whitespace-nowrap transition-colors">
                <i data-lucide="receipt" class="w-4 h-4"></i>
                <span>Tagihan Pelanggan (AR) ({{ $project->customerInvoices->count() }})</span>
            </button>
            <button @click="activeTab = 'po'" 
                    :class="activeTab === 'po' ? 'border-construction-600 text-construction-600 border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="py-3 px-2 flex items-center gap-2 whitespace-nowrap transition-colors">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                <span>Purchase Orders ({{ $project->purchaseOrders->count() }})</span>
            </button>
            <button @click="activeTab = 'vo'" 
                    :class="activeTab === 'vo' ? 'border-construction-600 text-construction-600 border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="py-3 px-2 flex items-center gap-2 whitespace-nowrap transition-colors">
                <i data-lucide="git-pull-request" class="w-4 h-4"></i>
                <span>Variation Orders ({{ $project->variationOrders->count() }})</span>
            </button>
        </nav>
    </div>

    <!-- Tab 1: WBS Content -->
    <div x-show="activeTab === 'wbs'" class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-navy-950 uppercase tracking-wider">Work Breakdown Structure (WBS)</h2>
            <button @click="newTaskModalOpen = true" class="btn-primary text-xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Item WBS</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr>
                            <th class="table-th">Kode</th>
                            <th class="table-th">Nama Pekerjaan</th>
                            <th class="table-th text-center">Bobot (%)</th>
                            <th class="table-th">Volume Rencana</th>
                            <th class="table-th">Jadwal Pelaksanaan</th>
                            <th class="table-th">Progres Fisik</th>
                            <th class="table-th text-center">Status</th>
                            <th class="table-th text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($project->tasks as $task)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="table-td font-mono font-bold text-navy-900">{{ $task->task_code }}</td>
                                <td class="table-td font-semibold text-slate-800">{{ $task->name }}</td>
                                <td class="table-td text-center font-bold text-slate-700">{{ $task->weight_percent }}%</td>
                                <td class="table-td text-slate-600">{{ $task->planned_volume }} {{ $task->unit }}</td>
                                <td class="table-td whitespace-nowrap text-slate-500 text-[11px]">
                                    @dateid($task->start_date) - @dateid($task->end_date)
                                </td>
                                <td class="table-td min-w-[130px]">
                                    <div class="flex items-center justify-between text-[11px] font-bold text-slate-700 mb-1">
                                        <span>{{ $task->progress_percent }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-construction-500 h-2 rounded-full transition-all" 
                                             style="width: {{ min(100, $task->progress_percent) }}%"></div>
                                    </div>
                                </td>
                                <td class="table-td text-center whitespace-nowrap">
                                    <span class="badge {{ $task->status === 'completed' ? 'badge-success' : ($task->status === 'in_progress' ? 'badge-primary' : 'badge-neutral') }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </td>
                                <td class="table-td text-center whitespace-nowrap">
                                    <button @click="activeTask = {{ json_encode($task) }}; updateModalOpen = true" 
                                            class="btn-secondary text-[11px] py-1 px-2.5">
                                        <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                                        <span>Update Progres</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400">
                                    Belum ada tahapan WBS yang didaftarkan pada proyek ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab 2: Cost Control Content -->
    <div x-show="activeTab === 'cost_control'" style="display: none;" class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h2 class="text-sm font-bold text-navy-950 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                Laporan Perbandingan Anggaran & Realisasi Biaya Proyek
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 text-xs">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-slate-400 font-medium block">Pagu Anggaran Aktif</span>
                    <span class="font-extrabold text-slate-900 text-base mt-1 block">@rupiah($costSummary['active_budget'])</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-slate-400 font-medium block">Total Biaya Aktual</span>
                    <span class="font-extrabold text-rose-600 text-base mt-1 block">@rupiah($costSummary['total_actual_cost'])</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-slate-400 font-medium block">Komitmen PO Terbuka</span>
                    <span class="font-extrabold text-amber-600 text-base mt-1 block">@rupiah($costSummary['committed_po'])</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-slate-400 font-medium block">Varians Anggaran</span>
                    <span class="font-extrabold {{ $costSummary['variance'] >= 0 ? 'text-emerald-700' : 'text-rose-600' }} text-base mt-1 block">
                        @rupiah($costSummary['variance'])
                    </span>
                </div>
            </div>

            <!-- Breakdown by Category Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr>
                            <th class="table-th">Kategori Pengeluaran Proyek</th>
                            <th class="table-th text-right">Realisasi Biaya Aktual (IDR)</th>
                            <th class="table-th text-right">Proporsi Biaya (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $tot = $costSummary['total_actual_cost'] > 0 ? $costSummary['total_actual_cost'] : 1;
                        @endphp
                        <tr>
                            <td class="table-td font-semibold text-slate-800">1. Beban Material Konstruksi</td>
                            <td class="table-td text-right font-bold text-slate-900">@rupiah($costSummary['actual_material'])</td>
                            <td class="table-td text-right font-medium text-slate-600">{{ round(($costSummary['actual_material'] / $tot) * 100, 1) }}%</td>
                        </tr>
                        <tr>
                            <td class="table-td font-semibold text-slate-800">2. Beban Upah & Tenaga Kerja Lapangan</td>
                            <td class="table-td text-right font-bold text-slate-900">@rupiah($costSummary['actual_labor'])</td>
                            <td class="table-td text-right font-medium text-slate-600">{{ round(($costSummary['actual_labor'] / $tot) * 100, 1) }}%</td>
                        </tr>
                        <tr>
                            <td class="table-td font-semibold text-slate-800">3. Beban Subkontraktor</td>
                            <td class="table-td text-right font-bold text-slate-900">@rupiah($costSummary['actual_subcon'])</td>
                            <td class="table-td text-right font-medium text-slate-600">{{ round(($costSummary['actual_subcon'] / $tot) * 100, 1) }}%</td>
                        </tr>
                        <tr>
                            <td class="table-td font-semibold text-slate-800">4. Beban Sewa & Operasional Alat Berat</td>
                            <td class="table-td text-right font-bold text-slate-900">@rupiah($costSummary['actual_equipment'])</td>
                            <td class="table-td text-right font-medium text-slate-600">{{ round(($costSummary['actual_equipment'] / $tot) * 100, 1) }}%</td>
                        </tr>
                        <tr class="bg-slate-50 font-bold">
                            <td class="table-td text-navy-950">TOTAL REALISASI BIAYA PROYEK</td>
                            <td class="table-td text-right text-navy-950">@rupiah($costSummary['total_actual_cost'])</td>
                            <td class="table-td text-right text-navy-950">100.0%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab 3: Customer Invoices Content -->
    <div x-show="activeTab === 'invoices'" style="display: none;" class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-navy-950 uppercase tracking-wider">Daftar Tagihan & Termin Pelanggan</h2>
            <a href="{{ route('customer-invoices.create', ['project_id' => $project->id]) }}" class="btn-primary text-xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Terbitkan Invoice Baru</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr>
                            <th class="table-th">No. Invoice</th>
                            <th class="table-th">Deskripsi Termin / Milestone</th>
                            <th class="table-th">Tgl. Terbit</th>
                            <th class="table-th text-right">Nilai Tagihan</th>
                            <th class="table-th text-right">Sudah Dibayar</th>
                            <th class="table-th text-right">Sisa Piutang</th>
                            <th class="table-th text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($project->customerInvoices as $inv)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="table-td font-bold text-navy-900">{{ $inv->invoice_number }}</td>
                                <td class="table-td font-semibold text-slate-800">{{ $inv->milestone_description }}</td>
                                <td class="table-td whitespace-nowrap">@dateid($inv->invoice_date)</td>
                                <td class="table-td text-right font-bold text-slate-900">@rupiah($inv->net_amount)</td>
                                <td class="table-td text-right font-medium text-emerald-700">@rupiah($inv->paid_amount)</td>
                                <td class="table-td text-right font-bold text-rose-600">@rupiah($inv->remaining_amount)</td>
                                <td class="table-td text-center">
                                    <span class="badge {{ $inv->status === 'paid' ? 'badge-success' : ($inv->status === 'partially_paid' ? 'badge-warning' : 'badge-neutral') }}">
                                        {{ ucfirst($inv->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">
                                    Belum ada tagihan termin yang diterbitkan untuk proyek ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab 4: PO Content -->
    <div x-show="activeTab === 'po'" style="display: none;" class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-navy-950 uppercase tracking-wider">Purchase Order Pembelian Material</h2>
            <a href="{{ route('purchase-orders.create', ['project_id' => $project->id]) }}" class="btn-primary text-xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Buat PO Baru</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr>
                            <th class="table-th">No. PO</th>
                            <th class="table-th">Supplier</th>
                            <th class="table-th">Tanggal PO</th>
                            <th class="table-th text-right">Total Nilai PO</th>
                            <th class="table-th text-center">Status</th>
                            <th class="table-th text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($project->purchaseOrders as $po)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="table-td font-bold text-navy-900">{{ $po->po_number }}</td>
                                <td class="table-td font-semibold text-slate-800">{{ $po->supplier?->name }}</td>
                                <td class="table-td whitespace-nowrap">@dateid($po->po_date)</td>
                                <td class="table-td text-right font-bold text-slate-900">@rupiah($po->total_amount)</td>
                                <td class="table-td text-center">
                                    <span class="badge badge-primary">
                                        {{ ucfirst(str_replace('_', ' ', $po->status)) }}
                                    </span>
                                </td>
                                <td class="table-td text-center">
                                    <a href="{{ route('purchase-orders.show', $po) }}" class="btn-secondary text-[11px] py-1 px-2.5">
                                        Detail PO
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400">
                                    Belum ada Purchase Order material pada proyek ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab 5: VO Content -->
    <div x-show="activeTab === 'vo'" style="display: none;" class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-navy-950 uppercase tracking-wider">Variation Order (Pekerjaan Tambah / Kurang)</h2>
            <a href="{{ route('variation-orders.create', ['project_id' => $project->id]) }}" class="btn-primary text-xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Ajukan VO Baru</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr>
                            <th class="table-th">No. VO</th>
                            <th class="table-th">Deskripsi Perubahan</th>
                            <th class="table-th text-right">Dampak Kontrak (IDR)</th>
                            <th class="table-th text-right">Dampak Budget (IDR)</th>
                            <th class="table-th text-center">Status</th>
                            <th class="table-th text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($project->variationOrders as $vo)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="table-td font-bold text-navy-900">{{ $vo->vo_number }}</td>
                                <td class="table-td font-medium text-slate-800">{{ $vo->description }}</td>
                                <td class="table-td text-right font-bold {{ $vo->contract_amount_change >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                    @rupiah($vo->contract_amount_change)
                                </td>
                                <td class="table-td text-right font-medium text-slate-700">
                                    @rupiah($vo->budget_amount_change)
                                </td>
                                <td class="table-td text-center">
                                    <span class="badge {{ $vo->status === 'approved' ? 'badge-success' : ($vo->status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                        {{ ucfirst($vo->status) }}
                                    </span>
                                </td>
                                <td class="table-td text-center">
                                    @if($vo->status === 'draft')
                                        <form method="POST" action="{{ route('variation-orders.approve', $vo) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-accent text-[11px] py-1 px-2.5">Setujui VO</button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 text-[11px]">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400">
                                    Belum ada catatan pekerjaan tambah/kurang (VO).
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal: Tambah Item WBS Baru -->
    <div x-show="newTaskModalOpen" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" 
         style="display: none;">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200" @click.away="newTaskModalOpen = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="font-extrabold text-navy-950 text-sm sm:text-base">Tambah Item WBS Baru</h3>
                <button @click="newTaskModalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('projects.tasks.store', $project) }}" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Kode WBS</label>
                        <input type="text" name="task_code" required placeholder="Contoh: WBS-01.01" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Bobot Pekerjaan (%)</label>
                        <input type="number" step="0.01" name="weight_percent" required placeholder="Contoh: 15.00" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nama Item Pekerjaan</label>
                    <input type="text" name="name" required placeholder="Contoh: Fabrikasi Balok & Kolom Baja WF" 
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Volume Rencana</label>
                        <input type="number" step="0.0001" name="planned_volume" value="1" required 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Satuan</label>
                        <input type="text" name="unit" value="ls" required 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Target Selesai</label>
                        <input type="date" name="end_date" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="newTaskModalOpen = false" class="btn-secondary text-xs">Batal</button>
                    <button type="submit" class="btn-primary text-xs">Simpan Item WBS</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Update Progres Lapangan -->
    <div x-show="updateModalOpen" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" 
         style="display: none;">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200" @click.away="updateModalOpen = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="font-extrabold text-navy-950 text-sm sm:text-base">Update Progres Lapangan</h3>
                <button @click="updateModalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form x-bind:action="'/projects/tasks/' + (activeTask ? activeTask.id : '') + '/progress'" 
                  method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Item Pekerjaan WBS</span>
                    <p class="font-bold text-navy-950 text-sm mt-0.5" x-text="activeTask ? (activeTask.task_code + ' - ' + activeTask.name) : ''"></p>
                    <span class="text-[11px] text-slate-500 mt-1 block" x-text="activeTask ? ('Bobot WBS: ' + activeTask.weight_percent + '% | Target Volume: ' + activeTask.planned_volume + ' ' + activeTask.unit) : ''"></span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="form-label required">Akumulasi Progres Fisik (%)</label>
                        <div class="relative">
                            <input type="number" step="0.01" min="0" max="100" name="progress_percent" 
                                   x-bind:value="activeTask ? activeTask.progress_percent : 0" required
                                   class="form-input font-bold text-sm pr-8">
                            <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400">%</span>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Volume Riil Terpasang</label>
                        <input type="number" step="0.01" min="0" name="actual_volume" 
                               x-bind:value="activeTask ? (activeTask.actual_volume || 0) : 0"
                               class="form-input font-bold text-sm" 
                               placeholder="Volume opname">
                    </div>
                </div>

                <div>
                    <label class="form-label">Foto Bukti Lapangan / Inspeksi (Opsional)</label>
                    <input type="file" name="photo" accept="image/*" 
                           class="w-full text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-navy-900 file:text-white hover:file:bg-navy-800 transition-colors cursor-pointer">
                    <span class="text-[10px] text-slate-400 mt-1 block">Format JPG, PNG (Maks 5MB)</span>
                </div>

                <div>
                    <label class="form-label">Catatan Lapangan & Kendala</label>
                    <textarea name="notes" rows="2" placeholder="Catatan cuaca, material, kesiapan tukang, atau instruksi pengawas..." 
                              class="form-textarea text-xs"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="updateModalOpen = false" class="btn-secondary text-xs">Batal</button>
                    <button type="submit" class="btn-accent text-xs inline-flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Simpan Progres Fisik</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
