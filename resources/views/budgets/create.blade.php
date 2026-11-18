@extends('layouts.app')

@section('title', 'Buat Dokumen RAB Baru')
@section('page_title', 'Buat RAB Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-navy-950">Buat Dokumen RAB Baru</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Atur target profit margin atau markup penawaran serta alokasi biaya tidak langsung.</p>
        </div>
        <a href="{{ route('budgets.index') }}" class="btn-secondary">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8">
        <form method="POST" action="{{ route('budgets.store') }}" class="space-y-5 text-xs sm:text-sm">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="project_id" class="block font-semibold text-slate-700 mb-1.5">Pilih Proyek Konstruksi <span class="text-rose-500">*</span></label>
                    <select id="project_id" name="project_id" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500">
                        <option value="">-- Pilih Proyek --</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ (old('project_id') == $p->id || ($selectedProject && $selectedProject->id == $p->id)) ? 'selected' : '' }}>
                                {{ $p->project_code }} - {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="title" class="block font-semibold text-slate-700 mb-1.5">Judul Dokumen RAB <span class="text-rose-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: Rencana Anggaran Biaya Pembangunan Tahap 1"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="overhead_percent" class="block font-semibold text-slate-700 mb-1.5">Biaya Overhead Kantor (%) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" min="0" max="50" id="overhead_percent" name="overhead_percent" value="{{ old('overhead_percent', '5.00') }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500">
                    </div>

                    <div>
                        <label for="contingency_percent" class="block font-semibold text-slate-700 mb-1.5">Cadangan Risiko / Contingency (%) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" min="0" max="50" id="contingency_percent" name="contingency_percent" value="{{ old('contingency_percent', '3.00') }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500">
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100" x-data="{ pricingMethod: '{{ old('pricing_method', 'margin') }}' }">
                    <label class="block font-bold text-slate-700 mb-2">Metode Penentuan Harga Penawaran</label>
                    
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <label class="p-3 rounded-xl border cursor-pointer flex items-center gap-2"
                               :class="pricingMethod === 'margin' ? 'border-construction-500 bg-construction-50/50 text-construction-900 font-bold' : 'border-slate-200 text-slate-600'">
                            <input type="radio" name="pricing_method" value="margin" x-model="pricingMethod" class="text-construction-600 focus:ring-construction-500">
                            <div>
                                <span class="block text-xs">Target Margin Laba</span>
                                <span class="text-[10px] text-slate-400 font-normal">Harga Jual = Biaya / (1 - Margin%)</span>
                            </div>
                        </label>

                        <label class="p-3 rounded-xl border cursor-pointer flex items-center gap-2"
                               :class="pricingMethod === 'markup' ? 'border-construction-500 bg-construction-50/50 text-construction-900 font-bold' : 'border-slate-200 text-slate-600'">
                            <input type="radio" name="pricing_method" value="markup" x-model="pricingMethod" class="text-construction-600 focus:ring-construction-500">
                            <div>
                                <span class="block text-xs">Target Markup</span>
                                <span class="text-[10px] text-slate-400 font-normal">Harga Jual = Biaya × (1 + Markup%)</span>
                            </div>
                        </label>
                    </div>

                    <div x-show="pricingMethod === 'margin'">
                        <label for="target_margin_percent" class="block font-semibold text-slate-700 mb-1.5">Target Profit Margin (%)</label>
                        <input type="number" step="0.01" min="0" max="99" id="target_margin_percent" name="target_margin_percent" value="{{ old('target_margin_percent', '15.00') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500">
                    </div>

                    <div x-show="pricingMethod === 'markup'" style="display: none;">
                        <label for="target_markup_percent" class="block font-semibold text-slate-700 mb-1.5">Target Markup Biaya (%)</label>
                        <input type="number" step="0.01" min="0" id="target_markup_percent" name="target_markup_percent" value="{{ old('target_markup_percent', '17.65') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('budgets.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan & Lanjutkan Rincian Item</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
