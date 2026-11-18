@extends('layouts.app')

@section('title', 'Detail RAB: ' . $budget->rab_number)
@section('page_title', 'Rencana Anggaran Biaya')

@section('content')
<div class="space-y-6" x-data="{ newDivisionModal: false, newItemModal: false, reviseModal: false, activeDivisionId: null }">

    <!-- Top Banner -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-lg bg-navy-950 text-white">
                        {{ $budget->rab_number }}
                    </span>
                    <span class="badge badge-neutral text-xs">Versi {{ $budget->version }}</span>
                    @php
                        $stBadge = match($budget->status) {
                            'approved' => 'badge-success',
                            'revised' => 'badge-info',
                            'rejected' => 'badge-danger',
                            'submitted' => 'badge-warning',
                            default => 'badge-neutral',
                        };
                    @endphp
                    <span class="badge {{ $stBadge }}">
                        {{ ucfirst($budget->status) }}
                    </span>
                </div>
                <h1 class="text-lg sm:text-2xl font-black text-navy-950 mt-2">{{ $budget->title }}</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Proyek: <strong class="text-slate-800">{{ $budget->project?->name }}</strong> ({{ $budget->project?->project_code }})
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-2 self-start md:self-auto">
                <a href="{{ route('budgets.export-pdf', $budget) }}" class="btn-secondary text-xs">
                    <i data-lucide="file-down" class="w-4 h-4 text-rose-500"></i>
                    <span>Cetak PDF</span>
                </a>

                @if($budget->status === 'draft')
                    <button @click="newDivisionModal = true" class="btn-secondary text-xs">
                        <i data-lucide="folder-plus" class="w-4 h-4 text-navy-800"></i>
                        <span>Tambah Divisi</span>
                    </button>
                    <form method="POST" action="{{ route('budgets.approve', $budget) }}" class="inline" onsubmit="return confirm('Setujui dokumen RAB ini?')">
                        @csrf
                        <button type="submit" class="btn-primary text-xs">
                            <i data-lucide="check-check" class="w-4 h-4"></i>
                            <span>Setujui RAB</span>
                        </button>
                    </form>
                @elseif($budget->status === 'approved')
                    <button @click="reviseModal = true" class="btn-accent text-xs">
                        <i data-lucide="git-branch" class="w-4 h-4"></i>
                        <span>Buat Revisi (Versi Baru)</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- 5 Summary KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mt-5 text-xs">
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                <span class="text-slate-400 font-medium block">Total Biaya Langsung</span>
                <span class="font-extrabold text-slate-800 text-sm mt-0.5 block truncate">@rupiah($budget->direct_cost)</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                <span class="text-slate-400 font-medium block">Overhead ({{ $budget->overhead_percent }}%)</span>
                <span class="font-bold text-slate-700 text-sm mt-0.5 block truncate">@rupiah($budget->overhead_cost)</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                <span class="text-slate-400 font-medium block">Contingency ({{ $budget->contingency_percent }}%)</span>
                <span class="font-bold text-slate-700 text-sm mt-0.5 block truncate">@rupiah($budget->contingency_cost)</span>
            </div>
            <div class="bg-navy-900 text-white p-3 rounded-xl shadow-xs">
                <span class="text-slate-300 font-medium block">Total Anggaran (RAB)</span>
                <span class="font-extrabold text-white text-sm mt-0.5 block truncate">@rupiah($budget->total_rab_amount)</span>
            </div>
            <div class="bg-construction-50 border border-construction-200 p-3 rounded-xl">
                <span class="text-construction-800 font-semibold block">Harga Penawaran Klien</span>
                <span class="font-extrabold text-construction-700 text-sm mt-0.5 block truncate">@rupiah($budget->offered_price)</span>
            </div>
        </div>
    </div>

    <!-- Divisi & Items Detailed Table -->
    <div class="space-y-6">
        @forelse($budget->divisions as $div)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="font-mono font-bold text-xs bg-navy-900 text-white px-2 py-0.5 rounded">
                            {{ $div->division_code }}
                        </span>
                        <h2 class="text-sm font-bold text-slate-800">{{ $div->name }}</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold text-slate-500">
                            Subtotal: <strong class="text-navy-950">@rupiah($div->total_division_cost)</strong>
                        </span>
                        @if($budget->status === 'draft')
                            <button @click="activeDivisionId = {{ $div->id }}; newItemModal = true" 
                                    class="btn-secondary text-[11px] py-1 px-2.5">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Tambah Item</span>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr>
                                <th class="table-th">Kode</th>
                                <th class="table-th">Uraian Pekerjaan & Spesifikasi</th>
                                <th class="table-th text-center">Volume</th>
                                <th class="table-th text-right">Material</th>
                                <th class="table-th text-right">Upah Tukang</th>
                                <th class="table-th text-right">Alat & Subkon</th>
                                <th class="table-th text-right">Harga Satuan</th>
                                <th class="table-th text-right">Total Biaya</th>
                                <th class="table-th text-right">Penawaran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($div->items as $item)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="table-td font-mono font-bold text-navy-900 whitespace-nowrap">{{ $item->item_code }}</td>
                                    <td class="table-td">
                                        <span class="font-semibold text-slate-800 block">{{ $item->name }}</span>
                                        <span class="text-[10px] text-slate-400 block">{{ $item->spec ?: '-' }}</span>
                                    </td>
                                    <td class="table-td text-center whitespace-nowrap font-medium text-slate-700">
                                        {{ $item->volume }} {{ $item->unit }}
                                    </td>
                                    <td class="table-td text-right text-slate-600">@rupiah($item->unit_material_price)</td>
                                    <td class="table-td text-right text-slate-600">@rupiah($item->unit_labor_price)</td>
                                    <td class="table-td text-right text-slate-600">@rupiah($item->unit_equipment_price + $item->unit_subcon_price)</td>
                                    <td class="table-td text-right font-semibold text-slate-900">@rupiah($item->direct_unit_cost)</td>
                                    <td class="table-td text-right font-bold text-navy-950">@rupiah($item->total_direct_cost)</td>
                                    <td class="table-td text-right font-bold text-construction-700">@rupiah($item->total_offered_price)</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="p-6 text-center text-slate-400">
                                        Belum ada item pekerjaan di divisi ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-12 text-center text-slate-400">
                <i data-lucide="calculator" class="w-12 h-12 mx-auto mb-2 text-slate-300"></i>
                <p class="font-semibold text-sm">Belum ada divisi pekerjaan yang dibuat pada dokumen RAB ini.</p>
                @if($budget->status === 'draft')
                    <button @click="newDivisionModal = true" class="btn-primary mt-4">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Tambah Divisi Pekerjaan Pertama</span>
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Modal: Tambah Divisi Baru -->
    <div x-show="newDivisionModal" 
         class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" 
         style="display: none;">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200" @click.away="newDivisionModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="font-extrabold text-navy-950 text-sm sm:text-base">Tambah Divisi Pekerjaan</h3>
                <button @click="newDivisionModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('budgets.divisions.store', $budget) }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Kode Divisi</label>
                    <input type="text" name="division_code" required placeholder="Contoh: DIV-01, DIV-02" 
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nama Divisi</label>
                    <input type="text" name="name" required placeholder="Contoh: Pekerjaan Struktur Beton Bertulang" 
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                </div>
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="newDivisionModal = false" class="btn-secondary text-xs">Batal</button>
                    <button type="submit" class="btn-primary text-xs">Simpan Divisi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Tambah Item Pekerjaan -->
    <div x-show="newItemModal" 
         class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" 
         style="display: none;">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200" @click.away="newItemModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="font-extrabold text-navy-950 text-sm sm:text-base">Tambah Item Pekerjaan</h3>
                <button @click="newItemModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form x-bind:action="'/budgets/divisions/' + activeDivisionId + '/items'" method="POST" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Kode Item</label>
                        <input type="text" name="item_code" required placeholder="Contoh: ITM-01.01" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Pekerjaan</label>
                        <input type="text" name="name" required placeholder="Contoh: Cor Kolom K-300" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Spesifikasi Teknis</label>
                    <input type="text" name="spec" placeholder="Contoh: Readymix K-300 + Pembesian Ulir D16" 
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Volume Pekerjaan</label>
                        <input type="number" step="0.0001" name="volume" value="1" required 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Satuan</label>
                        <input type="text" name="unit" value="m3" required 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Harga Satuan Material (IDR)</label>
                        <input type="number" step="0.01" name="unit_material_price" value="0" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Harga Satuan Upah (IDR)</label>
                        <input type="number" step="0.01" name="unit_labor_price" value="0" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Harga Satuan Alat (IDR)</label>
                        <input type="number" step="0.01" name="unit_equipment_price" value="0" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Harga Satuan Subkon (IDR)</label>
                        <input type="number" step="0.01" name="unit_subcon_price" value="0" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="newItemModal = false" class="btn-secondary text-xs">Batal</button>
                    <button type="submit" class="btn-primary text-xs">Simpan Item Pekerjaan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Revisi RAB -->
    <div x-show="reviseModal" 
         class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" 
         style="display: none;">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200" @click.away="reviseModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="font-extrabold text-navy-950 text-sm sm:text-base">Buat Revisi Dokumen RAB</h3>
                <button @click="reviseModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('budgets.revise', $budget) }}" class="space-y-4 text-xs">
                @csrf
                <p class="text-slate-600">
                    Pembuatan revisi akan menduplikasi RAB versi {{ $budget->version }} menjadi versi {{ $budget->version + 1 }} dengan status DRAFT untuk penyesuaian harga dan volume.
                </p>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Alasan Revisi Anggaran <span class="text-rose-500">*</span></label>
                    <textarea name="revision_notes" rows="3" required placeholder="Penyesuaian kenaikan harga baja / perubahan spesifikasi..." 
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="reviseModal = false" class="btn-secondary text-xs">Batal</button>
                    <button type="submit" class="btn-accent text-xs">Buat Revisi Baru</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
