@extends('layouts.app')

@section('title', 'Tagihan Pelanggan (Accounts Receivable)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Piutang & Tagihan Pelanggan (AR)</h1>
            <p class="text-sm text-slate-500 mt-1">Pengelolaan invoice termin, uang muka, milestone progres, dan pelunasan pembayaran pelanggan.</p>
        </div>
        <a href="{{ route('customer-invoices.create') }}" class="btn-primary inline-flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Terbitkan Invoice Termin</span>
        </a>
    </div>

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5 border-l-4 border-navy-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Invoice</p>
                    <p class="text-2xl font-black text-navy-950 mt-1">{{ $invoices->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-navy-50 text-navy-700 flex items-center justify-center">
                    <i data-lucide="receipt" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Total Piutang Belum Cair</p>
                    <p class="text-xl font-black text-amber-950 mt-1 font-mono">@rupiah(\App\Models\CustomerInvoice::sum('remaining_amount'))</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Sudah Diterima (Lunas)</p>
                    <p class="text-xl font-black text-emerald-950 mt-1 font-mono">@rupiah(\App\Models\CustomerInvoice::where('status', 'paid')->sum('net_amount'))</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="card p-5 border-l-4 border-rose-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-rose-700">Piutang Jatuh Tempo</p>
                    <p class="text-2xl font-black text-rose-950 mt-1">
                        {{ \App\Models\CustomerInvoice::where('remaining_amount', '>', 0)->where('due_date', '<', date('Y-m-d'))->count() }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card p-4">
        <form method="GET" action="{{ route('customer-invoices.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="form-label text-xs">Pilih Proyek</label>
                <select name="project_id" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Proyek</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->code }} - {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Status Pembayaran</label>
                <select name="status" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Dibayar Parsial</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            <div class="flex items-end">
                <a href="{{ route('customer-invoices.index') }}" class="btn-secondary text-sm">Reset Filter</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th">No. Invoice</th>
                        <th class="table-th">Pelanggan</th>
                        <th class="table-th">Proyek</th>
                        <th class="table-th">Termin / Milestone</th>
                        <th class="table-th">Jatuh Tempo</th>
                        <th class="table-th text-right">Total Tagihan</th>
                        <th class="table-th text-right">Sisa Piutang</th>
                        <th class="table-th">Status</th>
                        <th class="table-th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $inv)
                        @php
                            $isOverdue = $inv->remaining_amount > 0 && strtotime($inv->due_date) < time();
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td">
                                <a href="{{ route('customer-invoices.show', $inv) }}" class="font-bold text-navy-900 hover:text-construction-600 font-mono">
                                    {{ $inv->invoice_number }}
                                </a>
                                <div class="text-[11px] text-slate-400">@dateid($inv->invoice_date)</div>
                            </td>
                            <td class="table-td">
                                <div class="font-semibold text-slate-900">{{ $inv->customer?->name }}</div>
                            </td>
                            <td class="table-td">
                                <div class="font-medium text-slate-800">{{ $inv->project?->name }}</div>
                                <div class="text-xs font-mono text-slate-400">{{ $inv->project?->code }}</div>
                            </td>
                            <td class="table-td">
                                <div class="text-xs font-bold text-navy-950">{{ $inv->milestone_description }}</div>
                                @if($inv->progress_percent > 0)
                                    <div class="text-[11px] text-construction-600 font-semibold mt-0.5">Progres Fisik: {{ $inv->progress_percent }}%</div>
                                @endif
                            </td>
                            <td class="table-td whitespace-nowrap">
                                <div class="{{ $isOverdue ? 'text-rose-600 font-bold' : 'text-slate-700 font-medium' }}">
                                    @dateid($inv->due_date)
                                </div>
                                @if($isOverdue)
                                    <span class="inline-block mt-0.5 text-[10px] bg-rose-100 text-rose-700 px-2 py-0.5 rounded font-bold">Jatuh Tempo!</span>
                                @endif
                            </td>
                            <td class="table-td text-right font-mono font-medium text-slate-700 whitespace-nowrap">@rupiah($inv->net_amount)</td>
                            <td class="table-td text-right font-mono font-black whitespace-nowrap {{ $inv->remaining_amount > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                @rupiah($inv->remaining_amount)
                            </td>
                            <td class="table-td">
                                @if($inv->status === 'paid')
                                    <span class="badge badge-success"><i data-lucide="check" class="w-3 h-3"></i> Lunas</span>
                                @elseif($inv->status === 'partial')
                                    <span class="badge badge-primary"><i data-lucide="clock" class="w-3 h-3"></i> Parsial</span>
                                @else
                                    <span class="badge badge-warning"><i data-lucide="alert-circle" class="w-3 h-3"></i> Belum Bayar</span>
                                @endif
                            </td>
                            <td class="table-td text-right whitespace-nowrap">
                                <a href="{{ route('customer-invoices.show', $inv) }}" class="btn-primary btn-xs">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Detail / Bayar</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-12 text-slate-400">
                                <i data-lucide="receipt" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada invoice pelanggan yang diterbitkan.
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
</div>
@endsection
