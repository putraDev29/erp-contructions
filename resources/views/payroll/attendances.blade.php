@extends('layouts.app')

@section('title', 'Absensi Harian Tenaga Kerja')

@section('content')
<div class="space-y-6" x-data="{ openBatchModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Absensi Harian Lapangan</h1>
            <p class="text-sm text-slate-500 mt-1">Pencatatan kehadiran, lembur, dan penempatan tenaga kerja ke proyek konstruksi.</p>
        </div>
        <button type="button" @click="openBatchModal = true" class="btn-primary inline-flex items-center gap-2">
            <i data-lucide="check-square" class="w-4 h-4"></i>
            <span>Input Absensi Proyek</span>
        </button>
    </div>

    <!-- Filter Bar -->
    <div class="card p-4">
        <form method="GET" action="{{ route('attendances.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="form-label text-xs">Pilih Tanggal</label>
                <input type="date" name="date" class="form-input text-sm" value="{{ $date }}" onchange="this.form.submit()">
            </div>
            <div>
                <label class="form-label text-xs">Filter Proyek</label>
                <select name="project_id" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Proyek</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ $projectId == $p->id ? 'selected' : '' }}>
                            {{ $p->code }} - {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <a href="{{ route('attendances.index') }}" class="btn-secondary text-sm">Hari Ini</a>
            </div>
        </form>
    </div>

    <!-- Attendances Table -->
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-navy-950">Daftar Kehadiran Lapangan: @dateid($date)</h3>
            <span class="text-xs text-slate-400">{{ $attendances->total() }} catatan absensi</span>
        </div>
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th">Pekerja</th>
                        <th class="table-th">Jabatan</th>
                        <th class="table-th">Proyek</th>
                        <th class="table-th">Status Kehadiran</th>
                        <th class="table-th text-right">Jam Lembur</th>
                        <th class="table-th text-right">Upah Harian</th>
                        <th class="table-th text-right">Upah Lembur</th>
                        <th class="table-th">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td">
                                <div class="font-bold text-navy-900">{{ $att->employee?->name }}</div>
                                <div class="text-xs font-mono text-slate-400">{{ $att->employee?->employee_number }}</div>
                            </td>
                            <td class="table-td text-xs font-medium text-slate-700">{{ $att->employee?->role_title }}</td>
                            <td class="table-td">
                                <span class="badge badge-neutral text-xs">{{ $att->project?->name ?? 'Head Office' }}</span>
                            </td>
                            <td class="table-td">
                                @if($att->status === 'present')
                                    <span class="badge badge-success text-xs"><i data-lucide="check" class="w-3 h-3"></i> Hadir Penuh</span>
                                @elseif($att->status === 'half_day')
                                    <span class="badge badge-primary text-xs"><i data-lucide="clock" class="w-3 h-3"></i> Setengah Hari</span>
                                @elseif($att->status === 'sick')
                                    <span class="badge badge-warning text-xs"><i data-lucide="activity" class="w-3 h-3"></i> Sakit</span>
                                @elseif($att->status === 'leave')
                                    <span class="badge badge-neutral text-xs">Izin</span>
                                @else
                                    <span class="badge badge-danger text-xs">Alpa / Absen</span>
                                @endif
                            </td>
                            <td class="table-td text-right font-mono font-medium whitespace-nowrap">{{ $att->overtime_hours > 0 ? $att->overtime_hours . ' Jam' : '-' }}</td>
                            <td class="table-td text-right font-mono text-xs whitespace-nowrap">@rupiah($att->daily_wage)</td>
                            <td class="table-td text-right font-mono text-xs whitespace-nowrap">@rupiah($att->overtime_wage)</td>
                            <td class="table-td text-xs text-slate-500">{{ $att->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <i data-lucide="calendar-check" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada catatan absensi untuk tanggal ini. Klik "Input Absensi Proyek" untuk mengisi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $attendances->links() }}
        </div>
    </div>

    <!-- MODAL INPUT ABSENSI PROYEK (MULTI-WORKER) -->
    <div x-show="openBatchModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full p-6 space-y-6" @click.away="openBatchModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Input Absensi Harian Tenaga Kerja</h3>
                    <p class="text-xs text-slate-500">Pilih proyek dan tentukan status kehadiran serta jam lembur pekerja.</p>
                </div>
                <button type="button" @click="openBatchModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('attendances.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Proyek Konstruksi</label>
                        <select name="project_id" class="form-select text-sm" required>
                            <option value="">-- Pilih Proyek --</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ $projectId == $p->id ? 'selected' : '' }}>
                                    {{ $p->code }} - {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label required">Tanggal Absensi</label>
                        <input type="date" name="date" class="form-input text-sm" value="{{ $date }}" required>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Daftar Pekerja Lapangan</h4>
                    <div class="max-h-80 overflow-y-auto space-y-2 pr-1">
                        @foreach($employees as $idx => $emp)
                            <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                <div class="w-48">
                                    <div class="font-bold text-navy-900">{{ $emp->name }}</div>
                                    <div class="text-slate-500">{{ $emp->role_title }} (@rupiah($emp->daily_rate)/hr)</div>
                                    <input type="hidden" name="attendances[{{ $idx }}][employee_id]" value="{{ $emp->id }}">
                                </div>
                                <div class="flex items-center gap-3">
                                    <div>
                                        <select name="attendances[{{ $idx }}][status]" class="form-select text-xs py-1">
                                            <option value="present">Hadir Penuh</option>
                                            <option value="half_day">Setengah Hari</option>
                                            <option value="sick">Sakit</option>
                                            <option value="leave">Izin</option>
                                            <option value="absent">Absen / Alpa</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="number" step="0.5" min="0" max="12" name="attendances[{{ $idx }}][overtime_hours]" value="0" placeholder="Lembur (jam)" class="form-input text-xs py-1 w-24 text-right">
                                    </div>
                                    <div>
                                        <input type="text" name="attendances[{{ $idx }}][notes]" placeholder="Keterangan..." class="form-input text-xs py-1 w-36">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openBatchModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Simpan Absensi Harian</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
