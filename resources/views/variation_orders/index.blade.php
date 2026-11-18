@extends('layouts.app')

@section('title', 'Variation Orders (VO)')
@section('page_title', 'Variation Orders (VO)')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-navy-950">Variation Orders (VO)</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pengelolaan perubahan kontrak, pekerjaan tambah, dan pekerjaan kurang.</p>
        </div>
        <a href="{{ route('variation-orders.create') }}" class="btn-primary self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Ajukan VO Baru</span>
        </a>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5 border-l-4 border-navy-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total VO Diajukan</p>
                    <p class="text-2xl font-black text-navy-950 mt-1">{{ $variationOrders->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-navy-50 text-navy-700 flex items-center justify-center">
                    <i data-lucide="git-pull-request" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Disetujui (Approved)</p>
                    <p class="text-2xl font-black text-emerald-900 mt-1">{{ \App\Models\VariationOrder::where('status', 'approved')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-construction-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-construction-700">Net Dampak Kontrak</p>
                    <p class="text-xl font-black text-construction-950 mt-1 font-mono">
                        @php $netChange = \App\Models\VariationOrder::where('status', 'approved')->sum('contract_amount_change'); @endphp
                        {{ $netChange >= 0 ? '+' : '' }}@rupiah($netChange)
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-construction-50 text-construction-600 flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Draf / Menunggu Review</p>
                    <p class="text-2xl font-black text-amber-900 mt-1">{{ \App\Models\VariationOrder::where('status', 'draft')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-4">
        <form method="GET" action="{{ route('variation-orders.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
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
                <label class="form-label text-xs">Status VO</label>
                <select name="status" onchange="this.form.submit()" class="form-select text-sm">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft (Review)</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                </select>
            </div>

            <div class="flex items-end">
                <a href="{{ route('variation-orders.index') }}" class="btn-secondary text-sm">Reset Filter</a>
            </div>
        </form>
    </div>

    <!-- VO Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr>
                        <th class="table-th">No. VO</th>
                        <th class="table-th">Proyek Terkait</th>
                        <th class="table-th">Tanggal</th>
                        <th class="table-th">Deskripsi & Alasan</th>
                        <th class="table-th text-right">Dampak Nilai Kontrak</th>
                        <th class="table-th text-right">Dampak Anggaran</th>
                        <th class="table-th text-center">Jadwal</th>
                        <th class="table-th text-center">Status</th>
                        <th class="table-th text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($variationOrders as $vo)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-bold text-navy-900 whitespace-nowrap">{{ $vo->vo_number }}</td>
                            <td class="table-td">
                                <span class="font-semibold text-slate-800 block truncate max-w-xs">{{ $vo->project?->name }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ $vo->project?->project_code }}</span>
                            </td>
                            <td class="table-td whitespace-nowrap">@dateid($vo->vo_date)</td>
                            <td class="table-td max-w-xs">
                                <span class="font-medium text-slate-800 block truncate">{{ $vo->description }}</span>
                                <span class="text-[10px] text-slate-400 block truncate">{{ $vo->reason ?: '-' }}</span>
                            </td>
                            <td class="table-td text-right font-bold whitespace-nowrap {{ $vo->contract_amount_change >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                {{ $vo->contract_amount_change >= 0 ? '+' : '' }}@rupiah($vo->contract_amount_change)
                            </td>
                            <td class="table-td text-right font-medium text-slate-700 whitespace-nowrap">
                                {{ $vo->budget_amount_change >= 0 ? '+' : '' }}@rupiah($vo->budget_amount_change)
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                @if($vo->schedule_days_change != 0)
                                    <span class="font-semibold {{ $vo->schedule_days_change > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                                        {{ $vo->schedule_days_change > 0 ? '+' : '' }}{{ $vo->schedule_days_change }} Hari
                                    </span>
                                @else
                                    <span class="text-slate-400">Tetap</span>
                                @endif
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                <span class="badge {{ $vo->status === 'approved' ? 'badge-success' : ($vo->status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                    {{ ucfirst($vo->status) }}
                                </span>
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                @if($vo->status === 'draft')
                                    <div class="flex items-center justify-center gap-1.5">
                                        <form method="POST" action="{{ route('variation-orders.approve', $vo) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-accent text-[11px] py-1 px-2.5">
                                                Setujui
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('variation-orders.reject', $vo) }}" class="inline" onsubmit="return confirm('Tolak VO ini?')">
                                            @csrf
                                            <button type="submit" class="p-1 rounded text-rose-500 hover:bg-rose-50">
                                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[11px] text-slate-400">
                                        @if($vo->approvedByUser)
                                            Oleh: {{ $vo->approvedByUser->name }}
                                        @else
                                            Diproses
                                        @endif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-400">
                                Belum ada Variation Order yang diajukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($variationOrders->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $variationOrders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
