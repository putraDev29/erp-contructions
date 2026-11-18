@extends('layouts.app')

@section('title', 'Purchase Orders (PO)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Purchase Orders (PO)</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar pesanan pembelian material konstruksi ke supplier resmi.</p>
        </div>
        <a href="{{ route('purchase-orders.create') }}" class="btn-primary inline-flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Buat PO Baru</span>
        </a>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5 border-l-4 border-navy-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Purchase Order</p>
                    <p class="text-2xl font-black text-navy-950 mt-1">{{ $purchaseOrders->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-navy-50 text-navy-700 flex items-center justify-center">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-construction-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-construction-700">Total Komitmen PO</p>
                    <p class="text-xl font-black text-construction-950 mt-1 font-mono">@rupiah(\App\Models\PurchaseOrder::sum('total_amount'))</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-construction-50 text-construction-600 flex items-center justify-center">
                    <i data-lucide="badge-dollar-sign" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Menunggu Approval</p>
                    <p class="text-2xl font-black text-amber-900 mt-1">{{ \App\Models\PurchaseOrder::where('status', 'pending_approval')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Lengkap Diterima</p>
                    <p class="text-2xl font-black text-emerald-900 mt-1">{{ \App\Models\PurchaseOrder::where('status', 'received_full')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card p-4">
        <form method="GET" action="{{ route('purchase-orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label text-xs">Pilih Proyek</label>
                <select name="project_id" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Proyek</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->code }} - {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Supplier</label>
                <select name="supplier_id" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Status</label>
                <select name="status" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="received_partial" {{ request('status') == 'received_partial' ? 'selected' : '' }}>Penerimaan Parsial</option>
                    <option value="received_full" {{ request('status') == 'received_full' ? 'selected' : '' }}>Penerimaan Lengkap</option>
                    <option value="billed" {{ request('status') == 'billed' ? 'selected' : '' }}>Faktur Diterbitkan</option>
                </select>
            </div>
            <div class="flex items-end">
                <a href="{{ route('purchase-orders.index') }}" class="btn-secondary text-sm">Reset Filter</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th">No. PO</th>
                        <th class="table-th">Supplier</th>
                        <th class="table-th">Proyek</th>
                        <th class="table-th">Tgl PO</th>
                        <th class="table-th text-right">Total Nilai</th>
                        <th class="table-th">Status</th>
                        <th class="table-th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td">
                                <a href="{{ route('purchase-orders.show', $po) }}" class="font-bold text-navy-900 hover:text-construction-600 transition-colors font-mono">
                                    {{ $po->po_number }}
                                </a>
                                <div class="text-[11px] text-slate-500 font-sans">Termin: {{ $po->payment_terms }}</div>
                            </td>
                            <td class="table-td">
                                <div class="font-semibold text-slate-900">{{ $po->supplier?->name }}</div>
                                <div class="text-xs text-slate-400">{{ $po->supplier?->phone ?? '-' }}</div>
                            </td>
                            <td class="table-td">
                                <div class="font-medium text-slate-800">{{ $po->project?->name }}</div>
                                <div class="text-xs text-slate-400 font-mono">{{ $po->project?->code }}</div>
                            </td>
                            <td class="table-td font-medium text-slate-700 whitespace-nowrap">@dateid($po->po_date)</td>
                            <td class="table-td text-right font-black text-navy-950 font-mono whitespace-nowrap">
                                @rupiah($po->total_amount)
                            </td>
                            <td class="table-td">
                                @if($po->status === 'approved')
                                    <span class="badge badge-success"><i data-lucide="check" class="w-3 h-3"></i> Approved</span>
                                @elseif($po->status === 'pending_approval')
                                    <span class="badge badge-warning"><i data-lucide="clock" class="w-3 h-3"></i> Pending</span>
                                @elseif($po->status === 'received_partial')
                                    <span class="badge badge-primary"><i data-lucide="package" class="w-3 h-3"></i> Sebagian</span>
                                @elseif($po->status === 'received_full')
                                    <span class="badge badge-success"><i data-lucide="package-check" class="w-3 h-3"></i> Lengkap</span>
                                @elseif($po->status === 'billed')
                                    <span class="badge badge-info"><i data-lucide="file-text" class="w-3 h-3"></i> Tagihan</span>
                                @else
                                    <span class="badge badge-neutral">{{ ucfirst($po->status) }}</span>
                                @endif
                            </td>
                            <td class="table-td text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('purchase-orders.show', $po) }}" class="btn-secondary btn-xs" title="Lihat Rincian">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <span>Detail</span>
                                    </a>
                                    <a href="{{ route('purchase-orders.pdf', $po) }}" target="_blank" class="btn-secondary btn-xs text-slate-600 hover:text-navy-900" title="Cetak PDF">
                                        <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <i data-lucide="shopping-cart" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada Purchase Order yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $purchaseOrders->links() }}
        </div>
    </div>
</div>
@endsection
