@extends('layouts.app')

@section('title', 'PO ' . $purchaseOrder->po_number)

@section('content')
<div class="space-y-6" x-data="{ openGrModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-navy-950">{{ $purchaseOrder->po_number }}</h1>
                @if($purchaseOrder->status === 'approved')
                    <span class="badge-success">Approved</span>
                @elseif($purchaseOrder->status === 'pending_approval')
                    <span class="badge-warning">Menunggu Approval</span>
                @elseif($purchaseOrder->status === 'received_partial')
                    <span class="badge-primary">Penerimaan Parsial</span>
                @elseif($purchaseOrder->status === 'received_full')
                    <span class="badge-success">Lengkap Diterima</span>
                @else
                    <span class="badge-neutral">{{ ucfirst($purchaseOrder->status) }}</span>
                @endif
            </div>
            <p class="text-sm text-slate-500 mt-1">Diterbitkan pada @dateid($purchaseOrder->po_date) untuk {{ $purchaseOrder->supplier?->name }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if($purchaseOrder->status === 'pending_approval')
                <form method="POST" action="{{ route('purchase-orders.approve', $purchaseOrder) }}" onsubmit="return confirm('Setujui Purchase Order ini?')">
                    @csrf
                    <button type="submit" class="btn-primary bg-emerald-600 hover:bg-emerald-700">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        <span>Setujui PO</span>
                    </button>
                </form>
            @endif

            @if(in_array($purchaseOrder->status, ['approved', 'received_partial']))
                <button type="button" @click="openGrModal = true" class="btn-primary inline-flex items-center gap-2">
                    <i data-lucide="package-check" class="w-4 h-4"></i>
                    <span>Terima Barang (GR)</span>
                </button>
            @endif

            <a href="{{ route('purchase-orders.pdf', $purchaseOrder) }}" target="_blank" class="btn-secondary inline-flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Cetak PDF</span>
            </a>
            <a href="{{ route('purchase-orders.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </div>

    <!-- Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card p-5 space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Informasi Proyek</h3>
            <div>
                <div class="font-bold text-navy-950 text-base">{{ $purchaseOrder->project?->name }}</div>
                <div class="text-xs font-mono text-slate-500 mt-0.5">Kode: {{ $purchaseOrder->project?->code }}</div>
                <div class="text-xs text-slate-600 mt-2">{{ $purchaseOrder->project?->address }}</div>
            </div>
        </div>

        <div class="card p-5 space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Supplier & Pengiriman</h3>
            <div>
                <div class="font-bold text-navy-950 text-base">{{ $purchaseOrder->supplier?->name }}</div>
                <div class="text-xs text-slate-600 mt-0.5">{{ $purchaseOrder->supplier?->contact_person }} ({{ $purchaseOrder->supplier?->phone }})</div>
                <div class="text-xs text-slate-500 mt-1">{{ $purchaseOrder->supplier?->address }}</div>
                <div class="text-xs font-medium text-navy-800 mt-2 bg-slate-100 px-2 py-1 rounded inline-block">
                    Termin: {{ $purchaseOrder->payment_terms }}
                </div>
            </div>
        </div>

        <div class="card p-5 space-y-3 bg-gradient-to-br from-navy-950 to-navy-900 text-white">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Ringkasan Nilai PO</h3>
            <div class="space-y-1.5 text-xs">
                <div class="flex justify-between text-slate-300">
                    <span>Subtotal:</span>
                    <span class="font-mono">@rupiah($purchaseOrder->subtotal)</span>
                </div>
                <div class="flex justify-between text-slate-300">
                    <span>PPN ({{ $purchaseOrder->tax_percent }}%):</span>
                    <span class="font-mono">@rupiah($purchaseOrder->tax_amount)</span>
                </div>
                <div class="flex justify-between text-slate-300">
                    <span>Ongkir:</span>
                    <span class="font-mono">@rupiah($purchaseOrder->shipping_fee)</span>
                </div>
                <div class="border-t border-navy-800 pt-2 flex justify-between font-bold text-base text-white">
                    <span>Total PO:</span>
                    <span class="font-mono text-construction-400">@rupiah($purchaseOrder->total_amount)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-navy-950">Item Pembelian Material</h3>
            <span class="text-xs text-slate-500">{{ $purchaseOrder->items->count() }} macam material</span>
        </div>
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th">Material</th>
                        <th class="table-th">Kategori</th>
                        <th class="table-th text-right">Dipesan</th>
                        <th class="table-th text-right">Sudah Diterima</th>
                        <th class="table-th text-right">Harga Satuan</th>
                        <th class="table-th text-right">Total Harga</th>
                        <th class="table-th text-center">Status Item</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($purchaseOrder->items as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td">
                                <div class="font-bold text-navy-900">{{ $item->material?->name }}</div>
                                <div class="text-xs font-mono text-slate-400">{{ $item->material?->code }}</div>
                            </td>
                            <td class="table-td">{{ $item->material?->category?->name ?? 'Umum' }}</td>
                            <td class="table-td text-right font-medium">{{ number_format($item->qty, 2) }} {{ $item->unit }}</td>
                            <td class="table-td text-right font-medium {{ $item->received_qty >= $item->qty ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ number_format($item->received_qty, 2) }} {{ $item->unit }}
                            </td>
                            <td class="table-td text-right font-mono text-xs">@rupiah($item->unit_price)</td>
                            <td class="table-td text-right font-mono font-bold text-navy-950">@rupiah($item->total_price)</td>
                            <td class="table-td text-center">
                                @if($item->received_qty >= $item->qty)
                                    <span class="badge badge-success text-[10px]">Lengkap</span>
                                @elseif($item->received_qty > 0)
                                    <span class="badge badge-primary text-[10px]">Parsial</span>
                                @else
                                    <span class="badge badge-neutral text-[10px]">Menunggu Kirim</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Goods Receipt History -->
    @if($purchaseOrder->goodsReceipts->count() > 0)
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-navy-950">Riwayat Surat Jalan / Penerimaan Barang</h3>
            </div>
            <div class="table-container">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="table-th">No. Penerimaan (GR)</th>
                            <th class="table-th">No. Surat Jalan Vendor</th>
                            <th class="table-th">Gudang Penerima</th>
                            <th class="table-th">Tanggal Diterima</th>
                            <th class="table-th">Diterima Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($purchaseOrder->goodsReceipts as $gr)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="table-td font-bold text-navy-900 font-mono">{{ $gr->receipt_number }}</td>
                                <td class="table-td font-mono text-xs">{{ $gr->delivery_note_number }}</td>
                                <td class="table-td">{{ $gr->warehouse?->name }}</td>
                                <td class="table-td">@dateid($gr->receipt_date)</td>
                                <td class="table-td">{{ $gr->receivedByUser?->name ?? 'Staff Gudang' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- MODAL PENERIMAAN BARANG (GOODS RECEIPT) -->
    <div x-show="openGrModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6 space-y-6" @click.away="openGrModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Catat Penerimaan Barang (Goods Receipt)</h3>
                    <p class="text-xs text-slate-500">Stok material gudang akan otomatis bertambah dan jurnal akuntansi di-posting.</p>
                </div>
                <button type="button" @click="openGrModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('goods-receipts.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Gudang Penyimpanan</label>
                        <select name="warehouse_id" class="form-select text-sm" required>
                            @foreach($warehouses as $w)
                                <option value="{{ $w->id }}">{{ $w->name }} ({{ $w->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label required">Tanggal Diterima Lapangan</label>
                        <input type="date" name="receipt_date" class="form-input text-sm" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label required">Nomor Surat Jalan (Vendor)</label>
                        <input type="text" name="delivery_note_number" class="form-input text-sm" placeholder="Contoh: SJ-2026/089" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Catatan Pemeriksaan</label>
                        <textarea name="notes" rows="2" class="form-input text-sm" placeholder="Kondisi material baik, kemasan utuh"></textarea>
                    </div>
                </div>

                <!-- Receipt Items Input -->
                <div class="border-t border-slate-100 pt-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Item yang Diterima</h4>
                    <div class="space-y-3">
                        @foreach($purchaseOrder->items as $idx => $item)
                            @php
                                $sisa = max(0, $item->qty - $item->received_qty);
                            @endphp
                            <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                <div>
                                    <div class="font-bold text-navy-950">{{ $item->material?->name }}</div>
                                    <div class="text-slate-500">Dipesan: {{ $item->qty }} {{ $item->unit }} | Sisa: {{ $sisa }} {{ $item->unit }}</div>
                                    <input type="hidden" name="items[{{ $idx }}][po_item_id]" value="{{ $item->id }}">
                                </div>
                                <div class="flex items-center gap-2">
                                    <div>
                                        <label class="block text-[10px] text-slate-500 mb-1">Qty Diterima (Lolos)</label>
                                        <input type="number" step="0.01" min="0" max="{{ $sisa }}" name="items[{{ $idx }}][received_qty]" value="{{ $sisa }}" class="form-input text-xs w-28 text-right font-bold" required>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-500 mb-1">Qty Rusak / Tolak</label>
                                        <input type="number" step="0.01" min="0" name="items[{{ $idx }}][rejected_qty]" value="0" class="form-input text-xs w-24 text-right">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openGrModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Simpan & Tambah Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
