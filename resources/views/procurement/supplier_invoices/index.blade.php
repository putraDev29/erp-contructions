@extends('layouts.app')

@section('title', 'Hutang & Tagihan Supplier (Accounts Payable)')

@section('content')
<div class="space-y-6" x-data="{ openPayModal: false, selectedInvId: null, selectedInvNo: '', selectedVendor: '', remainingAmount: 0 }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Hutang & Faktur Supplier (AP)</h1>
            <p class="text-sm text-slate-500 mt-1">Verifikasi tagihan material vendor, jadwal jatuh tempo, dan pelunasan pembayaran via kas/bank.</p>
        </div>
        <a href="{{ route('supplier-invoices.create') }}" class="btn-primary inline-flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Catat Faktur Masuk</span>
        </a>
    </div>

    <!-- KPI Summary Cards -->
    @php
        $totalAP = $invoices->sum('total_amount');
        $remainingAP = $invoices->sum('remaining_amount');
        $paidAP = $invoices->sum('paid_amount');
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card p-5">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Tagihan Masuk</span>
            <h3 class="text-xl font-black text-navy-950 mt-1">@rupiah($totalAP)</h3>
            <p class="text-xs text-slate-400 mt-0.5">Seluruh faktur vendor tercatat</p>
        </div>
        <div class="card p-5">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Telah Dibayarkan</span>
            <h3 class="text-xl font-black text-emerald-600 mt-1">@rupiah($paidAP)</h3>
            <p class="text-xs text-emerald-500 mt-0.5">Sudah keluar dari kas/bank</p>
        </div>
        <div class="card p-5">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Sisa Kewajiban Hutang</span>
            <h3 class="text-xl font-black text-rose-600 mt-1">@rupiah($remainingAP)</h3>
            <p class="text-xs text-rose-500 mt-0.5">Menunggu pelunasan</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card p-4">
        <form method="GET" action="{{ route('supplier-invoices.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="form-label text-xs">Pilih Rekanan Supplier</label>
                <select name="supplier_id" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Rekanan Supplier</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Status Pembayaran</label>
                <select name="status" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Lunas Sebagian</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas Penuh</option>
                </select>
            </div>
            <div class="flex items-end">
                <a href="{{ route('supplier-invoices.index') }}" class="btn-secondary text-sm">Reset Filter</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Invoice ERP</th>
                        <th>No. Faktur Vendor</th>
                        <th>Supplier</th>
                        <th>No. PO / Proyek</th>
                        <th>Jatuh Tempo</th>
                        <th class="text-right">Total Tagihan</th>
                        <th class="text-right">Sisa Hutang</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $inv)
                        @php
                            $isOverdue = $inv->remaining_amount > 0 && strtotime($inv->due_date) < time();
                        @endphp
                        <tr>
                            <td>
                                <div class="font-bold text-navy-900 font-mono">{{ $inv->invoice_number }}</div>
                                <div class="text-[11px] text-slate-400">@dateid($inv->invoice_date)</div>
                            </td>
                            <td class="font-mono text-xs text-slate-700 font-semibold">{{ $inv->supplier_invoice_ref }}</td>
                            <td>
                                <div class="font-medium text-slate-800">{{ $inv->supplier?->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $inv->supplier?->contact_person }}</div>
                            </td>
                            <td>
                                @if($inv->purchaseOrder)
                                    <div class="text-xs font-mono text-navy-700 font-semibold">{{ $inv->purchaseOrder->po_number }}</div>
                                    <div class="text-[11px] text-slate-400 truncate max-w-xs">{{ $inv->purchaseOrder->project?->name }}</div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="{{ $isOverdue ? 'text-rose-600 font-bold' : 'text-slate-700' }}">
                                    @dateid($inv->due_date)
                                </div>
                                @if($isOverdue)
                                    <span class="text-[10px] bg-rose-100 text-rose-700 px-1.5 py-0.5 rounded font-semibold">Jatuh Tempo!</span>
                                @endif
                            </td>
                            <td class="text-right font-mono font-medium text-xs">@rupiah($inv->total_amount)</td>
                            <td class="text-right font-mono font-bold text-xs {{ $inv->remaining_amount > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                @rupiah($inv->remaining_amount)
                            </td>
                            <td class="text-center">
                                @if($inv->status === 'paid')
                                    <span class="badge-success text-[10px]">Lunas</span>
                                @elseif($inv->status === 'partially_paid')
                                    <span class="badge-primary text-[10px]">Dibayar Sebagian</span>
                                @else
                                    <span class="badge-warning text-[10px]">Menunggu Bayar</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($inv->remaining_amount > 0)
                                    <button type="button" 
                                            @click="selectedInvId = {{ $inv->id }}; selectedInvNo = '{{ $inv->invoice_number }} ({{ $inv->supplier_invoice_ref }})'; selectedVendor = '{{ $inv->supplier?->name }}'; remainingAmount = {{ $inv->remaining_amount }}; openPayModal = true"
                                            class="btn-accent text-xs px-2.5 py-1 inline-flex items-center gap-1">
                                        <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                        <span>Bayar Hutang</span>
                                    </button>
                                @else
                                    <span class="text-xs text-emerald-600 font-semibold inline-flex items-center gap-1">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                        Lunas
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-12 text-slate-400">
                                <i data-lucide="file-spreadsheet" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada faktur tagihan supplier yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $invoices->links() }}
        </div>
    </div>

    <!-- MODAL PEMBAYARAN HUTANG SUPPLIER -->
    <div x-show="openPayModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-5" @click.away="openPayModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Pelunasan Hutang Supplier</h3>
                    <p class="text-xs text-slate-500" x-text="selectedVendor + ' • ' + selectedInvNo"></p>
                </div>
                <button type="button" @click="openPayModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form :action="'/supplier-invoices/' + selectedInvId + '/payment'" method="POST" class="space-y-4">
                @csrf
                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200/80 flex items-center justify-between text-xs">
                    <span class="text-amber-800 font-medium">Sisa Hutang Faktur:</span>
                    <span class="font-bold font-mono text-amber-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(remainingAmount)"></span>
                </div>

                <div>
                    <label class="form-label required">Rekening Sumber Pembayaran (Kas / Bank)</label>
                    <select name="bank_account_id" class="form-select text-sm" required>
                        <option value="">-- Pilih Rekening Pengeluaran --</option>
                        @foreach($bankAccounts as $bank)
                            <option value="{{ $bank->id }}">
                                {{ $bank->account_name }} ({{ $bank->bank_name }}) - Saldo: @rupiah($bank->current_balance)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Tanggal Pembayaran</label>
                        <input type="date" name="payment_date" class="form-input text-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="form-label required">Metode Pembayaran</label>
                        <select name="payment_method" class="form-select text-sm" required>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Giro / Cek">Giro / Cek</option>
                            <option value="Kas Tunai">Kas Tunai</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label required">Jumlah yang Dibayarkan (IDR)</label>
                    <input type="number" step="1000" min="1000" :max="remainingAmount" name="amount" :value="remainingAmount" class="form-input text-sm font-mono font-bold" required>
                    <p class="text-[11px] text-slate-400 mt-1">Dukung pembayaran penuh atau cicilan parsial.</p>
                </div>

                <div>
                    <label class="form-label">Catatan / Bukti Transfer</label>
                    <textarea name="notes" rows="2" class="form-input text-sm" placeholder="Contoh: Transfer via BCA KlikBisnis ref: 81293"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="openPayModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-accent text-sm">Proses & Posting Jurnal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
