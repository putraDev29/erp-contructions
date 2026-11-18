@extends('layouts.app')

@section('title', 'Laporan Arus Kas (Cash Flow)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Laporan Arus Kas (Cash Flow)</h1>
            <p class="text-sm text-slate-500 mt-1">Rekapitulasi penerimaan kas riil dari pelanggan dan pengeluaran kas operasional.</p>
        </div>
        <a href="{{ route('finance.cash-bank') }}" class="btn-secondary text-sm">Kas & Rekening Bank</a>
    </div>

    <!-- Filter Dates -->
    <div class="card p-4">
        <form method="GET" action="{{ route('reports.cash-flow') }}" class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div>
                <label class="form-label text-xs">Periode Mulai</label>
                <input type="date" name="start_date" class="form-input text-sm" value="{{ $startDate }}">
            </div>
            <div>
                <label class="form-label text-xs">Periode Selesai</label>
                <input type="date" name="end_date" class="form-input text-sm" value="{{ $endDate }}">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary text-sm">Filter Periode</button>
            </div>
        </form>
    </div>

    <!-- Cash Flow Card -->
    <div class="card p-6 space-y-6 text-sm">
        <!-- 1. ARUS KAS MASUK (INFLOWS) -->
        <div>
            <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400 mb-3 border-b border-slate-200 pb-1">
                1. ARUS KAS MASUK OPERASIONAL (CASH INFLOWS)
            </h4>
            <div class="space-y-2">
                <div class="flex justify-between text-slate-700">
                    <span class="pl-4">Penerimaan Pembayaran Termin & Uang Muka dari Pelanggan</span>
                    <span class="font-mono font-medium">@rupiah($clientReceipts)</span>
                </div>
                <div class="flex justify-between text-slate-700">
                    <span class="pl-4">Penerimaan Kas Operasional Lain-lain (BKM)</span>
                    <span class="font-mono font-medium">@rupiah($otherInflows)</span>
                </div>
                <div class="flex justify-between font-bold text-navy-950 pt-2 border-t border-slate-200">
                    <span>TOTAL ARUS KAS MASUK:</span>
                    <span class="font-mono text-emerald-600">@rupiah($totalInflows)</span>
                </div>
            </div>
        </div>

        <!-- 2. ARUS KAS KELUAR (OUTFLOWS) -->
        <div>
            <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400 mb-3 border-b border-slate-200 pb-1">
                2. ARUS KAS KELUAR OPERASIONAL (CASH OUTFLOWS)
            </h4>
            <div class="space-y-2">
                <div class="flex justify-between text-slate-700">
                    <span class="pl-4">Pembayaran Hutang Tagihan Supplier Material & Vendor</span>
                    <span class="font-mono font-medium">@rupiah($supplierDisbursements)</span>
                </div>
                <div class="flex justify-between text-slate-700">
                    <span class="pl-4">Pencairan Gaji, Upah Tukang & Lembur (Payroll)</span>
                    <span class="font-mono font-medium">@rupiah($payrollDisbursements)</span>
                </div>
                <div class="flex justify-between text-slate-700">
                    <span class="pl-4">Pengeluaran Kas Operasional Lapangan & Kantor (BKK)</span>
                    <span class="font-mono font-medium">@rupiah($otherOutflows)</span>
                </div>
                <div class="flex justify-between font-bold text-navy-950 pt-2 border-t border-slate-200">
                    <span>TOTAL ARUS KAS KELUAR:</span>
                    <span class="font-mono text-rose-600">@rupiah($totalOutflows)</span>
                </div>
            </div>
        </div>

        <!-- 3. NET CASH FLOW & SALDO AKHIR -->
        <div class="border-t-2 border-slate-200 pt-4 space-y-3">
            <div class="flex justify-between font-bold text-base text-navy-950">
                <span>ARUS KAS BERSIH (NET CASH FLOW):</span>
                <span class="font-mono {{ $netCashFlow >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $netCashFlow >= 0 ? '+' : '' }}@rupiah($netCashFlow)
                </span>
            </div>

            <div class="p-4 bg-navy-950 text-white rounded-xl flex items-center justify-between">
                <div>
                    <span class="text-xs uppercase tracking-wider text-slate-300 font-semibold block">TOTAL SALDO KAS & REKENING BANK AKTIF SAAT INI:</span>
                    <span class="text-xs text-slate-400 mt-0.5 block">Di seluruh rekening bank perusahaan & kas kecil</span>
                </div>
                <span class="font-mono text-2xl font-black text-construction-400">@rupiah($currentTotalCash)</span>
            </div>
        </div>
    </div>
</div>
@endsection
