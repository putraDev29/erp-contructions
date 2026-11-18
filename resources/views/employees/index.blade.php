@extends('layouts.app')

@section('title', 'Daftar Tenaga Kerja & Tukang')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Tenaga Kerja & Tukang</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar staf, mandor, tukang spesialis, dan pekerja harian lapangan proyek.</p>
        </div>
        <a href="{{ route('employees.create') }}" class="btn-primary inline-flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Tambah Pekerja</span>
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="card p-4">
        <form method="GET" action="{{ route('employees.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="form-label text-xs">Cari Nama / NIK</label>
                <input type="text" name="search" class="form-input text-sm" placeholder="Nama, NIK, jabatan..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="form-label text-xs">Jenis Hubungan Kerja</label>
                <select name="employment_type" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Hubungan Kerja</option>
                    <option value="daily" {{ request('employment_type') == 'daily' ? 'selected' : '' }}>Harian Lepas (Tukang)</option>
                    <option value="contract" {{ request('employment_type') == 'contract' ? 'selected' : '' }}>Kontrak Proyek</option>
                    <option value="permanent" {{ request('employment_type') == 'permanent' ? 'selected' : '' }}>Karyawan Tetap</option>
                    <option value="piece_rate" {{ request('employment_type') == 'piece_rate' ? 'selected' : '' }}>Borongan</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary text-sm">Cari</button>
                <a href="{{ route('employees.index') }}" class="btn-secondary text-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>NIK & Nama</th>
                        <th>Jabatan / Spesialisasi</th>
                        <th>Hubungan Kerja</th>
                        <th class="text-right">Upah Harian</th>
                        <th class="text-right">Tarif Lembur / Jam</th>
                        <th class="text-right">Kasbon Aktif</th>
                        <th>Info Rekening Pembayaran</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                        @php
                            $advanceBal = $emp->active_advance_balance;
                        @endphp
                        <tr>
                            <td>
                                <div class="font-bold text-navy-900">{{ $emp->name }}</div>
                                <div class="text-xs font-mono text-slate-400 font-semibold">{{ $emp->employee_number ?: $emp->nik }}</div>
                            </td>
                            <td>
                                <span class="text-xs font-semibold text-slate-800">{{ $emp->role_title ?: $emp->position }}</span>
                            </td>
                            <td>
                                @if($emp->employment_type === 'daily' || $emp->employment_type === 'daily_worker')
                                    <span class="badge-primary text-[10px]">Harian Lepas</span>
                                @elseif($emp->employment_type === 'contract')
                                    <span class="badge-neutral text-[10px]">Kontrak</span>
                                @elseif($emp->employment_type === 'permanent')
                                    <span class="badge-success text-[10px]">Tetap</span>
                                @else
                                    <span class="badge-warning text-[10px]">Borongan</span>
                                @endif
                            </td>
                            <td class="text-right font-mono font-medium text-xs">@rupiah($emp->daily_rate)</td>
                            <td class="text-right font-mono text-xs text-slate-600">@rupiah($emp->hourly_rate)</td>
                            <td class="text-right font-mono text-xs font-bold {{ $advanceBal > 0 ? 'text-amber-600' : 'text-slate-400' }}">
                                {{ $advanceBal > 0 ? 'Rp ' . number_format($advanceBal, 0, ',', '.') : '-' }}
                            </td>
                            <td>
                                <div class="text-xs text-slate-700 font-medium">{{ $emp->bank_name ?? 'Tunai' }}</div>
                                <div class="text-[11px] font-mono text-slate-400">{{ $emp->bank_account_number ?: ($emp->bank_account_no ?? '-') }}</div>
                            </td>
                            <td class="text-center">
                                @if($emp->is_active)
                                    <span class="badge-success text-[10px]">Aktif</span>
                                @else
                                    <span class="badge-danger text-[10px]">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('employees.edit', $emp) }}" class="btn-secondary text-xs px-2.5 py-1 inline-flex items-center gap-1">
                                    <i data-lucide="edit-3" class="w-3 h-3"></i>
                                    <span>Edit</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-12 text-slate-400">
                                <i data-lucide="users" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada data pekerja/tukang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@endsection
