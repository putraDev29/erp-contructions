@extends('layouts.app')

@section('title', 'Kas & Rekening Bank')

@section('content')
<div class="space-y-6" x-data="{ openTrxModal: false, openTransferModal: false, trxType: 'in' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Kas & Rekening Bank</h1>
            <p class="text-sm text-slate-500 mt-1">Pengelolaan saldo kas besar, kas kecil (petty cash), rekening giro/bank, serta mutasi operasional.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" @click="trxType = 'in'; openTrxModal = true" class="btn-primary inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700">
                <i data-lucide="arrow-down-left" class="w-4 h-4"></i>
                <span>Kas Masuk (BKM)</span>
            </button>
            <button type="button" @click="trxType = 'out'; openTrxModal = true" class="btn-primary inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700">
                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                <span>Kas Keluar (BKK)</span>
            </button>
            <button type="button" @click="openTransferModal = true" class="btn-secondary inline-flex items-center gap-2">
                <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                <span>Transfer Antar Rekening</span>
            </button>
        </div>
    </div>

    <!-- Accounts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($accounts as $acc)
            <div class="card p-5 relative overflow-hidden group hover:border-construction-400 transition-colors">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-xs font-mono font-semibold text-slate-400 uppercase tracking-wider">{{ $acc->code }}</span>
                        <h3 class="text-lg font-bold text-navy-950 mt-0.5">{{ $acc->account_name }}</h3>
                        <p class="text-xs text-slate-500 font-mono mt-1">{{ $acc->account_number }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-navy-900 group-hover:bg-construction-50 group-hover:text-construction-600 transition-colors">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-baseline justify-between">
                    <span class="text-xs text-slate-500">Saldo Tersedia:</span>
                    <span class="text-lg font-extrabold text-navy-950 font-mono">@rupiah($acc->current_balance)</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Recent Transactions Table -->
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="font-bold text-navy-950 text-base">Riwayat Mutasi Kas & Bank Operasional</h3>
                <p class="text-xs text-slate-500">Seluruh arus masuk dan keluar tercatat otomatis dengan nomor bukti akuntansi.</p>
            </div>
            <span class="badge badge-neutral">{{ $transactions->total() }} Catatan Mutasi</span>
        </div>
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th">No. Bukti Transaksi</th>
                        <th class="table-th">Tanggal</th>
                        <th class="table-th">Rekening Asal / Tujuan</th>
                        <th class="table-th">Kategori Operasional</th>
                        <th class="table-th">Alokasi Proyek</th>
                        <th class="table-th text-right">Nominal (IDR)</th>
                        <th class="table-th">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td">
                                <span class="font-mono text-xs font-bold text-navy-900 block">{{ $trx->transaction_number }}</span>
                                <span class="text-[10px] {{ $trx->type === 'in' ? 'text-emerald-600 font-bold' : 'text-rose-600 font-bold' }}">
                                    {{ $trx->type === 'in' ? 'BUKTI KAS MASUK (BKM)' : 'BUKTI KAS KELUAR (BKK)' }}
                                </span>
                            </td>
                            <td class="table-td whitespace-nowrap font-medium text-slate-700">@dateid($trx->transaction_date)</td>
                            <td class="table-td">
                                <div class="font-semibold text-xs text-slate-900">{{ $trx->bankAccount?->account_name }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $trx->bankAccount?->account_number }}</div>
                            </td>
                            <td class="table-td">
                                <span class="badge badge-neutral text-xs">{{ $trx->category }}</span>
                            </td>
                            <td class="table-td">
                                @if($trx->project)
                                    <span class="font-medium text-xs text-navy-900 block">{{ $trx->project->name }}</span>
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $trx->project->code }}</span>
                                @else
                                    <span class="badge badge-neutral text-[11px]">Overhead Kantor</span>
                                @endif
                            </td>
                            <td class="table-td text-right font-mono font-black whitespace-nowrap {{ $trx->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $trx->type === 'in' ? '+' : '-' }}@rupiah($trx->amount)
                            </td>
                            <td class="table-td text-xs text-slate-600 max-w-xs">{{ $trx->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <i data-lucide="wallet" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada riwayat transaksi kas/bank.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- MODAL TRANSAKSI KAS MASUK / KELUAR -->
    <div x-show="openTrxModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-6" @click.away="openTrxModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950" x-text="trxType === 'in' ? 'Catat Kas Masuk (BKM)' : 'Catat Kas Keluar (BKK)'"></h3>
                    <p class="text-xs text-slate-500">Transaksi penerimaan atau pengeluaran operasional.</p>
                </div>
                <button type="button" @click="openTrxModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('finance.cash-transaction.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="type" :value="trxType">

                <div>
                    <label class="form-label required">Rekening Kas / Bank</label>
                    <select name="bank_account_id" class="form-select text-sm" required>
                        <option value="">-- Pilih Rekening --</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">
                                {{ $acc->account_name }} (Saldo: Rp {{ number_format($acc->current_balance, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Tanggal Transaksi</label>
                        <input type="date" name="transaction_date" class="form-input text-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="form-label required">Nominal (IDR)</label>
                        <input type="number" step="1000" min="1000" name="amount" class="form-input text-sm font-mono font-bold" placeholder="1000000" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Kategori Transaksi</label>
                        <select name="category" class="form-select text-sm" required>
                            <option value="Operasional Kantor">Operasional Kantor</option>
                            <option value="Bahan Bakar & Kendaraan">Bahan Bakar & Kendaraan</option>
                            <option value="Sewa Alat Berat">Sewa Alat Berat</option>
                            <option value="Perizinan & Legal">Perizinan & Legal</option>
                            <option value="Konsumsi Lapangan">Konsumsi Lapangan</option>
                            <option value="Pendapatan Lain-lain">Pendapatan Lain-lain</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Proyek Terkait</label>
                        <select name="project_id" class="form-select text-sm">
                            <option value="">-- Overhead Kantor (Bukan Proyek) --</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Keterangan / Uraian</label>
                    <textarea name="notes" rows="2" class="form-input text-sm" placeholder="Rincian pengeluaran / pemasukan..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openTrxModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TRANSFER ANTAR REKENING -->
    <div x-show="openTransferModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-6" @click.away="openTransferModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Transfer Internal Antar Rekening</h3>
                    <p class="text-xs text-slate-500">Pemindahan dana kas/bank internal perusahaan (tidak menambah beban/pendapatan).</p>
                </div>
                <button type="button" @click="openTransferModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('finance.transfer.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Dari Rekening</label>
                        <select name="from_bank_account_id" class="form-select text-sm" required>
                            <option value="">-- Pilih Asal --</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">
                                    {{ $acc->account_name }} (Rp {{ number_format($acc->current_balance, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label required">Ke Rekening</label>
                        <select name="to_bank_account_id" class="form-select text-sm" required>
                            <option value="">-- Pilih Tujuan --</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->account_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Tanggal Transfer</label>
                        <input type="date" name="transfer_date" class="form-input text-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="form-label required">Nominal Transfer (IDR)</label>
                        <input type="number" step="1000" min="1000" name="amount" class="form-input text-sm font-mono font-bold" placeholder="5000000" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">Biaya Administrasi Bank (IDR)</label>
                    <input type="number" step="500" min="0" name="fee" value="0" class="form-input text-sm font-mono">
                </div>

                <div>
                    <label class="form-label">Catatan</label>
                    <input type="text" name="notes" placeholder="Misal: Top up kas kecil lapangan" class="form-input text-sm">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openTransferModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Eksekusi Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
