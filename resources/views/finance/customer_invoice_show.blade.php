@extends('layouts.app')

@section('title', 'Invoice ' . $customerInvoice->invoice_number)

@section('content')
<div class="space-y-6" x-data="{ openPayModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-navy-950">{{ $customerInvoice->invoice_number }}</h1>
                @if($customerInvoice->status === 'paid')
                    <span class="badge badge-success">Lunas</span>
                @elseif($customerInvoice->status === 'partial')
                    <span class="badge badge-primary">Dibayar Sebagian</span>
                @else
                    <span class="badge badge-warning">Belum Dibayar</span>
                @endif
            </div>
            <p class="text-sm text-slate-500 mt-1">Diterbitkan pada @dateid($customerInvoice->invoice_date) untuk {{ $customerInvoice->customer?->name }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if($customerInvoice->remaining_amount > 0)
                <button type="button" @click="openPayModal = true" class="btn-primary inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                    <span>Terima Pembayaran</span>
                </button>
            @endif
            <a href="{{ route('customer-invoices.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </div>

    <!-- Meta Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card p-5 space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Informasi Proyek & Termin</h3>
            <div>
                <div class="font-bold text-navy-950 text-base">{{ $customerInvoice->project?->name }}</div>
                <div class="text-xs font-mono text-slate-500 mt-0.5">Kode: {{ $customerInvoice->project?->code }}</div>
                <div class="text-xs font-medium text-navy-800 mt-2 bg-slate-100 px-2 py-1 rounded inline-block">
                    Termin: {{ $customerInvoice->milestone_description }}
                </div>
            </div>
        </div>

        <div class="card p-5 space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Data Pelanggan (Client)</h3>
            <div>
                <div class="font-bold text-navy-950 text-base">{{ $customerInvoice->customer?->name }}</div>
                <div class="text-xs text-slate-600 mt-0.5">{{ $customerInvoice->customer?->contact_person }} ({{ $customerInvoice->customer?->phone }})</div>
                <div class="text-xs text-slate-500 mt-1">{{ $customerInvoice->customer?->address }}</div>
            </div>
        </div>

        <div class="card p-5 space-y-3 bg-gradient-to-br from-navy-950 to-navy-900 text-white">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Saldo Piutang</h3>
            <div class="space-y-1.5 text-xs">
                <div class="flex justify-between text-slate-300">
                    <span>Total Tagihan:</span>
                    <span class="font-mono">@rupiah($customerInvoice->net_amount)</span>
                </div>
                <div class="flex justify-between text-emerald-400">
                    <span>Sudah Diterima:</span>
                    <span class="font-mono">@rupiah($customerInvoice->paid_amount)</span>
                </div>
                <div class="border-t border-navy-800 pt-2 flex justify-between font-bold text-base text-white">
                    <span>Sisa Piutang:</span>
                    <span class="font-mono text-construction-400">@rupiah($customerInvoice->remaining_amount)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments History -->
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-navy-950">Riwayat Pembayaran Pelanggan</h3>
            <span class="text-xs text-slate-500">{{ $customerInvoice->payments->count() }} transaksi pembayaran</span>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Kwitansi</th>
                        <th>Tanggal Bayar</th>
                        <th>Rekening Penerima</th>
                        <th>Ref / Bukti Transfer</th>
                        <th class="text-right">Nominal Diterima</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customerInvoice->payments as $pay)
                        <tr>
                            <td class="font-bold text-navy-900 font-mono">{{ $pay->payment_number }}</td>
                            <td>@dateid($pay->payment_date)</td>
                            <td>{{ $pay->bankAccount?->account_name }}</td>
                            <td class="font-mono text-xs">{{ $pay->reference_number ?? '-' }}</td>
                            <td class="text-right font-mono font-bold text-emerald-600">@rupiah($pay->amount)</td>
                            <td class="text-xs text-slate-500">{{ $pay->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <i data-lucide="receipt" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada pembayaran yang diterima untuk invoice ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TERIMA PEMBAYARAN PELANGGAN -->
    <div x-show="openPayModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-6" @click.away="openPayModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Terima Pembayaran Pelanggan</h3>
                    <p class="text-xs text-slate-500">Sisa piutang tagihan ini: Rp {{ number_format($customerInvoice->remaining_amount, 0, ',', '.') }}</p>
                </div>
                <button type="button" @click="openPayModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('customer-invoices.payment', $customerInvoice) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label required">Rekening Kas / Bank Penerima</label>
                    <select name="bank_account_id" class="form-select text-sm" required>
                        <option value="">-- Pilih Rekening Penerima --</option>
                        @foreach($bankAccounts as $ba)
                            <option value="{{ $ba->id }}">{{ $ba->account_name }} ({{ $ba->account_number }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Tanggal Pembayaran</label>
                        <input type="date" name="payment_date" class="form-input text-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="form-label required">Nominal Diterima (IDR)</label>
                        <input type="number" step="1000" min="1000" max="{{ $customerInvoice->remaining_amount }}" name="amount" value="{{ $customerInvoice->remaining_amount }}" class="form-input text-sm font-mono font-bold" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">No. Referensi / Bukti Transfer</label>
                    <input type="text" name="reference_number" placeholder="Contoh: TRF-BCA-981723" class="form-input text-sm">
                </div>

                <div>
                    <label class="form-label">Catatan</label>
                    <input type="text" name="notes" placeholder="Pelunasan termin 1 proyek..." class="form-input text-sm">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openPayModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm bg-emerald-600 hover:bg-emerald-700">Catat Pembayaran & Jurnal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
