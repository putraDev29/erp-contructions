@extends('layouts.app')

@section('title', 'Manajemen Gudang & Stok Material')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Gudang & Valuasi Persediaan</h1>
            <p class="text-sm text-slate-500 mt-1">Monitoring stok fisik, harga rata-rata bergerak (Moving Average), mutasi dan pengeluaran ke proyek.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('inventory.issuance') }}" class="btn-primary inline-flex items-center gap-2">
                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                <span>Keluarkan Material ke Proyek</span>
            </a>
            <a href="{{ route('inventory.transfer') }}" class="btn-secondary inline-flex items-center gap-2">
                <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                <span>Transfer Antar Gudang</span>
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Valuasi Stok</span>
                    <h3 class="text-xl font-extrabold text-navy-950 mt-1 font-mono">@rupiah($totalValuation)</h3>
                    <p class="text-xs text-slate-400 mt-1">Metode: Moving Average Cost</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-construction-50 text-construction-600 flex items-center justify-center">
                    <i data-lucide="boxes" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Item Material Terisi</span>
                    <h3 class="text-xl font-extrabold text-navy-950 mt-1">{{ $totalItems }} <span class="text-sm font-normal text-slate-500">item</span></h3>
                    <p class="text-xs text-slate-400 mt-1">Di seluruh lokasi gudang</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Lokasi Gudang</span>
                    <h3 class="text-xl font-extrabold text-navy-950 mt-1">{{ $warehouses->count() }} <span class="text-sm font-normal text-slate-500">gudang</span></h3>
                    <p class="text-xs text-slate-400 mt-1">Gudang utama & site store</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i data-lucide="warehouse" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Stok Kritis (Minimum)</span>
                    <h3 class="text-xl font-extrabold text-rose-600 mt-1">{{ $lowStocks->count() }} <span class="text-sm font-normal text-slate-500">item</span></h3>
                    <p class="text-xs text-rose-400 mt-1">Di bawah batas aman</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card p-4">
        <form method="GET" action="{{ route('inventory.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="form-label text-xs">Pilih Lokasi Gudang</label>
                <select name="warehouse_id" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Lokasi Gudang</option>
                    @foreach($warehouses as $w)
                        <option value="{{ $w->id }}" {{ $selectedWarehouse == $w->id ? 'selected' : '' }}>
                            {{ $w->name }} ({{ $w->stocks_count }} item)
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Cari Nama / Kode Material</label>
                <input type="text" name="search" class="form-input text-sm" placeholder="Ketik nama atau kode..." value="{{ request('search') }}">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary text-sm">Cari</button>
                <a href="{{ route('inventory.index') }}" class="btn-secondary text-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- Inventory Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th">Material & Spesifikasi</th>
                        <th class="table-th">Kategori</th>
                        <th class="table-th">Lokasi Gudang</th>
                        <th class="table-th text-right">Sisa Stok Fisik</th>
                        <th class="table-th text-right">Moving Avg Cost</th>
                        <th class="table-th text-right">Total Nilai Valuasi</th>
                        <th class="table-th text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        @php
                            $isLow = $stock->material && $stock->current_stock <= $stock->material->minimum_stock;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td">
                                <a href="{{ route('inventory.stock-card', $stock->material_id) }}" class="font-bold text-navy-900 hover:text-construction-600 transition-colors">
                                    {{ $stock->material?->name }}
                                </a>
                                <div class="text-xs font-mono text-slate-400">{{ $stock->material?->code }}</div>
                            </td>
                            <td class="table-td">
                                <span class="badge badge-neutral text-xs">{{ $stock->material?->category?->name ?? 'Material Sipil' }}</span>
                            </td>
                            <td class="table-td">
                                <div class="font-semibold text-slate-800 text-xs">{{ $stock->warehouse?->name }}</div>
                            </td>
                            <td class="table-td text-right">
                                <div class="font-bold {{ $isLow ? 'text-rose-600' : 'text-slate-900' }} text-sm font-mono whitespace-nowrap">
                                    {{ number_format($stock->current_stock, 2) }} {{ $stock->material?->unit?->symbol ?? 'pcs' }}
                                </div>
                                @if($isLow)
                                    <span class="inline-block mt-0.5 text-[10px] bg-rose-100 text-rose-700 px-1.5 py-0.5 rounded font-bold">Batas Min: {{ $stock->material?->minimum_stock }}</span>
                                @endif
                            </td>
                            <td class="table-td text-right font-mono text-xs text-slate-600 whitespace-nowrap">
                                @rupiah($stock->moving_avg_cost)
                            </td>
                            <td class="table-td text-right font-mono font-black text-navy-950 whitespace-nowrap">
                                @rupiah($stock->total_value)
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                <a href="{{ route('inventory.stock-card', $stock->material_id) }}" class="btn-secondary btn-xs inline-flex items-center gap-1">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                    <span>Kartu Stok</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <i data-lucide="boxes" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Tidak ada data stok yang cocok dengan kriteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $stocks->links() }}
        </div>
    </div>
</div>
@endsection
