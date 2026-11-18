@extends('layouts.app')

@section('title', 'Kasbon Pekerja (Employee Advances)')

@section('content')
<div class="space-y-6" x-data="{ openAdvanceModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Kasbon & Uang Muka Pekerja</h1>
            <p class="text-sm text-slate-500 mt-1">Pengelolaan kasbon pekerja yang akan dipotong secara otomatis saat penggajian periode berikutnya.</p>
        </div>
        <button type="button" @click="openAdvanceModal = true" class="btn-primary inline-flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Beri Kasbon Baru</span>
        </button>
    </div>

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card p-5 border-l-4 border-navy-700">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Kasbon Diajukan</span>
            <h3 class="text-2xl font-black text-navy-950 mt-1">{{ $advances->total() }}</h3>
            <p class="text-xs text-slate-400 mt-1">Catatan uang muka karyawan</p>
        </div>
        <div class="card p-5 border-l-4 border-rose-500">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-700">Sisa Kasbon Belum Lunas</span>
            <h3 class="text-xl font-black text-rose-950 mt-1 font-mono">
                @rupiah(\App\Models\EmployeeAdvance::sum('remaining_amount'))
            </h3>
            <p class="text-xs text-slate-400 mt-1">Akan dipotong saat gajian</p>
        </div>
        <div class="card p-5 border-l-4 border-emerald-500">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Total Sudah Lunas Dipotong</span>
            <h3 class="text-xl font-black text-emerald-900 mt-1 font-mono">
                @php $repaid = \App\Models\EmployeeAdvance::sum('amount') - \App\Models\EmployeeAdvance::sum('remaining_amount'); @endphp
                @rupiah($repaid)
            </h3>
            <p class="text-xs text-slate-400 mt-1">Selesai diperhitungkan</p>
        </div>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th">No. Kasbon</th>
                        <th class="table-th">Pekerja</th>
                        <th class="table-th">Jabatan</th>
                        <th class="table-th">Tanggal Kasbon</th>
                        <th class="table-th text-right">Nominal Pinjaman</th>
                        <th class="table-th text-right">Sisa Belum Dipotong</th>
                        <th class="table-th text-center">Status</th>
                        <th class="table-th">Alasan / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($advances as $adv)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-bold text-navy-900 font-mono">{{ $adv->advance_number }}</td>
                            <td class="table-td">
                                <div class="font-bold text-slate-900">{{ $adv->employee?->name }}</div>
                                <div class="text-xs font-mono text-slate-400">{{ $adv->employee?->employee_number }}</div>
                            </td>
                            <td class="table-td text-xs font-medium text-slate-700">{{ $adv->employee?->role_title }}</td>
                            <td class="table-td whitespace-nowrap text-xs text-slate-700 font-medium">@dateid($adv->advance_date)</td>
                            <td class="table-td text-right font-mono font-medium whitespace-nowrap">@rupiah($adv->amount)</td>
                            <td class="table-td text-right font-mono font-black whitespace-nowrap {{ $adv->remaining_amount > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                @rupiah($adv->remaining_amount)
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                @if($adv->status === 'repaid' || $adv->remaining_amount <= 0)
                                    <span class="badge badge-success text-xs"><i data-lucide="check" class="w-3 h-3"></i> Lunas Dipotong</span>
                                @else
                                    <span class="badge badge-warning text-xs"><i data-lucide="clock" class="w-3 h-3"></i> Belum Lunas</span>
                                @endif
                            </td>
                            <td class="table-td text-xs text-slate-500 max-w-xs truncate">{{ $adv->reason ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <i data-lucide="banknote" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada data kasbon pekerja.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $advances->links() }}
        </div>
    </div>

    <!-- MODAL BERI KASBON -->
    <div x-show="openAdvanceModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-6" @click.away="openAdvanceModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Catat Pinjaman Kasbon Pekerja</h3>
                    <p class="text-xs text-slate-500">Akan dicatat sebagai piutang karyawan dan dipotong pada slip gaji.</p>
                </div>
                <button type="button" @click="openAdvanceModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('advances.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label required">Pilih Pekerja</label>
                    <select name="employee_id" class="form-select text-sm" required>
                        <option value="">-- Pilih Pekerja --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">
                                {{ $emp->name }} ({{ $emp->role_title }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label required">Tanggal Kasbon</label>
                    <input type="date" name="advance_date" class="form-input text-sm" value="{{ date('Y-m-d') }}" required>
                </div>

                <div>
                    <label class="form-label required">Nominal Kasbon (IDR)</label>
                    <input type="number" step="10000" min="10000" name="amount" class="form-input text-sm font-mono font-bold" placeholder="500000" required>
                </div>

                <div>
                    <label class="form-label">Keperluan / Alasan</label>
                    <textarea name="reason" rows="2" class="form-input text-sm" placeholder="Contoh: Keperluan keluarga, biaya sekolah"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openAdvanceModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Setujui & Simpan Kasbon</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
