@extends('layouts.app')

@section('title', 'Laporan Aging Piutang (AR) & Hutang (AP)')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'ar' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Analisis Umur Piutang & Hutang (Aging Schedule)</h1>
            <p class="text-sm text-slate-500 mt-1">Klasifikasi keterlambatan pembayaran pelanggan (AR) dan jatuh tempo tagihan vendor supplier (AP).</p>
        </div>
        <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl">
            <button type="button" @click="activeTab = 'ar'" 
                    :class="activeTab === 'ar' ? 'bg-white shadow text-navy-950 font-bold' : 'text-slate-600 hover:text-navy-900'"
                    class="px-4 py-1.5 rounded-lg text-sm transition-all">
                Aging Piutang (AR)
            </button>
            <button type="button" @click="activeTab = 'ap'" 
                    :class="activeTab === 'ap' ? 'bg-white shadow text-navy-950 font-bold' : 'text-slate-600 hover:text-navy-900'"
                    class="px-4 py-1.5 rounded-lg text-sm transition-all">
                Aging Hutang (AP)
            </button>
        </div>
    </div>

    <!-- AGING PIUTANG (AR) TAB -->
    <div x-show="activeTab === 'ar'" class="space-y-6">
        <!-- Bucket Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <div class="card p-4">
                <span class="text-[11px] font-bold uppercase text-slate-400 block">Belum Jatuh Tempo</span>
                <span class="text-base font-extrabold text-emerald-600 font-mono mt-1 block">@rupiah($arAging['current'])</span>
            </div>
            <div class="card p-4">
                <span class="text-[11px] font-bold uppercase text-slate-400 block">1 - 30 Hari</span>
                <span class="text-base font-extrabold text-blue-600 font-mono mt-1 block">@rupiah($arAging['1_30'])</span>
            </div>
            <div class="card p-4">
                <span class="text-[11px] font-bold uppercase text-slate-400 block">31 - 60 Hari</span>
                <span class="text-base font-extrabold text-amber-600 font-mono mt-1 block">@rupiah($arAging['31_60'])</span>
            </div>
            <div class="card p-4">
                <span class="text-[11px] font-bold uppercase text-slate-400 block">61 - 90 Hari</span>
                <span class="text-base font-extrabold text-orange-600 font-mono mt-1 block">@rupiah($arAging['61_90'])</span>
            </div>
            <div class="card p-4">
                <span class="text-[11px] font-bold uppercase text-rose-500 block">> 90 Hari (Kritis)</span>
                <span class="text-base font-extrabold text-rose-600 font-mono mt-1 block">@rupiah($arAging['over_90'])</span>
            </div>
        </div>

        <!-- Detail Table -->
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-navy-950">Daftar Tagihan Piutang Pelanggan Terutang</h3>
                <span class="font-bold font-mono text-sm text-navy-950">Total Piutang: @rupiah($arAging['total'])</span>
            </div>
            <div class="table-container">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="table-th">No. Invoice</th>
                            <th class="table-th">Pelanggan</th>
                            <th class="table-th">Proyek</th>
                            <th class="table-th">Jatuh Tempo</th>
                            <th class="table-th text-center">Hari Terlewat</th>
                            <th class="table-th">Kelompok Umur</th>
                            <th class="table-th text-right">Sisa Piutang (IDR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($arAging['items'] as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="table-td font-mono text-xs font-bold text-navy-900">{{ $item['ref'] }}</td>
                                <td class="table-td font-medium text-slate-800">{{ $item['party'] }}</td>
                                <td class="table-td text-xs text-slate-600">{{ $item['project'] }}</td>
                                <td class="table-td whitespace-nowrap">@dateid($item['due_date'])</td>
                                <td class="table-td text-center font-mono text-xs font-bold {{ $item['days'] > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                    {{ $item['days'] > 0 ? $item['days'] . ' hr' : 'Lancar' }}
                                </td>
                                <td class="table-td">
                                    <span class="badge badge-neutral text-[10px]">{{ $item['bucket'] }}</span>
                                </td>
                                <td class="table-td text-right font-mono font-black text-navy-950 whitespace-nowrap">@rupiah($item['amount'])</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400">
                                    <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-emerald-400"></i>
                                    Tidak ada piutang yang belum tertagih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- AGING HUTANG (AP) TAB -->
    <div x-show="activeTab === 'ap'" class="space-y-6">
        <!-- Bucket Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <div class="card p-4">
                <span class="text-[11px] font-bold uppercase text-slate-400 block">Belum Jatuh Tempo</span>
                <span class="text-base font-extrabold text-emerald-600 font-mono mt-1 block">@rupiah($apAging['current'])</span>
            </div>
            <div class="card p-4">
                <span class="text-[11px] font-bold uppercase text-slate-400 block">1 - 30 Hari</span>
                <span class="text-base font-extrabold text-blue-600 font-mono mt-1 block">@rupiah($apAging['1_30'])</span>
            </div>
            <div class="card p-4">
                <span class="text-[11px] font-bold uppercase text-slate-400 block">31 - 60 Hari</span>
                <span class="text-base font-extrabold text-amber-600 font-mono mt-1 block">@rupiah($apAging['31_60'])</span>
            </div>
            <div class="card p-4">
                <span class="text-[11px] font-bold uppercase text-slate-400 block">61 - 90 Hari</span>
                <span class="text-base font-extrabold text-orange-600 font-mono mt-1 block">@rupiah($apAging['61_90'])</span>
            </div>
            <div class="card p-4">
                <span class="text-[11px] font-bold uppercase text-rose-500 block">> 90 Hari (Mendesak)</span>
                <span class="text-base font-extrabold text-rose-600 font-mono mt-1 block">@rupiah($apAging['over_90'])</span>
            </div>
        </div>

        <!-- Detail Table -->
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-navy-950">Daftar Tagihan Hutang Supplier Material Terutang</h3>
                <span class="font-bold font-mono text-sm text-navy-950">Total Hutang: @rupiah($apAging['total'])</span>
            </div>
            <div class="table-container">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="table-th">No. Faktur Vendor</th>
                            <th class="table-th">Supplier</th>
                            <th class="table-th">Proyek / PO</th>
                            <th class="table-th">Jatuh Tempo</th>
                            <th class="table-th text-center">Hari Terlewat</th>
                            <th class="table-th">Kelompok Umur</th>
                            <th class="table-th text-right">Sisa Hutang (IDR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($apAging['items'] as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="table-td font-mono text-xs font-bold text-navy-900">{{ $item['ref'] }}</td>
                                <td class="table-td font-medium text-slate-800">{{ $item['party'] }}</td>
                                <td class="table-td text-xs text-slate-600">{{ $item['project'] }}</td>
                                <td class="table-td whitespace-nowrap">@dateid($item['due_date'])</td>
                                <td class="table-td text-center font-mono text-xs font-bold {{ $item['days'] > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                    {{ $item['days'] > 0 ? $item['days'] . ' hr' : 'Lancar' }}
                                </td>
                                <td class="table-td">
                                    <span class="badge badge-neutral text-[10px]">{{ $item['bucket'] }}</span>
                                </td>
                                <td class="table-td text-right font-mono font-black text-navy-950 whitespace-nowrap">@rupiah($item['amount'])</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400">
                                    <i data-lucide="check-circle" class="w-10 h-10 mx-auto mb-2 text-emerald-400"></i>
                                    Tidak ada hutang supplier yang belum dibayar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
