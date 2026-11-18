@extends('layouts.app')

@section('title', 'Detail Payroll - ' . $period->period_code)

@section('content')
<div class="space-y-6" x-data="{ openDisburseModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-navy-950">{{ $period->title }}</h1>
                @if($period->status === 'paid')
                    <span class="badge badge-success">Sudah Dibayarkan</span>
                @elseif($period->status === 'calculated')
                    <span class="badge badge-primary">Telah Dikalkulasi</span>
                @else
                    <span class="badge badge-warning">Draft</span>
                @endif
            </div>
            <p class="text-sm text-slate-500 mt-1">Periode Kerja: @dateid($period->start_date) s/d @dateid($period->end_date) (Kode: {{ $period->period_code }})</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if($period->status !== 'paid')
                <form method="POST" action="{{ route('payrolls.calculate', $period) }}">
                    @csrf
                    <button type="submit" class="btn-primary inline-flex items-center gap-2 bg-construction-600 hover:bg-construction-700">
                        <i data-lucide="calculator" class="w-4 h-4"></i>
                        <span>{{ $period->status === 'draft' ? 'Hitung Upah Periode Ini' : 'Hitung Ulang Upah' }}</span>
                    </button>
                </form>

                @if($period->status === 'calculated' && $period->total_net > 0)
                    <button type="button" @click="openDisburseModal = true" class="btn-primary inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        <span>Cairkan / Bayar Gaji</span>
                    </button>
                @endif
            @endif

            <a href="{{ route('payrolls.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card p-5">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Upah Bruto</span>
            <h3 class="text-xl font-bold text-navy-950 font-mono mt-1">@rupiah($period->total_gross)</h3>
            <p class="text-xs text-slate-400 mt-1">Gaji pokok harian + lembur</p>
        </div>

        <div class="card p-5">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Potongan Kasbon</span>
            <h3 class="text-xl font-bold text-rose-600 font-mono mt-1">@rupiah($period->total_deductions)</h3>
            <p class="text-xs text-slate-400 mt-1">Dipotong otomatis dari kasbon aktif</p>
        </div>

        <div class="card p-5 bg-gradient-to-br from-navy-950 to-navy-900 text-white">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Upah Bersih Dicairkan</span>
            <h3 class="text-xl font-extrabold text-construction-400 font-mono mt-1">@rupiah($period->total_net)</h3>
            <p class="text-xs text-slate-300 mt-1">Total transfer / tunai ke pekerja</p>
        </div>
    </div>

    <!-- Worker Payroll Breakdown Table -->
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-navy-950">Rincian Upah Tiap Pekerja</h3>
            <span class="text-xs text-slate-500">{{ $period->payrolls->count() }} orang pekerja</span>
        </div>
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th">Pekerja</th>
                        <th class="table-th">Jabatan</th>
                        <th class="table-th text-center">Hari Kerja</th>
                        <th class="table-th text-center">Jam Lembur</th>
                        <th class="table-th text-right">Upah Pokok</th>
                        <th class="table-th text-right">Upah Lembur</th>
                        <th class="table-th text-right">Potongan Kasbon</th>
                        <th class="table-th text-right">Upah Bersih (Take Home)</th>
                        <th class="table-th text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($period->payrolls as $pay)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td">
                                <div class="font-bold text-navy-900">{{ $pay->employee?->name }}</div>
                                <div class="text-xs font-mono text-slate-400">{{ $pay->employee?->employee_number }}</div>
                            </td>
                            <td class="table-td">{{ $pay->employee?->role_title }}</td>
                            <td class="table-td text-center font-bold text-navy-950">{{ $pay->work_days }} hr</td>
                            <td class="table-td text-center font-mono">{{ $pay->overtime_hours > 0 ? $pay->overtime_hours . ' jam' : '-' }}</td>
                            <td class="table-td text-right font-mono text-xs">@rupiah($pay->regular_earnings)</td>
                            <td class="table-td text-right font-mono text-xs">@rupiah($pay->overtime_earnings)</td>
                            <td class="table-td text-right font-mono text-xs text-rose-600">
                                {{ $pay->advance_deductions > 0 ? '-' . number_format($pay->advance_deductions, 0, ',', '.') : '-' }}
                            </td>
                            <td class="table-td text-right font-mono font-bold text-navy-950 text-sm">
                                @rupiah($pay->net_salary)
                            </td>
                            <td class="table-td text-center">
                                <a href="{{ route('payrolls.slip-pdf', $pay) }}" target="_blank" class="btn-secondary text-xs px-2.5 py-1 inline-flex items-center gap-1" title="Cetak Slip Gaji">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                    <span>Slip</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-12 text-slate-400">
                                <i data-lucide="calculator" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada kalkulasi upah. Klik tombol "Hitung Upah Periode Ini" di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL PENCAIRAN GAJI -->
    <div x-show="openDisburseModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-6" @click.away="openDisburseModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Pencairan / Pembayaran Gaji</h3>
                    <p class="text-xs text-slate-500">Pilih rekening sumber pembayaran upah pekerja.</p>
                </div>
                <button type="button" @click="openDisburseModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('payrolls.pay', $period) }}" class="space-y-4">
                @csrf

                <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                    <span class="text-xs text-slate-500">Total Upah yang Akan Dicairkan:</span>
                    <div class="text-xl font-extrabold text-navy-950 font-mono mt-0.5">@rupiah($period->total_net)</div>
                </div>

                <div>
                    <label class="form-label required">Rekening Sumber Kas / Bank</label>
                    <select name="bank_account_id" class="form-select text-sm" required>
                        <option value="">-- Pilih Rekening Kas / Bank --</option>
                        @foreach($bankAccounts as $ba)
                            <option value="{{ $ba->id }}">
                                {{ $ba->account_name }} ({{ $ba->account_number }}) - Saldo: Rp {{ number_format($ba->current_balance, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label required">Tanggal Pembayaran</label>
                    <input type="date" name="payment_date" class="form-input text-sm" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openDisburseModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm bg-emerald-600 hover:bg-emerald-700">Konfirmasi Pembayaran Gaji</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
