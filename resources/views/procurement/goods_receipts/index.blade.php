@extends('layouts.app')

@section('title', 'Penerimaan Barang (Goods Receipt)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Penerimaan Barang (Goods Receipts)</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar fisik material yang telah sampai di gudang/lapangan, diverifikasi, dan menambah stok persediaan.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card p-4">
        <form method="GET" action="{{ route('goods-receipts.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="form-label text-xs">Pilih Lokasi Gudang Penerima</label>
                <select name="warehouse_id" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Gudang</option>
                    @foreach($warehouses as $w)
                        <option value="{{ $w->id }}" {{ request('warehouse_id') == $w->id ? 'selected' : '' }}>
                            {{ $w->name }} ({{ $w->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <a href="{{ route('goods-receipts.index') }}" class="btn-secondary text-sm">Reset Filter</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. GR</th>
                        <th>No. PO Terkait</th>
                        <th>No. Surat Jalan Vendor</th>
                        <th>Gudang & Proyek</th>
                        <th>Material Diterima & Kuantitas</th>
                        <th>Tgl Penerimaan</th>
                        <th>Penerima</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receipts as $gr)
                        <tr>
                            <td>
                                <div class="font-bold text-navy-900 font-mono">{{ $gr->receipt_number }}</div>
                            </td>
                            <td>
                                @if($gr->purchaseOrder)
                                    <a href="{{ route('purchase-orders.show', $gr->purchaseOrder) }}" class="font-bold text-construction-600 hover:underline font-mono text-xs">
                                        {{ $gr->purchaseOrder->po_number }}
                                    </a>
                                    <div class="text-[11px] text-slate-400">{{ $gr->purchaseOrder->supplier?->name }}</div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="font-mono text-xs text-slate-800 font-semibold bg-slate-100 px-2 py-0.5 rounded">
                                    {{ $gr->delivery_note_number }}
                                </span>
                            </td>
                            <td>
                                <div class="text-xs font-bold text-slate-800">{{ $gr->warehouse?->name }}</div>
                                <div class="text-[11px] text-slate-400 truncate max-w-xs">{{ $gr->purchaseOrder?->project?->name ?? 'Gudang Umum' }}</div>
                            </td>
                            <td>
                                <div class="space-y-1">
                                    @forelse($gr->items as $item)
                                        <div class="text-xs flex items-center justify-between gap-3 bg-slate-50/80 px-2.5 py-1 rounded border border-slate-100">
                                            <span class="font-medium text-slate-800">{{ $item->material?->name }}</span>
                                            <span class="font-mono font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded text-[11px]">
                                                +{{ number_format($item->received_quantity, 2) }} {{ $item->material?->unit?->symbol }}
                                            </span>
                                        </div>
                                    @empty
                                        <span class="text-xs text-slate-400">Item tidak tercatat</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="text-xs whitespace-nowrap">@dateid($gr->receipt_date)</td>
                            <td class="text-xs text-slate-600 font-medium">{{ $gr->receivedByUser?->name ?? 'Petugas Gudang' }}</td>
                            <td class="text-center">
                                <span class="badge-success text-[10px]">Stok Masuk</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <i data-lucide="package-check" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada penerimaan barang yang dicatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $receipts->links() }}
        </div>
    </div>
</div>
@endsection
