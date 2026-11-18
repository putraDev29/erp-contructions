@extends('layouts.app')

@section('title', 'Kartu Stok - ' . $material->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-navy-950">{{ $material->name }}</h1>
                <span class="badge-primary font-mono">{{ $material->code }}</span>
            </div>
            <p class="text-sm text-slate-500 mt-1">Kartu stok dan riwayat mutasi keluar/masuk material di seluruh gudang.</p>
        </div>
        <a href="{{ route('inventory.index') }}" class="btn-secondary text-sm">Kembali ke Gudang</a>
    </div>

    <!-- Material Meta Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kategori</span>
            <div class="font-bold text-navy-950 text-base mt-1">{{ $material->category?->name ?? 'Material Sipil' }}</div>
        </div>
        <div class="card p-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Satuan Unit</span>
            <div class="font-bold text-navy-950 text-base mt-1">{{ $material->unit?->name }} ({{ $material->unit?->symbol }})</div>
        </div>
        <div class="card p-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Harga Beli Terakhir</span>
            <div class="font-bold text-navy-950 text-base mt-1 font-mono">@rupiah($material->last_purchase_price)</div>
        </div>
        <div class="card p-4">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Batas Stok Minimum</span>
            <div class="font-bold text-navy-950 text-base mt-1 font-mono">{{ number_format($material->minimum_stock, 2) }} {{ $material->unit?->symbol }}</div>
        </div>
    </div>

    <!-- Mutations Table -->
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-navy-950">Riwayat Mutasi Fisik & Biaya</h3>
            <span class="text-xs text-slate-400">{{ $mutations->total() }} catatan mutasi</span>
        </div>
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th">No. Transaksi</th>
                        <th class="table-th">Tanggal</th>
                        <th class="table-th">Tipe Mutasi</th>
                        <th class="table-th">Gudang / Proyek Terkait</th>
                        <th class="table-th text-right">Kuantitas</th>
                        <th class="table-th text-right">Harga Satuan (Cost)</th>
                        <th class="table-th text-right">Total Nilai</th>
                        <th class="table-th">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mutations as $item)
                        @php
                            $trx = $item->transaction;
                            $isMasuk = in_array($trx->type, ['receipt', 'transfer_in', 'return']);
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-mono text-xs font-bold text-navy-900">{{ $trx->transaction_number }}</td>
                            <td class="table-td whitespace-nowrap">@dateid($trx->transaction_date)</td>
                            <td class="table-td">
                                @if($trx->type === 'receipt')
                                    <span class="badge badge-success"><i data-lucide="arrow-down-left" class="w-3 h-3"></i> Penerimaan PO</span>
                                @elseif($trx->type === 'issuance_to_project')
                                    <span class="badge badge-primary"><i data-lucide="arrow-up-right" class="w-3 h-3"></i> Pemakaian Proyek</span>
                                @elseif($trx->type === 'transfer_out')
                                    <span class="badge badge-warning"><i data-lucide="arrow-right" class="w-3 h-3"></i> Transfer Keluar</span>
                                @else
                                    <span class="badge badge-neutral">{{ ucfirst($trx->type) }}</span>
                                @endif
                            </td>
                            <td class="table-td">
                                <div class="font-semibold text-xs text-slate-900">{{ $trx->warehouse?->name }}</div>
                                @if($trx->project)
                                    <div class="text-[11px] text-construction-600 font-mono">{{ $trx->project->name }}</div>
                                @endif
                            </td>
                            <td class="table-td text-right font-mono font-bold whitespace-nowrap {{ $isMasuk ? 'text-emerald-600' : 'text-slate-800' }}">
                                {{ $isMasuk ? '+' : '-' }}{{ number_format($item->qty, 2) }} {{ $item->unit }}
                            </td>
                            <td class="table-td text-right font-mono text-xs whitespace-nowrap">@rupiah($item->unit_cost)</td>
                            <td class="table-td text-right font-mono font-black text-navy-950 whitespace-nowrap">@rupiah($item->total_cost)</td>
                            <td class="table-td text-xs text-slate-500 max-w-xs truncate">{{ $item->notes ?? $trx->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <i data-lucide="file-text" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada riwayat mutasi untuk material ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $mutations->links() }}
        </div>
    </div>
</div>
@endsection
