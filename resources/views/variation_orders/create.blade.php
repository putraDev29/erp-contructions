@extends('layouts.app')

@section('title', 'Ajukan Variation Order Baru')
@section('page_title', 'Ajukan VO Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-navy-950">Ajukan Variation Order (VO)</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Catat perubahan lingkup kerja, dampak terhadap nilai kontrak, anggaran, dan target jadwal.</p>
        </div>
        <a href="{{ route('variation-orders.index') }}" class="btn-secondary">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8">
        <form method="POST" action="{{ route('variation-orders.store') }}" class="space-y-5 text-xs sm:text-sm">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="project_id" class="block font-semibold text-slate-700 mb-1.5">Proyek Konstruksi <span class="text-rose-500">*</span></label>
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
                    <label for="vo_date" class="block font-semibold text-slate-700 mb-1.5">Tanggal VO <span class="text-rose-500">*</span></label>
                    <input type="date" id="vo_date" name="vo_date" value="{{ old('vo_date', date('Y-m-d')) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500">
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block font-semibold text-slate-700 mb-1.5">Deskripsi Perubahan Pekerjaan <span class="text-rose-500">*</span></label>
                    <input type="text" id="description" name="description" value="{{ old('description') }}" required
                           placeholder="Contoh: Penambahan Kanopi Baja WF Depan Area Loading Dock"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500">
                </div>

                <div>
                    <label for="contract_amount_change" class="block font-semibold text-slate-700 mb-1.5">
                        Dampak Nilai Kontrak (+ / - IDR) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.01" id="contract_amount_change" name="contract_amount_change" value="{{ old('contract_amount_change', 0) }}" required
                           placeholder="Positif jika tambah, negatif jika kurang"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-bold focus:ring-2 focus:ring-navy-500">
                    <span class="text-[10px] text-slate-400 mt-1 block">Gunakan tanda minus (-) jika terjadi pekerjaan kurang.</span>
                </div>

                <div>
                    <label for="budget_amount_change" class="block font-semibold text-slate-700 mb-1.5">
                        Dampak Pagu Anggaran RAB (+ / - IDR) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.01" id="budget_amount_change" name="budget_amount_change" value="{{ old('budget_amount_change', 0) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-bold focus:ring-2 focus:ring-navy-500">
                </div>

                <div>
                    <label for="schedule_days_change" class="block font-semibold text-slate-700 mb-1.5">Dampak Waktu Pelaksanaan (Hari)</label>
                    <input type="number" id="schedule_days_change" name="schedule_days_change" value="{{ old('schedule_days_change', 0) }}"
                           placeholder="0 jika jadwal tetap, +14 jika ada perpanjangan"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500">
                </div>

                <div>
                    <label for="customer_approval_ref" class="block font-semibold text-slate-700 mb-1.5">No. Referensi Persetujuan Klien (Surat/BA)</label>
                    <input type="text" id="customer_approval_ref" name="customer_approval_ref" value="{{ old('customer_approval_ref') }}"
                           placeholder="Contoh: BA-VO-SL-012"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500">
                </div>

                <div class="sm:col-span-2">
                    <label for="reason" class="block font-semibold text-slate-700 mb-1.5">Alasan Perubahan Teknis / Lapangan</label>
                    <textarea id="reason" name="reason" rows="3" placeholder="Permintaan owner / penyesuaian kondisi tanah..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-navy-500">{{ old('reason') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('variation-orders.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Ajukan Variation Order</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
