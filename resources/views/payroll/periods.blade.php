@extends('layouts.app')

@section('title', 'Periode Penggajian & Payroll')

@section('content')
<div class="space-y-6" x-data="{ openPeriodModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Payroll & Penggajian Pekerja</h1>
            <p class="text-sm text-slate-500 mt-1">Kalkulasi upah mingguan/bulanan berdasarkan absensi harian dan jam lembur riil lapangan.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('attendances.index') }}" class="btn-secondary inline-flex items-center gap-2">
                <i data-lucide="calendar-check" class="w-4 h-4"></i>
                <span>Absensi Lapangan</span>
            </a>
            <a href="{{ route('advances.index') }}" class="btn-secondary inline-flex items-center gap-2">
                <i data-lucide="wallet" class="w-4 h-4"></i>
                <span>Kasbon Pekerja</span>
            </a>
            <button type="button" @click="openPeriodModal = true" class="btn-primary inline-flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Buka Periode Payroll Baru</span>
            </button>
        </div>
    </div>

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card p-5 border-l-4 border-navy-700">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Periode Payroll</span>
            <h3 class="text-2xl font-black text-navy-950 mt-1">{{ $periods->total() }}</h3>
            <p class="text-xs text-slate-400 mt-1">Siklus penggajian tenaga kerja</p>
        </div>
        <div class="card p-5 border-l-4 border-emerald-500">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Total Upah Dicairkan</span>
            <h3 class="text-xl font-black text-emerald-900 mt-1 font-mono">
                @rupiah(\App\Models\Payroll::where('payment_status', 'paid')->sum('net_salary'))
            </h3>
            <p class="text-xs text-slate-400 mt-1">Telah dibayar & dibukukan</p>
        </div>
        <div class="card p-5 border-l-4 border-amber-500">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Belum Dicairkan (Pending)</span>
            <h3 class="text-xl font-black text-amber-900 mt-1 font-mono">
                @rupiah(\App\Models\Payroll::where('payment_status', '!=', 'paid')->sum('net_salary'))
            </h3>
            <p class="text-xs text-slate-400 mt-1">Draft & siap diajukan</p>
        </div>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th">Kode Periode</th>
                        <th class="table-th">Keterangan Periode</th>
                        <th class="table-th">Rentang Tanggal</th>
                        <th class="table-th text-right">Total Upah Bruto</th>
                        <th class="table-th text-right">Potongan Kasbon</th>
                        <th class="table-th text-right">Total Upah Bersih</th>
                        <th class="table-th">Status</th>
                        <th class="table-th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periods as $period)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-bold text-navy-900 font-mono">{{ $period->period_code }}</td>
                            <td class="table-td">
                                <a href="{{ route('payrolls.show', $period) }}" class="font-bold text-navy-950 hover:text-construction-600 transition-colors">
                                    {{ $period->title }}
                                </a>
                                <div class="text-xs text-slate-400">{{ $period->payrolls_count }} pekerja dihitung</div>
                            </td>
                            <td class="table-td whitespace-nowrap">
                                <div class="text-xs text-slate-700 font-medium">@dateid($period->start_date) - @dateid($period->end_date)</div>
                            </td>
                            <td class="table-td text-right font-mono font-medium whitespace-nowrap">@rupiah($period->total_gross)</td>
                            <td class="table-td text-right font-mono text-slate-600 whitespace-nowrap">@rupiah($period->total_deductions)</td>
                            <td class="table-td text-right font-mono font-black text-navy-950 whitespace-nowrap">@rupiah($period->total_net)</td>
                            <td class="table-td whitespace-nowrap">
                                @if($period->status === 'paid')
                                    <span class="badge badge-success text-xs"><i data-lucide="check" class="w-3 h-3"></i> Sudah Dicairkan</span>
                                @elseif($period->status === 'calculated')
                                    <span class="badge badge-primary text-xs"><i data-lucide="calculator" class="w-3 h-3"></i> Siap Bayar</span>
                                @else
                                    <span class="badge badge-warning text-xs">Draft</span>
                                @endif
                            </td>
                            <td class="table-td text-right whitespace-nowrap">
                                <a href="{{ route('payrolls.show', $period) }}" class="btn-primary btn-xs">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Detail / Bayar</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <i data-lucide="banknote" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada periode payroll yang dibuka.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $periods->links() }}
        </div>
    </div>

    <!-- MODAL BUKA PERIODE PAYROLL -->
    <div x-show="openPeriodModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-6" @click.away="openPeriodModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Buka Periode Payroll Baru</h3>
                    <p class="text-xs text-slate-500">Tentukan rentang tanggal kerja mingguan atau bulanan.</p>
                </div>
                <button type="button" @click="openPeriodModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('payrolls.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label required">Keterangan / Judul Periode</label>
                    <input type="text" name="title" class="form-input text-sm" placeholder="Contoh: Upah Mingguan Proyek 06 - 12 Okt 2026" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-input text-sm" value="{{ date('Y-m-d', strtotime('-6 days')) }}" required>
                    </div>
                    <div>
                        <label class="form-label required">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-input text-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openPeriodModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Buka Periode</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
