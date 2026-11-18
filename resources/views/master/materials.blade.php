@extends('layouts.app')

@section('title', 'Master Data Material Konstruksi')

@section('content')
<div class="space-y-6" x-data="{ openMaterialModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Master Material Konstruksi</h1>
            <p class="text-sm text-slate-500 mt-1">Katalog bahan bangunan (Semen, Besi Beton, Baja WF, Pasir, Readymix, dll) dan batas stok aman.</p>
        </div>
        <button type="button" @click="openMaterialModal = true" class="btn-primary inline-flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Material Baru</span>
        </button>
    </div>

    <!-- Filter Bar -->
    <div class="card p-4">
        <form method="GET" action="{{ route('materials.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="form-label text-xs">Kategori Material</label>
                <select name="category_id" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Cari Nama / Kode</label>
                <input type="text" name="search" class="form-input text-sm" placeholder="Ketik kode atau nama material..." value="{{ request('search') }}">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary text-sm">Cari</button>
                <a href="{{ route('materials.index') }}" class="btn-secondary text-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th w-32">Kode Material</th>
                        <th class="table-th">Nama & Spesifikasi</th>
                        <th class="table-th">Kategori</th>
                        <th class="table-th text-center">Satuan</th>
                        <th class="table-th text-right">Harga Beli Terakhir</th>
                        <th class="table-th text-right">Batas Min. Stok</th>
                        <th class="table-th text-center">Status</th>
                        <th class="table-th text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $m)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-mono text-xs font-bold text-navy-900">{{ $m->code }}</td>
                            <td class="table-td">
                                <div class="font-bold text-navy-950">{{ $m->name }}</div>
                                <div class="text-xs text-slate-500">{{ $m->specification ?? '-' }}</div>
                            </td>
                            <td class="table-td">
                                <span class="badge badge-neutral text-xs">{{ $m->category?->name ?? 'Material Sipil' }}</span>
                            </td>
                            <td class="table-td text-center font-bold text-xs text-slate-700">{{ $m->unit?->symbol }}</td>
                            <td class="table-td text-right font-mono text-xs font-medium whitespace-nowrap">@rupiah($m->last_purchase_price)</td>
                            <td class="table-td text-right font-mono text-xs font-bold text-slate-700 whitespace-nowrap">
                                {{ number_format($m->minimum_stock, 2) }} {{ $m->unit?->symbol }}
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                @if($m->is_active)
                                    <span class="badge badge-success text-[10px]">Aktif</span>
                                @else
                                    <span class="badge badge-danger text-[10px]">Nonaktif</span>
                                @endif
                            </td>
                            <td class="table-td text-center whitespace-nowrap">
                                <a href="{{ route('inventory.stock-card', $m->id) }}" class="btn-secondary btn-xs inline-flex items-center gap-1" title="Lihat Kartu Stok di Gudang">
                                    <i data-lucide="file-text" class="w-3 h-3"></i>
                                    <span>Kartu Stok</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <i data-lucide="layers" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada master material yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $materials->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH MATERIAL -->
    <div x-show="openMaterialModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-6" @click.away="openMaterialModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Tambah Master Material Baru</h3>
                    <p class="text-xs text-slate-500">Daftarkan bahan baku atau perlengkapan konstruksi baru.</p>
                </div>
                <button type="button" @click="openMaterialModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('materials.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Kode Material</label>
                        <input type="text" name="code" class="form-input text-sm" placeholder="Contoh: MAT-010" required>
                    </div>
                    <div>
                        <label class="form-label required">Satuan Unit</label>
                        <select name="unit_id" class="form-select text-sm" required>
                            @foreach($units as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->symbol }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label required">Nama Material</label>
                    <input type="text" name="name" class="form-input text-sm" placeholder="Contoh: Baja WF 250 x 125 x 6 x 9mm" required>
                </div>

                <div>
                    <label class="form-label required">Kategori</label>
                    <select name="material_category_id" class="form-select text-sm" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Spesifikasi Teknis</label>
                    <textarea name="specification" rows="2" class="form-input text-sm" placeholder="Standar SNI, mutu beton, toleransi ukuran..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Harga Acuan / Beli (IDR)</label>
                        <input type="number" step="100" min="0" name="last_purchase_price" class="form-input text-sm font-mono" placeholder="75000" required>
                    </div>
                    <div>
                        <label class="form-label required">Batas Minimum Stok</label>
                        <input type="number" step="0.1" min="0" name="minimum_stock" class="form-input text-sm font-mono" placeholder="10" required>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openMaterialModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Simpan Material</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
