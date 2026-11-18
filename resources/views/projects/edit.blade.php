@extends('layouts.app')

@section('title', 'Edit Proyek: ' . $project->project_code)
@section('page_title', 'Edit Proyek')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-navy-950">Edit Proyek: {{ $project->project_code }}</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Perbarui data kontrak, jadwal waktu, penanggung jawab, dan status proyek.</p>
        </div>
        <a href="{{ route('projects.show', $project) }}" class="btn-secondary">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Detail</span>
        </a>
    </div>

    <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-7 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Proyek Konstruksi</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500">
                </div>

                <div>
                    <label for="customer_id" class="block text-xs font-semibold text-slate-700 mb-1.5">Pelanggan / Pemilik Proyek</label>
                    <select id="customer_id" name="customer_id" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500">
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id', $project->customer_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->code }} - {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-700 mb-1.5">Jenis Pekerjaan</label>
                    <select id="type" name="type" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500">
                        <option value="residential" {{ old('type', $project->type) == 'residential' ? 'selected' : '' }}>Rumah Tinggal</option>
                        <option value="commercial" {{ old('type', $project->type) == 'commercial' ? 'selected' : '' }}>Gedung & Bangunan Komersial</option>
                        <option value="steel_construction" {{ old('type', $project->type) == 'steel_construction' ? 'selected' : '' }}>Konstruksi Baja</option>
                        <option value="renovation" {{ old('type', $project->type) == 'renovation' ? 'selected' : '' }}>Renovasi</option>
                        <option value="civil_works" {{ old('type', $project->type) == 'civil_works' ? 'selected' : '' }}>Pekerjaan Sipil</option>
                        <option value="others" {{ old('type', $project->type) == 'others' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">Status Proyek</label>
                    <select id="status" name="status" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 font-bold focus:ring-2 focus:ring-navy-500">
                        @foreach(['draft', 'tender', 'contract', 'preparation', 'in_progress', 'completed', 'on_hold', 'cancelled'] as $st)
                            <option value="{{ $st }}" {{ old('status', $project->status) == $st ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $st)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="contract_amount" class="block text-xs font-semibold text-slate-700 mb-1.5">Nilai Kontrak Dasar (IDR)</label>
                    <input type="number" step="0.01" id="contract_amount" name="contract_amount" value="{{ old('contract_amount', $project->contract_amount) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 font-bold focus:ring-2 focus:ring-navy-500">
                </div>

                <div>
                    <label for="approved_budget_amount" class="block text-xs font-semibold text-slate-700 mb-1.5">Pagu Anggaran RAB Dasar (IDR)</label>
                    <input type="number" step="0.01" id="approved_budget_amount" name="approved_budget_amount" value="{{ old('approved_budget_amount', $project->approved_budget_amount) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 font-bold focus:ring-2 focus:ring-navy-500">
                </div>

                <div>
                    <label for="target_margin_percent" class="block text-xs font-semibold text-slate-700 mb-1.5">Target Profit Margin (%)</label>
                    <input type="number" step="0.01" id="target_margin_percent" name="target_margin_percent" value="{{ old('target_margin_percent', $project->target_margin_percent) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500">
                </div>

                <div>
                    <label for="start_date" class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Mulai Proyek</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500">
                </div>

                <div>
                    <label for="deadline_date" class="block text-xs font-semibold text-slate-700 mb-1.5">Target Selesai (Deadline)</label>
                    <input type="date" id="deadline_date" name="deadline_date" value="{{ old('deadline_date', $project->deadline_date?->format('Y-m-d')) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500">
                </div>

                <div>
                    <label for="project_manager_id" class="block text-xs font-semibold text-slate-700 mb-1.5">Project Manager</label>
                    <select id="project_manager_id" name="project_manager_id"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500">
                        <option value="">-- Pilih Project Manager --</option>
                        @foreach($projectManagers as $pm)
                            <option value="{{ $pm->id }}" {{ old('project_manager_id', $project->project_manager_id) == $pm->id ? 'selected' : '' }}>
                                {{ $pm->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="site_manager_id" class="block text-xs font-semibold text-slate-700 mb-1.5">Site Manager / Mandor</label>
                    <select id="site_manager_id" name="site_manager_id"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500">
                        <option value="">-- Pilih Site Manager --</option>
                        @foreach($siteManagers as $sm)
                            <option value="{{ $sm->id }}" {{ old('site_manager_id', $project->site_manager_id) == $sm->id ? 'selected' : '' }}>
                                {{ $sm->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Proyek</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500">{{ old('notes', $project->notes) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('projects.show', $project) }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
