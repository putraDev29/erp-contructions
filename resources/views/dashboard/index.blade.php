@extends('layouts.app')

@section('title', 'Dashboard Eksekutif')
@section('page_title', 'Dashboard Eksekutif')

@section('content')
<div class="space-y-6">

    <!-- Header & Filter Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-navy-950 tracking-tight flex items-center gap-2">
                <span>Dashboard Eksekutif Kontraktor</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-navy-100 text-navy-800">
                    Live Database
                </span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Monitoring komprehensif seluruh siklus proyek, finansial, material, dan akuntansi secara real-time.
            </p>
        </div>

        <!-- Filter Controls -->
        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-2.5">
            <div>
                <select name="project_id" onchange="this.form.submit()" 
                        class="bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl focus:ring-2 focus:ring-navy-500 py-2 px-3">
                    <option value="">-- Konsolidasi Seluruh Proyek --</option>
                    @foreach($allProjects as $proj)
                        <option value="{{ $proj->id }}" {{ $selectedProjectId == $proj->id ? 'selected' : '' }}>
                            {{ $proj->project_code }} - {{ $proj->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="year" onchange="this.form.submit()" 
                        class="bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl focus:ring-2 focus:ring-navy-500 py-2 px-3">
                    @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
            </div>

            @if($selectedProjectId)
                <a href="{{ route('dashboard') }}" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors" title="Reset Filter">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Top 4 Primary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Card 1: Nilai Kontrak Aktif -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden group hover:border-navy-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Nilai Kontrak</span>
                <div class="w-9 h-9 rounded-xl bg-navy-50 text-navy-800 flex items-center justify-center">
                    <i data-lucide="folder-check" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-xl sm:text-2xl font-black text-navy-950 block tracking-tight">
                    @rupiah($totalContractValue)
                </span>
                <div class="flex items-center gap-2 mt-2 text-xs text-slate-500 font-medium">
                    <span class="badge badge-primary">{{ $activeProjectsCount }} Proyek Berjalan</span>
                    @if($delayedProjectsCount > 0)
                        <span class="badge badge-danger">{{ $delayedProjectsCount }} Terlambat</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card 2: Pendapatan vs Pengeluaran Aktual -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden group hover:border-emerald-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pendapatan Diakui</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-xl sm:text-2xl font-black text-emerald-700 block tracking-tight">
                    @rupiah($recognizedRevenue)
                </span>
                <div class="mt-2 text-xs text-slate-500 font-medium flex items-center justify-between">
                    <span>Biaya Aktual:</span>
                    <span class="font-bold text-slate-700">@rupiah($totalActualCost)</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Saldo Kas & Bank -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden group hover:border-construction-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Saldo Kas & Bank</span>
                <div class="w-9 h-9 rounded-xl bg-construction-50 text-construction-700 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-xl sm:text-2xl font-black text-navy-950 block tracking-tight">
                    @rupiah($totalCashBankBalance)
                </span>
                <div class="mt-2 text-xs text-slate-500 font-medium flex items-center justify-between">
                    <span>Piutang AR:</span>
                    <span class="font-bold text-sky-700">@rupiah($totalCustomerReceivables)</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Laba Bersih Proyek & Margin -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden group hover:border-navy-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Laba Kotor Proyek</span>
                <div class="w-9 h-9 rounded-xl {{ $projectGrossProfit >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }} flex items-center justify-center">
                    <i data-lucide="{{ $projectGrossProfit >= 0 ? 'badge-percent' : 'trending-down' }}" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-xl sm:text-2xl font-black {{ $projectGrossProfit >= 0 ? 'text-navy-950' : 'text-rose-600' }} block tracking-tight">
                    @rupiah($projectGrossProfit)
                </span>
                <div class="mt-2 text-xs font-semibold flex items-center justify-between">
                    <span class="text-slate-500">Margin Proyek:</span>
                    <span class="{{ $profitMarginPercent >= 15 ? 'text-emerald-600' : ($profitMarginPercent > 0 ? 'text-amber-600' : 'text-rose-600') }}">
                        {{ $profitMarginPercent }}%
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- Secondary Indicator Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 text-xs">
        <div class="bg-white p-3.5 rounded-xl border border-slate-200">
            <span class="text-slate-500 font-medium block">Total RAB Disetujui</span>
            <span class="font-bold text-slate-800 text-sm mt-0.5 block">@rupiah($totalApprovedBudget)</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200">
            <span class="text-slate-500 font-medium block">Hutang Supplier (AP)</span>
            <span class="font-bold text-rose-600 text-sm mt-0.5 block">@rupiah($totalSupplierPayables)</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200">
            <span class="text-slate-500 font-medium block">Biaya Material Aktual</span>
            <span class="font-bold text-slate-800 text-sm mt-0.5 block">@rupiah($actualMaterialCost)</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200">
            <span class="text-slate-500 font-medium block">Biaya Upah & Tenaga Kerja</span>
            <span class="font-bold text-slate-800 text-sm mt-0.5 block">@rupiah($actualLaborCost)</span>
        </div>
    </div>

    <!-- Charts Row 1: Pendapatan & Pengeluaran Bulanan & Komposisi Biaya -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Line/Bar Chart: Monthly Financials -->
        <div class="lg:col-span-2 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-bold text-navy-950 uppercase tracking-wider">Arus Pendapatan & Pengeluaran Bulanan</h2>
                    <p class="text-xs text-slate-500">Perbandingan pendapatan diakui dan beban aktual (Tahun {{ $selectedYear }})</p>
                </div>
                <span class="badge badge-primary">IDR Juta</span>
            </div>
            <div class="h-72 w-full">
                <canvas id="monthlyFinancialsChart"></canvas>
            </div>
        </div>

        <!-- Donut Chart: Project Cost Composition -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col">
            <div class="mb-4">
                <h2 class="text-sm font-bold text-navy-950 uppercase tracking-wider">Komposisi Biaya Proyek</h2>
                <p class="text-xs text-slate-500">Proporsi realisasi biaya berdasarkan kategori</p>
            </div>
            <div class="h-60 w-full relative flex-1 flex items-center justify-center">
                <canvas id="costCompositionChart"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs pt-3 border-t border-slate-100 mt-2">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-navy-800 inline-block"></span>
                    <span class="text-slate-600 truncate">Material</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-construction-500 inline-block"></span>
                    <span class="text-slate-600 truncate">Upah Pekerja</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-sky-500 inline-block"></span>
                    <span class="text-slate-600 truncate">Subkontraktor</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                    <span class="text-slate-600 truncate">Alat & Lainnya</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Rankings & Highlight Highlights -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Top Absorption -->
        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Penyerapan Anggaran Tertinggi</span>
            @if($topAbsorptionProject)
                <p class="font-bold text-slate-800 text-sm mt-1 truncate">{{ $topAbsorptionProject->name }}</p>
                <div class="flex items-center justify-between text-xs text-slate-500 mt-1">
                    <span>Penyerapan:</span>
                    <span class="font-bold text-construction-600">{{ $topAbsorptionProject->budget_absorption_percent }}%</span>
                </div>
            @else
                <p class="text-xs text-slate-400 mt-1">Belum ada data penyerapan</p>
            @endif
        </div>

        <!-- Top Margin -->
        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Proyek Margin Tertinggi</span>
            @if($topMarginProject)
                <p class="font-bold text-slate-800 text-sm mt-1 truncate">{{ $topMarginProject->name }}</p>
                <div class="flex items-center justify-between text-xs text-slate-500 mt-1">
                    <span>Margin Laba:</span>
                    <span class="font-bold text-emerald-600">{{ $topMarginProject->profit_margin_percent }}%</span>
                </div>
            @else
                <p class="text-xs text-slate-400 mt-1">Belum ada data laba</p>
            @endif
        </div>

        <!-- Stock Alert Warning -->
        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Peringatan Stok Minimum</span>
            <div class="flex items-center justify-between mt-1">
                <span class="text-xs text-slate-600">Material Kritis:</span>
                <span class="badge {{ $lowStockMaterials->count() > 0 ? 'badge-danger' : 'badge-success' }}">
                    {{ $lowStockMaterials->count() }} Item
                </span>
            </div>
            <a href="{{ route('materials.index') }}" class="text-xs text-navy-800 hover:text-navy-950 font-semibold block mt-1">
                Lihat detail gudang &rarr;
            </a>
        </div>
    </div>

    <!-- Operational Alerts & Actionable Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Table 1: Tagihan Pelanggan Jatuh Tempo -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-navy-950 uppercase tracking-wider">Tagihan Pelanggan Mendekati Jatuh Tempo</h3>
                    <p class="text-xs text-slate-500">Invoice termin yang perlu ditagih dalam 14 hari ke depan</p>
                </div>
                <a href="{{ route('customer-invoices.index') }}" class="text-xs font-semibold text-construction-600 hover:text-construction-700">
                    Lihat Semua
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr>
                            <th class="table-th">Invoice</th>
                            <th class="table-th">Pelanggan / Proyek</th>
                            <th class="table-th">Jatuh Tempo</th>
                            <th class="table-th text-right">Sisa Piutang</th>
                            <th class="table-th text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($dueCustomerInvoices as $inv)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="table-td font-bold text-navy-900">{{ $inv->invoice_number }}</td>
                                <td class="table-td">
                                    <span class="font-semibold text-slate-800 block truncate max-w-[150px]">{{ $inv->customer?->name }}</span>
                                    <span class="text-[10px] text-slate-400 block truncate max-w-[150px]">{{ $inv->project?->name }}</span>
                                </td>
                                <td class="table-td whitespace-nowrap">
                                    @dateid($inv->due_date)
                                </td>
                                <td class="table-td text-right font-bold text-slate-900">
                                    @rupiah($inv->remaining_amount)
                                </td>
                                <td class="table-td text-center">
                                    <span class="badge {{ $inv->status === 'overdue' ? 'badge-danger' : 'badge-warning' }}">
                                        {{ ucfirst($inv->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-slate-400">
                                    Tidak ada tagihan pelanggan yang mendekati jatuh tempo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table 2: Hutang Supplier Jatuh Tempo -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-navy-950 uppercase tracking-wider">Hutang Supplier Akan Jatuh Tempo</h3>
                    <p class="text-xs text-slate-500">Kewajiban pembayaran material & subkon yang harus dipersiapkan</p>
                </div>
                <a href="{{ route('supplier-invoices.index') }}" class="text-xs font-semibold text-construction-600 hover:text-construction-700">
                    Lihat Semua
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr>
                            <th class="table-th">No. Tagihan</th>
                            <th class="table-th">Supplier</th>
                            <th class="table-th">Jatuh Tempo</th>
                            <th class="table-th text-right">Sisa Hutang</th>
                            <th class="table-th text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($dueSupplierInvoices as $supInv)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="table-td font-bold text-navy-900">{{ $supInv->invoice_number }}</td>
                                <td class="table-td font-semibold text-slate-800 truncate max-w-[150px]">
                                    {{ $supInv->supplier?->name }}
                                </td>
                                <td class="table-td whitespace-nowrap">
                                    @dateid($supInv->due_date)
                                </td>
                                <td class="table-td text-right font-bold text-rose-600">
                                    @rupiah($supInv->remaining_amount)
                                </td>
                                <td class="table-td text-center">
                                    <span class="badge badge-warning">
                                        {{ ucfirst($supInv->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-slate-400">
                                    Tidak ada hutang supplier yang mendesak.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Monthly Financials Chart (Line / Bar)
    const monthlyCtx = document.getElementById('monthlyFinancialsChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Pendapatan Konstruksi',
                        data: {!! json_encode(array_map(fn($v) => round($v / 1000000, 2), $monthlyRevenue)) !!},
                        backgroundColor: '#0f1f33',
                        borderRadius: 6,
                    },
                    {
                        label: 'Biaya Proyek Aktual',
                        data: {!! json_encode(array_map(fn($v) => round($v / 1000000, 2), $monthlyExpense)) !!},
                        backgroundColor: '#ea580c',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID') + ' Juta';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value + ' Jt';
                            },
                            font: { size: 10 }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    }

    // 2. Cost Composition Donut Chart
    const costCtx = document.getElementById('costCompositionChart');
    if (costCtx) {
        const materialCost = {{ $costComposition['material'] }};
        const laborCost = {{ $costComposition['labor'] }};
        const subconCost = {{ $costComposition['subcon'] }};
        const equipCost = {{ $costComposition['equipment'] }};
        const total = materialCost + laborCost + subconCost + equipCost;

        new Chart(costCtx, {
            type: 'doughnut',
            data: {
                labels: ['Material', 'Upah Pekerja', 'Subkontraktor', 'Alat & Lainnya'],
                datasets: [{
                    data: total > 0 ? [materialCost, laborCost, subconCost, equipCost] : [1, 1, 1, 1],
                    backgroundColor: ['#0f1f33', '#f97316', '#0ea5e9', '#10b981'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + Number(context.raw).toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
