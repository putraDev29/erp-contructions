@extends('layouts.app')

@section('title', 'Buat Proyek Baru')
@section('page_title', 'Registrasi Proyek Baru')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-navy-950">Buat Proyek Konstruksi Baru</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Isi seluruh data kontrak, klien, estimasi anggaran, dan jadwal pelaksanaan.</p>
        </div>
        <a href="{{ route('projects.index') }}" class="btn-secondary">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    <form method="POST" action="{{ route('projects.store') }}" class="space-y-6">
        @csrf

        <!-- Section 1: Informasi Dasar & Klien -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-7 space-y-5">
            <h2 class="text-sm font-bold text-navy-950 uppercase tracking-wider pb-3 border-b border-slate-100 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-construction-600"></i>
                <span>1. Identitas & Informasi Proyek</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Proyek Konstruksi <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Pembangunan Gudang Logistik Rangka Baja Karawang 2.400 m2"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">
                </div>

                <div>
                    <label for="customer_id" class="block text-xs font-semibold text-slate-700 mb-1.5">Pelanggan / Pemilik Proyek (Owner) <span class="text-rose-500">*</span></label>
                    <select id="customer_id" name="customer_id" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->code }} - {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-700 mb-1.5">Jenis Pekerjaan Konstruksi <span class="text-rose-500">*</span></label>
                    <select id="type" name="type" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">
                        <option value="residential" {{ old('type') == 'residential' ? 'selected' : '' }}>Pembangunan Rumah Tinggal</option>
                        <option value="commercial" {{ old('type') == 'commercial' ? 'selected' : '' }}>Gedung & Bangunan Komersial</option>
                        <option value="steel_construction" {{ old('type') == 'steel_construction' ? 'selected' : '' }}>Konstruksi & Fabrikasi Baja</option>
                        <option value="renovation" {{ old('type') == 'renovation' ? 'selected' : '' }}>Renovasi Bangunan</option>
                        <option value="civil_works" {{ old('type') == 'civil_works' ? 'selected' : '' }}>Pekerjaan Sipil & Infrastruktur</option>
                        <option value="others" {{ old('type') == 'others' ? 'selected' : '' }}>Proyek Konstruksi Lainnya</option>
                    </select>
                </div>

                <div>
                    <label for="location" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Lokasi Site Proyek</label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}"
                           placeholder="Contoh: Kawasan Industri KIIC Lot C-4, Karawang"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">
                </div>

                <div>
                    <label for="coordinates" class="block text-xs font-semibold text-slate-700 mb-1.5">Koordinat GPS (Opsional)</label>
                    <input type="text" id="coordinates" name="coordinates" value="{{ old('coordinates') }}"
                           placeholder="Contoh: -6.3421, 107.2891"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">
                </div>
            </div>
        </div>

        <!-- Section 2: Finansial & Kontrak -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-7 space-y-5">
            <h2 class="text-sm font-bold text-navy-950 uppercase tracking-wider pb-3 border-b border-slate-100 flex items-center gap-2">
                <i data-lucide="coins" class="w-4 h-4 text-construction-600"></i>
                <span>2. Nilai Kontrak, Anggaran & Pembayaran</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label for="contract_amount" class="block text-xs font-semibold text-slate-700 mb-1.5">Total Nilai Kontrak (IDR) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" id="contract_amount" name="contract_amount" value="{{ old('contract_amount') }}" required
                           placeholder="Contoh: 3850000000"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 font-bold focus:ring-2 focus:ring-navy-500 focus:bg-white">
                </div>

                <div>
                    <label for="approved_budget_amount" class="block text-xs font-semibold text-slate-700 mb-1.5">Pagu Anggaran RAB Disetujui (IDR)</label>
                    <input type="number" step="0.01" id="approved_budget_amount" name="approved_budget_amount" value="{{ old('approved_budget_amount') }}"
                           placeholder="Contoh: 3200000000"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 font-bold focus:ring-2 focus:ring-navy-500 focus:bg-white">
                </div>

                <div>
                    <label for="target_margin_percent" class="block text-xs font-semibold text-slate-700 mb-1.5">Target Profit Margin (%)</label>
                    <input type="number" step="0.01" id="target_margin_percent" name="target_margin_percent" value="{{ old('target_margin_percent', '15.00') }}"
                           placeholder="Contoh: 15.00"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">
                </div>

                <div>
                    <label for="contract_method" class="block text-xs font-semibold text-slate-700 mb-1.5">Metode Kontrak</label>
                    <select id="contract_method" name="contract_method"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">
                        <option value="lump_sum" {{ old('contract_method') == 'lump_sum' ? 'selected' : '' }}>Lump Sum Fixed Price</option>
                        <option value="unit_price" {{ old('contract_method') == 'unit_price' ? 'selected' : '' }}>Unit Price (Harga Satuan)</option>
                        <option value="cost_plus" {{ old('contract_method') == 'cost_plus' ? 'selected' : '' }}>Cost Plus Fee</option>
                    </select>
                </div>

                <div>
                    <label for="down_payment_percent" class="block text-xs font-semibold text-slate-700 mb-1.5">Uang Muka (DP %)</label>
                    <input type="number" step="0.01" id="down_payment_percent" name="down_payment_percent" value="{{ old('down_payment_percent', '20.00') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">
                </div>

                <div>
                    <label for="retention_percent" class="block text-xs font-semibold text-slate-700 mb-1.5">Retensi Pemeliharaan (%)</label>
                    <input type="number" step="0.01" id="retention_percent" name="retention_percent" value="{{ old('retention_percent', '5.00') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">
                </div>
            </div>
        </div>

        <!-- Section 3: Jadwal & Penanggung Jawab -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-7 space-y-5">
            <h2 class="text-sm font-bold text-navy-950 uppercase tracking-wider pb-3 border-b border-slate-100 flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4 text-construction-600"></i>
                <span>3. Jadwal Waktu & Penanggung Jawab Proyek</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div>
                    <label for="start_date" class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Mulai Proyek</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">
                </div>

                <div>
                    <label for="deadline_date" class="block text-xs font-semibold text-slate-700 mb-1.5">Target Selesai (Deadline)</label>
                    <input type="date" id="deadline_date" name="deadline_date" value="{{ old('deadline_date') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">
                </div>

                <div>
                    <label for="project_manager_id" class="block text-xs font-semibold text-slate-700 mb-1.5">Project Manager (PM)</label>
                    <select id="project_manager_id" name="project_manager_id"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">
                        <option value="">-- Pilih Project Manager --</option>
                        @foreach($projectManagers as $pm)
                            <option value="{{ $pm->id }}" {{ old('project_manager_id') == $pm->id ? 'selected' : '' }}>
                                {{ $pm->name }} ({{ $pm->role?->display_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="site_manager_id" class="block text-xs font-semibold text-slate-700 mb-1.5">Site Manager / Mandor Utama</label>
                    <select id="site_manager_id" name="site_manager_id"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">
                        <option value="">-- Pilih Site Manager --</option>
                        @foreach($siteManagers as $sm)
                            <option value="{{ $sm->id }}" {{ old('site_manager_id') == $sm->id ? 'selected' : '' }}>
                                {{ $sm->name }} ({{ $sm->role?->display_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2 lg:col-span-4">
                    <label for="notes" class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Khusus & Ruang Lingkup Proyek</label>
                    <textarea id="notes" name="notes" rows="3"
                              placeholder="Deskripsi teknis singkat, klausul khusus, atau catatan lapangan..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3">
            <a href="{{ route('projects.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Simpan & Buat Proyek</span>
            </button>
        </div>
    </form>
</div>
@endsection
