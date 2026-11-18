@extends('layouts.app')

@section('title', 'Daftar Proyek')
@section('page_title', 'Manajemen Proyek')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-navy-950">Daftar Proyek Konstruksi</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola seluruh portofolio proyek, kontrak kerja, dan jadwal konstruksi.</p>
        </div>
        <a href="{{ route('projects.create') }}" class="btn-primary self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Buat Proyek Baru</span>
        </a>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5 border-l-4 border-navy-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Proyek</p>
                    <p class="text-2xl font-black text-navy-950 mt-1">{{ $projects->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-navy-50 text-navy-700 flex items-center justify-center">
                    <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-construction-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-construction-700">Nilai Kontrak Aktif</p>
                    <p class="text-xl font-black text-construction-950 mt-1 font-mono">
                        @rupiah(\App\Models\Project::sum('contract_amount'))
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-construction-50 text-construction-600 flex items-center justify-center">
                    <i data-lucide="badge-dollar-sign" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-sky-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-sky-700">Sedang Konstruksi</p>
                    <p class="text-2xl font-black text-sky-900 mt-1">{{ \App\Models\Project::where('status', 'in_progress')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                    <i data-lucide="hard-hat" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Proyek Selesai</p>
                    <p class="text-2xl font-black text-emerald-900 mt-1">{{ \App\Models\Project::where('status', 'completed')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card p-4">
        <form method="GET" action="{{ route('projects.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <label class="form-label text-xs">Cari Proyek</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari kode proyek, nama proyek, atau lokasi..." 
                       class="form-input text-sm">
            </div>

            <div>
                <label class="form-label text-xs">Status Proyek</label>
                <select name="status" onchange="this.form.submit()" class="form-select text-sm">
                    <option value="">Semua Status</option>
                    @foreach(['draft', 'tender', 'contract', 'preparation', 'in_progress', 'completed', 'on_hold', 'cancelled'] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $st)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label text-xs">Jenis Konstruksi</label>
                <select name="type" onchange="this.form.submit()" class="form-select text-sm">
                    <option value="">Semua Jenis Pekerjaan</option>
                    <option value="residential" {{ request('type') === 'residential' ? 'selected' : '' }}>Rumah Tinggal</option>
                    <option value="commercial" {{ request('type') === 'commercial' ? 'selected' : '' }}>Gedung Komersial</option>
                    <option value="steel_construction" {{ request('type') === 'steel_construction' ? 'selected' : '' }}>Konstruksi Baja</option>
                    <option value="renovation" {{ request('type') === 'renovation' ? 'selected' : '' }}>Renovasi</option>
                    <option value="civil_works" {{ request('type') === 'civil_works' ? 'selected' : '' }}>Pekerjaan Sipil</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Projects Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr>
                        <th class="table-th">Kode & Nama Proyek</th>
                        <th class="table-th">Klien / Lokasi</th>
                        <th class="table-th">Target Waktu</th>
                        <th class="table-th text-right">Nilai Kontrak</th>
                        <th class="table-th text-right">Anggaran RAB</th>
                        <th class="table-th">Progres Fisik</th>
                        <th class="table-th text-center">Status</th>
                        <th class="table-th text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projects as $proj)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td">
                                <a href="{{ route('projects.show', $proj) }}" class="font-bold text-navy-900 hover:text-construction-600 block text-xs sm:text-sm">
                                    {{ $proj->project_code }}
                                </a>
                                <span class="font-medium text-slate-800 block truncate max-w-xs mt-0.5">{{ $proj->name }}</span>
                                <span class="text-[10px] text-slate-400 uppercase tracking-wide">{{ ucfirst(str_replace('_', ' ', $proj->type)) }}</span>
                            </td>
                            <td class="table-td">
                                <span class="font-semibold text-slate-700 block truncate max-w-[180px]">{{ $proj->customer?->name }}</span>
                                <span class="text-[11px] text-slate-400 block truncate max-w-[180px]">{{ $proj->location ?: '-' }}</span>
                            </td>
                            <td class="table-td whitespace-nowrap">
                                <span class="block text-slate-600">Mulai: @dateid($proj->start_date)</span>
                                <span class="block text-slate-500 text-[10px]">Target: @dateid($proj->deadline_date)</span>
                            </td>
                            <td class="table-td text-right font-bold text-navy-950 whitespace-nowrap">
                                @rupiah($proj->active_contract_amount)
                            </td>
                            <td class="table-td text-right font-medium text-slate-700 whitespace-nowrap">
                                @rupiah($proj->active_budget_amount)
                            </td>
                            <td class="table-td min-w-[130px]">
                                <div class="flex items-center justify-between text-[11px] font-bold text-slate-700 mb-1">
                                    <span>{{ $proj->physical_progress_percent }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-construction-500 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ min(100, $proj->physical_progress_percent) }}%"></div>
                                </div>
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                @php
                                    $badgeClass = match($proj->status) {
                                        'in_progress' => 'badge-primary',
                                        'completed' => 'badge-success',
                                        'tender', 'contract' => 'badge-info',
                                        'preparation' => 'badge-warning',
                                        'on_hold', 'cancelled' => 'badge-danger',
                                        default => 'badge-neutral',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $proj->status)) }}
                                </span>
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('projects.show', $proj) }}" 
                                       class="p-1.5 rounded-lg text-slate-500 hover:text-navy-900 hover:bg-slate-100 transition-colors" title="Buka Detail Proyek">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('projects.edit', $proj) }}" 
                                       class="p-1.5 rounded-lg text-slate-500 hover:text-navy-900 hover:bg-slate-100 transition-colors" title="Edit Proyek">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
                                <i data-lucide="folder-search" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                <p class="font-medium text-sm">Tidak ada proyek yang sesuai dengan kriteria pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($projects->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $projects->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
