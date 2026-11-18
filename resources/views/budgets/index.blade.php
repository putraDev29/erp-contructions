@extends('layouts.app')

@section('title', 'Daftar Rencana Anggaran Biaya (RAB)')
@section('page_title', 'Rencana Anggaran Biaya (RAB)')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-navy-950">Rencana Anggaran Biaya (RAB)</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pengelolaan estimasi biaya konstruksi, struktur divisi pekerjaan, markup & margin penawaran.</p>
        </div>
        <a href="{{ route('budgets.create') }}" class="btn-primary self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Buat Estimasi / RAB Baru</span>
        </a>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5 border-l-4 border-navy-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Dokumen RAB</p>
                    <p class="text-2xl font-black text-navy-950 mt-1">{{ $budgets->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-navy-50 text-navy-700 flex items-center justify-center">
                    <i data-lucide="calculator" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">RAB Disetujui (Approved)</p>
                    <p class="text-2xl font-black text-emerald-900 mt-1">{{ \App\Models\Budget::where('status', 'approved')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-construction-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-construction-700">Total Anggaran Disetujui</p>
                    <p class="text-xl font-black text-construction-950 mt-1 font-mono">
                        @rupiah(\App\Models\Budget::where('status', 'approved')->sum('total_rab_amount'))
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-construction-50 text-construction-600 flex items-center justify-center">
                    <i data-lucide="badge-dollar-sign" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Dalam Proses / Draf</p>
                    <p class="text-2xl font-black text-amber-900 mt-1">{{ \App\Models\Budget::whereIn('status', ['draft', 'submitted', 'revised'])->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-4">
        <form method="GET" action="{{ route('budgets.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="form-label text-xs">Pilih Proyek</label>
                <select name="project_id" onchange="this.form.submit()" class="form-select text-sm">
                    <option value="">Semua Proyek</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->code }} - {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label text-xs">Status Dokumen RAB</label>
                <select name="status" onchange="this.form.submit()" class="form-select text-sm">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Diajukan (Submitted)</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                    <option value="revised" {{ request('status') === 'revised' ? 'selected' : '' }}>Revisi Baru (Revised)</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                </select>
            </div>

            <div class="flex items-end">
                <a href="{{ route('budgets.index') }}" class="btn-secondary text-sm">Reset Filter</a>
            </div>
        </form>
    </div>

    <!-- Budgets Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr>
                        <th class="table-th">No. RAB / Versi</th>
                        <th class="table-th">Proyek Konstruksi</th>
                        <th class="table-th">Judul Dokumen RAB</th>
                        <th class="table-th text-right">Biaya Langsung</th>
                        <th class="table-th text-right">Total Anggaran</th>
                        <th class="table-th text-right">Harga Penawaran</th>
                        <th class="table-th text-center">Status</th>
                        <th class="table-th text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($budgets as $b)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td whitespace-nowrap">
                                <a href="{{ route('budgets.show', $b) }}" class="font-bold text-navy-900 hover:text-construction-600 block">
                                    {{ $b->rab_number }}
                                </a>
                                <span class="badge badge-neutral text-[10px] mt-0.5">Versi {{ $b->version }}</span>
                            </td>
                            <td class="table-td">
                                <span class="font-semibold text-slate-800 block truncate max-w-xs">{{ $b->project?->name }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ $b->project?->project_code }}</span>
                            </td>
                            <td class="table-td max-w-xs font-medium text-slate-700 truncate">
                                {{ $b->title }}
                            </td>
                            <td class="table-td text-right font-medium text-slate-700 whitespace-nowrap">
                                @rupiah($b->direct_cost)
                            </td>
                            <td class="table-td text-right font-extrabold text-navy-950 whitespace-nowrap">
                                @rupiah($b->total_rab_amount)
                            </td>
                            <td class="table-td text-right font-bold text-construction-700 whitespace-nowrap">
                                @rupiah($b->offered_price)
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                @php
                                    $stBadge = match($b->status) {
                                        'approved' => 'badge-success',
                                        'revised' => 'badge-info',
                                        'rejected' => 'badge-danger',
                                        'submitted' => 'badge-warning',
                                        default => 'badge-neutral',
                                    };
                                @endphp
                                <span class="badge {{ $stBadge }}">
                                    {{ ucfirst($b->status) }}
                                </span>
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('budgets.show', $b) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-navy-900 hover:bg-slate-100 transition-colors" title="Buka Rincian RAB">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('budgets.export-pdf', $b) }}" class="p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition-colors" title="Download PDF RAB">
                                        <i data-lucide="file-down" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
                                Belum ada dokumen RAB yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($budgets->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $budgets->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
