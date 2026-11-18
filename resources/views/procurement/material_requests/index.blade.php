@extends('layouts.app')

@section('title', 'Permintaan Material (MR)')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Permintaan Material (Material Requests)</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar permintaan pengadaan material dari lapangan / site manager untuk proyek.</p>
        </div>
        <a href="{{ route('material-requests.create') }}" class="btn-primary inline-flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Buat Permintaan Baru</span>
        </a>
    </div>

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5 border-l-4 border-navy-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Permintaan</p>
                    <p class="text-2xl font-black text-navy-950 mt-1">{{ $requests->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-navy-50 text-navy-700 flex items-center justify-center">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Menunggu Approval</p>
                    <p class="text-2xl font-black text-amber-900 mt-1">{{ \App\Models\MaterialRequest::where('status', 'pending_approval')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Disetujui (Siap PO)</p>
                    <p class="text-2xl font-black text-emerald-900 mt-1">{{ \App\Models\MaterialRequest::where('status', 'approved')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-sky-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-sky-700">PO Diterbitkan</p>
                    <p class="text-2xl font-black text-sky-900 mt-1">{{ \App\Models\MaterialRequest::where('status', 'ordered')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card p-4">
        <form method="GET" action="{{ route('material-requests.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                <label class="form-label text-xs">Status Permintaan</label>
                <select name="status" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>PO Diterbitkan</option>
                </select>
            </div>
            <div class="flex items-end">
                <a href="{{ route('material-requests.index') }}" class="btn-secondary text-sm">Reset Filter</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th">No. Request</th>
                        <th class="table-th">Proyek & Catatan</th>
                        <th class="table-th">Item Material Diminta</th>
                        <th class="table-th">Tgl Kebutuhan</th>
                        <th class="table-th">Diajukan Oleh</th>
                        <th class="table-th">Status</th>
                        <th class="table-th">Disetujui Oleh</th>
                        <th class="table-th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $mr)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-bold text-navy-900 font-mono">{{ $mr->request_number }}</td>
                            <td class="table-td">
                                <div class="font-semibold text-slate-900">{{ $mr->project?->name ?? 'Proyek Tidak Ditemukan' }}</div>
                                <div class="text-xs text-slate-400 font-mono">{{ $mr->project?->code }}</div>
                                @if($mr->notes)
                                    <div class="text-xs text-slate-500 italic mt-1 bg-slate-50 p-1.5 rounded border border-slate-100">{{ $mr->notes }}</div>
                                @endif
                            </td>
                            <td class="table-td">
                                <div class="space-y-1 max-w-xs">
                                    @forelse($mr->items as $item)
                                        <div class="text-xs flex items-center justify-between gap-2 bg-slate-50 px-2 py-1 rounded border border-slate-200/60">
                                            <span class="font-medium text-slate-800 truncate">{{ $item->material?->name ?? 'Material' }}</span>
                                            <span class="font-bold text-navy-900 whitespace-nowrap">{{ number_format($item->requested_qty, 2) }} {{ $item->material?->unit?->symbol ?? '' }}</span>
                                        </div>
                                    @empty
                                        <span class="text-xs text-slate-400 italic">Tidak ada rincian item</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="table-td font-medium text-slate-700 whitespace-nowrap">@dateid($mr->required_date)</td>
                            <td class="table-td">
                                <div class="font-medium text-slate-800">{{ $mr->requestedByUser?->name ?? '-' }}</div>
                            </td>
                            <td class="table-td">
                                @if($mr->status === 'approved')
                                    <span class="badge badge-success"><i data-lucide="check" class="w-3 h-3"></i> Disetujui</span>
                                @elseif($mr->status === 'pending_approval')
                                    <span class="badge badge-warning"><i data-lucide="clock" class="w-3 h-3"></i> Menunggu Approval</span>
                                @elseif($mr->status === 'ordered')
                                    <span class="badge badge-primary"><i data-lucide="shopping-cart" class="w-3 h-3"></i> PO Diterbitkan</span>
                                @elseif($mr->status === 'rejected')
                                    <span class="badge badge-danger"><i data-lucide="x" class="w-3 h-3"></i> Ditolak</span>
                                @else
                                    <span class="badge badge-neutral">{{ ucfirst($mr->status) }}</span>
                                @endif
                            </td>
                            <td class="table-td">
                                @if($mr->approvedByUser)
                                    <span class="text-xs font-medium text-slate-800">{{ $mr->approvedByUser->name }}</span>
                                    <div class="text-[11px] text-slate-400">@dateid($mr->approved_at)</div>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="table-td text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($mr->status === 'pending_approval')
                                        <form method="POST" action="{{ route('material-requests.approve', $mr) }}" onsubmit="return confirm('Setujui permintaan material ini?')">
                                            @csrf
                                            <button type="submit" class="btn-accent btn-xs" title="Setujui Permintaan">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                <span>Approve</span>
                                            </button>
                                        </form>
                                    @endif
                                    @if($mr->status === 'approved')
                                        <a href="{{ route('purchase-orders.create', ['project_id' => $mr->project_id]) }}" class="btn-primary btn-xs" title="Terbitkan Purchase Order">
                                            <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                                            <span>Buat PO</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <i data-lucide="clipboard-list" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada pengajuan permintaan material.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
