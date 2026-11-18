@extends('layouts.app')

@section('title', 'Alat Berat & Peralatan Konstruksi')

@section('content')
<div class="space-y-6" x-data="{ openCreateModal: false, openUsageModal: false, selectedEquipmentId: null, selectedEquipmentName: '' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Alat Berat, Kendaraan & Peralatan</h1>
            <p class="text-sm text-slate-500 mt-1">Pengelolaan armada alat konstruksi milik sendiri maupun sewa, tarif sewa, penempatan proyek, dan log bahan bakar.</p>
        </div>
        <button type="button" @click="openCreateModal = true" class="btn-primary inline-flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Daftarkan Alat Baru</span>
        </button>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Armada</span>
                    <h3 class="text-2xl font-black text-navy-950 mt-1">{{ $totalEquipment }} <span class="text-xs font-normal text-slate-500">unit</span></h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Milik & sewa rekanan</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-navy-50 text-navy-700 flex items-center justify-center">
                    <i data-lucide="truck" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Sedang Beroperasi</span>
                    <h3 class="text-2xl font-black text-construction-600 mt-1">{{ $inUseCount }} <span class="text-xs font-normal text-slate-500">unit</span></h3>
                    <p class="text-[11px] text-construction-500 mt-0.5">Aktif di lokasi proyek</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-construction-600 flex items-center justify-center">
                    <i data-lucide="activity" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Siap Operasi (Pool)</span>
                    <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $availableCount }} <span class="text-xs font-normal text-slate-500">unit</span></h3>
                    <p class="text-[11px] text-emerald-500 mt-0.5">Standby di workshop/gudang</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Perbaikan / Servis</span>
                    <h3 class="text-2xl font-black text-rose-600 mt-1">{{ $maintenanceCount }} <span class="text-xs font-normal text-slate-500">unit</span></h3>
                    <p class="text-[11px] text-rose-500 mt-0.5">Maintenance berkala</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i data-lucide="wrench" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card p-4">
        <form method="GET" action="{{ route('equipment.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label text-xs">Cari Nama / Kode / No. Aset</label>
                <input type="text" name="search" class="form-input text-sm" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="form-label text-xs">Status Kesiapan</label>
                <select name="status" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Siap Pakai (Standby)</option>
                    <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>Sedang Beroperasi</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Perawatan / Servis</option>
                    <option value="breakdown" {{ request('status') == 'breakdown' ? 'selected' : '' }}>Rusak</option>
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Status Kepemilikan</label>
                <select name="ownership_status" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Kepemilikan</option>
                    <option value="owned" {{ request('ownership_status') == 'owned' ? 'selected' : '' }}>Milik Perusahaan (Aset)</option>
                    <option value="rented" {{ request('ownership_status') == 'rented' ? 'selected' : '' }}>Sewa Rekanan</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary text-sm flex-1">Cari</button>
                <a href="{{ route('equipment.index') }}" class="btn-secondary text-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- Main Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode & Nama Alat</th>
                        <th>Spesifikasi Model</th>
                        <th>Kepemilikan</th>
                        <th>Lokasi / Proyek</th>
                        <th>Operator & BBM</th>
                        <th class="text-right">Tarif Harian</th>
                        <th class="text-right">Tarif per Jam</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipmentList as $eq)
                        <tr>
                            <td>
                                <div class="font-bold text-navy-900">{{ $eq->name }}</div>
                                <div class="text-xs font-mono text-slate-400 flex items-center gap-1.5 mt-0.5">
                                    <span>{{ $eq->code }}</span>
                                    @if($eq->asset_number)
                                        <span class="text-slate-300">•</span>
                                        <span>{{ $eq->asset_number }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="text-xs text-slate-700">{{ $eq->model_spec ?? '-' }}</span>
                            </td>
                            <td>
                                @if($eq->ownership_status === 'owned')
                                    <span class="badge-primary text-[10px]">Aset Sendiri</span>
                                @else
                                    <span class="badge-warning text-[10px]">Sewa Rekanan</span>
                                @endif
                            </td>
                            <td>
                                @if($eq->project)
                                    <div class="text-xs font-semibold text-navy-950">{{ $eq->project->name }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ $eq->project->code }}</div>
                                @else
                                    <div class="text-xs text-slate-600">{{ $eq->current_location ?? 'Pool Utama' }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="text-xs text-slate-800">{{ $eq->operator_name ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">{{ $eq->fuel_type ?? 'Solar' }}</div>
                            </td>
                            <td class="text-right font-mono font-medium text-xs">
                                @rupiah($eq->daily_rental_rate)
                            </td>
                            <td class="text-right font-mono font-medium text-xs">
                                @rupiah($eq->hourly_rental_rate)
                            </td>
                            <td class="text-center">
                                @if($eq->status === 'available')
                                    <span class="badge-success text-[10px]">Siap Pakai</span>
                                @elseif($eq->status === 'in_use')
                                    <span class="badge-orange text-[10px]">Beroperasi</span>
                                @elseif($eq->status === 'maintenance')
                                    <span class="badge-warning text-[10px]">Servis</span>
                                @else
                                    <span class="badge-danger text-[10px]">Rusak</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button type="button" 
                                        @click="selectedEquipmentId = {{ $eq->id }}; selectedEquipmentName = '{{ $eq->name }} ({{ $eq->code }})'; openUsageModal = true" 
                                        class="btn-secondary text-xs px-2.5 py-1 inline-flex items-center gap-1 text-construction-700 hover:text-construction-800 border-construction-300">
                                    <i data-lucide="fuel" class="w-3.5 h-3.5 text-construction-600"></i>
                                    <span>Log Pemakaian</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-12 text-slate-400">
                                <i data-lucide="truck" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada armada alat berat atau kendaraan yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $equipmentList->links() }}
        </div>
    </div>

    <!-- Recent Operational Log Section -->
    @if($recentUsages->count() > 0)
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-navy-950">Catatan Pemakaian Lapangan Terkini</h3>
                    <p class="text-xs text-slate-400">Riwayat jam operasional, bahan bakar, dan pembebanan biaya ke proyek.</p>
                </div>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Alat / Kendaraan</th>
                            <th>Proyek Terkait</th>
                            <th class="text-center">Jam Kerja</th>
                            <th class="text-right">Bahan Bakar</th>
                            <th class="text-right">Total Biaya Operasional</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsages as $usg)
                            <tr>
                                <td class="whitespace-nowrap text-xs">@dateid($usg->usage_date)</td>
                                <td class="font-bold text-navy-900 text-xs">{{ $usg->equipment?->name }}</td>
                                <td class="text-xs text-slate-700">{{ $usg->project?->name }}</td>
                                <td class="text-center font-mono font-bold text-xs">{{ $usg->hours_used }} Jam</td>
                                <td class="text-right font-mono text-xs">
                                    <div>{{ $usg->fuel_liters }} Liter</div>
                                    <div class="text-[10px] text-slate-400">@rupiah($usg->fuel_cost)</div>
                                </td>
                                <td class="text-right font-mono font-bold text-navy-950 text-xs">
                                    @rupiah($usg->total_cost)
                                </td>
                                <td class="text-xs text-slate-500 max-w-xs truncate">{{ $usg->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- MODAL CATAT PEMAKAIAN ALAT -->
    <div x-show="openUsageModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-5" @click.away="openUsageModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Catat Pemakaian & Biaya Operasional</h3>
                    <p class="text-xs text-construction-600 font-semibold" x-text="selectedEquipmentName"></p>
                </div>
                <button type="button" @click="openUsageModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form :action="'/equipment/' + selectedEquipmentId + '/usage'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label required">Pilih Proyek Penugasan</label>
                    <select name="project_id" class="form-select text-sm" required>
                        <option value="">-- Pilih Proyek --</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Tanggal Pemakaian</label>
                        <input type="date" name="usage_date" class="form-input text-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="form-label required">Durasi Jam Kerja (Jam)</label>
                        <input type="number" step="0.5" min="0.5" max="24" name="hours_used" class="form-input text-sm font-mono font-bold" value="8" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Konsumsi BBM (Liter)</label>
                        <input type="number" step="0.5" min="0" name="fuel_liters" class="form-input text-sm font-mono" placeholder="Contoh: 45">
                    </div>
                    <div>
                        <label class="form-label">Total Biaya Solar (IDR)</label>
                        <input type="number" step="1000" min="0" name="fuel_cost" class="form-input text-sm font-mono" placeholder="675000">
                    </div>
                </div>

                <div>
                    <label class="form-label">Upah / Uang Makan Operator (IDR)</label>
                    <input type="number" step="1000" min="0" name="operator_cost" class="form-input text-sm font-mono" placeholder="150000">
                </div>

                <div>
                    <label class="form-label">Catatan Kegiatan Lapangan</label>
                    <textarea name="notes" rows="2" class="form-input text-sm" placeholder="Contoh: Penggalian pondasi basement zona utara"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="openUsageModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH ALAT BARU -->
    <div x-show="openCreateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full p-6 space-y-5" @click.away="openCreateModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Daftarkan Alat / Kendaraan Baru</h3>
                    <p class="text-xs text-slate-500">Registrasi armada alat berat, kendaraan dinas, atau peralatan sewa.</p>
                </div>
                <button type="button" @click="openCreateModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('equipment.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Nama Alat / Kendaraan</label>
                        <input type="text" name="name" class="form-input text-sm" placeholder="Contoh: Excavator Komatsu PC200" required>
                    </div>
                    <div>
                        <label class="form-label">Nomor Aset / Polisi</label>
                        <input type="text" name="asset_number" class="form-input text-sm font-mono" placeholder="AST-EXC-005 / B 9123 XYZ">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Status Kepemilikan</label>
                        <select name="ownership_status" class="form-select text-sm" required>
                            <option value="owned">Milik Sendiri (Aset Perusahaan)</option>
                            <option value="rented">Sewa dari Rekanan / Vendor</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label required">Status Kesiapan</label>
                        <select name="status" class="form-select text-sm" required>
                            <option value="available">Siap Pakai (Standby)</option>
                            <option value="in_use">Sedang Beroperasi</option>
                            <option value="maintenance">Dalam Perawatan / Servis</option>
                            <option value="breakdown">Rusak</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Spesifikasi Model & Kapasitas</label>
                    <input type="text" name="model_spec" class="form-input text-sm" placeholder="Contoh: Boom 28m, Bucket 0.9 m3, Daya 50 kVA">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Tarif Sewa Harian (IDR)</label>
                        <input type="number" step="1000" min="0" name="daily_rental_rate" class="form-input text-sm font-mono" value="0" required>
                    </div>
                    <div>
                        <label class="form-label required">Tarif Sewa per Jam (IDR)</label>
                        <input type="number" step="1000" min="0" name="hourly_rental_rate" class="form-input text-sm font-mono" value="0" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nama Operator Standby</label>
                        <input type="text" name="operator_name" class="form-input text-sm" placeholder="Nama operator">
                    </div>
                    <div>
                        <label class="form-label">Jenis Bahan Bakar</label>
                        <input type="text" name="fuel_type" class="form-input text-sm" value="Solar / Biosolar B35">
                    </div>
                </div>

                <div>
                    <label class="form-label">Lokasi Saat Ini / Pool</label>
                    <input type="text" name="current_location" class="form-input text-sm" placeholder="Contoh: Pool Alat Pulogadung">
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="openCreateModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Simpan Alat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
