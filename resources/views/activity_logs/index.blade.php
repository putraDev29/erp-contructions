@extends('layouts.app')

@section('title', 'Audit Trail & Log Aktivitas Sistem')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Audit Trail Log Sistem</h1>
            <p class="text-sm text-slate-500 mt-1">Rekaman jejak aktivitas pengguna, persetujuan transaksi, posting jurnal, dan perubahan master data.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card p-4">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="form-label text-xs">Filter Aksi</label>
                <select name="action" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Aksi</option>
                    <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="approved" {{ request('action') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="verified" {{ request('action') == 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="calculated" {{ request('action') == 'calculated' ? 'selected' : '' }}>Calculated</option>
                    <option value="paid" {{ request('action') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Cari Keterangan / Entitas</label>
                <input type="text" name="search" class="form-input text-sm" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary text-sm">Cari Log</button>
                <a href="{{ route('audit-logs.index') }}" class="btn-secondary text-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th w-44">Waktu (WIB)</th>
                        <th class="table-th">Pengguna</th>
                        <th class="table-th text-center">Aksi</th>
                        <th class="table-th">Entitas Model</th>
                        <th class="table-th">Keterangan Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-mono text-xs text-slate-500 whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="table-td">
                                <div class="font-bold text-navy-950 text-xs">{{ $log->user?->name ?? 'Sistem' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $log->user?->role?->name ?? 'System' }}</div>
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                @if($log->action === 'created')
                                    <span class="badge badge-primary text-[10px]">CREATED</span>
                                @elseif($log->action === 'approved')
                                    <span class="badge badge-success text-[10px]">APPROVED</span>
                                @elseif($log->action === 'paid')
                                    <span class="badge badge-success text-[10px]">PAID</span>
                                @elseif($log->action === 'updated')
                                    <span class="badge badge-warning text-[10px]">UPDATED</span>
                                @else
                                    <span class="badge badge-neutral text-[10px] uppercase">{{ $log->action }}</span>
                                @endif
                            </td>
                            <td class="table-td font-mono text-xs text-slate-700 whitespace-nowrap">
                                <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                    {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                </span>
                            </td>
                            <td class="table-td text-xs text-slate-800">
                                {{ $log->description }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                <i data-lucide="history" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada riwayat aktivitas yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
