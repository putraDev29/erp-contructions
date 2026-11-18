@extends('layouts.app')

@section('title', 'Mitra Bisnis: Pelanggan & Supplier')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'customers', openCustModal: false, openSupModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Mitra Bisnis: Pelanggan & Supplier</h1>
            <p class="text-sm text-slate-500 mt-1">Pengelolaan data rekanan klien pemilik proyek dan mitra vendor pemasok material.</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="bg-slate-100 p-1 rounded-xl flex items-center">
                <button type="button" @click="activeTab = 'customers'" 
                        :class="activeTab === 'customers' ? 'bg-white shadow text-navy-950 font-bold' : 'text-slate-600 hover:text-navy-900'"
                        class="px-4 py-1.5 rounded-lg text-sm transition-all">
                    Pelanggan (Clients)
                </button>
                <button type="button" @click="activeTab = 'suppliers'" 
                        :class="activeTab === 'suppliers' ? 'bg-white shadow text-navy-950 font-bold' : 'text-slate-600 hover:text-navy-900'"
                        class="px-4 py-1.5 rounded-lg text-sm transition-all">
                    Supplier / Vendor
                </button>
            </div>
            <button type="button" x-show="activeTab === 'customers'" @click="openCustModal = true" class="btn-primary inline-flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Pelanggan</span>
            </button>
            <button type="button" x-show="activeTab === 'suppliers'" @click="openSupModal = true" class="btn-primary inline-flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Supplier</span>
            </button>
        </div>
    </div>

    <!-- TAB 1: PELANGGAN (CUSTOMERS) -->
    <div x-show="activeTab === 'customers'" class="card overflow-hidden">
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th w-28">Kode</th>
                        <th class="table-th">Nama Pelanggan & Alamat</th>
                        <th class="table-th">Tipe Rekanan</th>
                        <th class="table-th">Penanggung Jawab</th>
                        <th class="table-th">No. Telepon / WA</th>
                        <th class="table-th">NPWP</th>
                        <th class="table-th text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-mono text-xs font-bold text-navy-900">{{ $c->code }}</td>
                            <td class="table-td">
                                <div class="font-bold text-navy-950">{{ $c->name }}</div>
                                <div class="text-xs text-slate-500">{{ $c->address ?? '-' }}</div>
                            </td>
                            <td class="table-td">
                                <span class="badge badge-neutral text-xs uppercase">{{ $c->customer_type }}</span>
                            </td>
                            <td class="table-td text-sm font-medium text-slate-800">{{ $c->contact_person }}</td>
                            <td class="table-td font-mono text-xs text-slate-700 whitespace-nowrap">{{ $c->phone }}</td>
                            <td class="table-td font-mono text-xs text-slate-500 whitespace-nowrap">{{ $c->npwp ?? '-' }}</td>
                            <td class="table-td text-center">
                                <span class="badge badge-success text-[10px]">Aktif</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <i data-lucide="users" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada data pelanggan terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $customers->links() }}
        </div>
    </div>

    <!-- TAB 2: SUPPLIER / VENDOR -->
    <div x-show="activeTab === 'suppliers'" class="card overflow-hidden">
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th w-28">Kode</th>
                        <th class="table-th">Nama Vendor / Supplier</th>
                        <th class="table-th">Penanggung Jawab</th>
                        <th class="table-th">Telepon & Email</th>
                        <th class="table-th">Termin Pembayaran</th>
                        <th class="table-th">NPWP</th>
                        <th class="table-th text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $s)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td font-mono text-xs font-bold text-navy-900">{{ $s->code }}</td>
                            <td class="table-td">
                                <div class="font-bold text-navy-950">{{ $s->name }}</div>
                                <div class="text-xs text-slate-500">{{ $s->address ?? '-' }}</div>
                            </td>
                            <td class="table-td text-sm font-medium text-slate-800">{{ $s->contact_person }}</td>
                            <td class="table-td">
                                <div class="font-mono text-xs text-slate-700 whitespace-nowrap">{{ $s->phone }}</div>
                                <div class="text-xs text-slate-400">{{ $s->email ?? '-' }}</div>
                            </td>
                            <td class="table-td">
                                <span class="badge badge-primary text-xs">{{ $s->payment_terms }}</span>
                            </td>
                            <td class="table-td font-mono text-xs text-slate-500 whitespace-nowrap">{{ $s->npwp ?? '-' }}</td>
                            <td class="table-td text-center">
                                <span class="badge badge-success text-[10px]">Aktif</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <i data-lucide="shopping-cart" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada data supplier terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $suppliers->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH PELANGGAN -->
    <div x-show="openCustModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-6" @click.away="openCustModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Daftarkan Pelanggan Baru</h3>
                    <p class="text-xs text-slate-500">Pemilik proyek / pemberi kerja konstruksi.</p>
                </div>
                <button type="button" @click="openCustModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('master.customers.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Kode Pelanggan</label>
                        <input type="text" name="code" class="form-input text-sm" placeholder="CUST-005" required>
                    </div>
                    <div>
                        <label class="form-label required">Tipe Pelanggan</label>
                        <select name="customer_type" class="form-select text-sm" required>
                            <option value="corporate">Perusahaan (Corporate)</option>
                            <option value="individual">Perorangan (Individual)</option>
                            <option value="government">Instansi Pemerintah</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label required">Nama Perusahaan / Klien</label>
                    <input type="text" name="name" class="form-input text-sm" placeholder="Contoh: PT Metropolitan Properti" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Nama PIC / Kontak</label>
                        <input type="text" name="contact_person" class="form-input text-sm" required>
                    </div>
                    <div>
                        <label class="form-label required">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-input text-sm" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input text-sm">
                    </div>
                    <div>
                        <label class="form-label">NPWP</label>
                        <input type="text" name="npwp" class="form-input text-sm">
                    </div>
                </div>

                <div>
                    <label class="form-label">Alamat Kantor / Penagihan</label>
                    <textarea name="address" rows="2" class="form-input text-sm"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openCustModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Daftarkan Pelanggan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH SUPPLIER -->
    <div x-show="openSupModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-6" @click.away="openSupModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Daftarkan Supplier / Vendor Baru</h3>
                    <p class="text-xs text-slate-500">Pemasok material dan perlengkapan konstruksi.</p>
                </div>
                <button type="button" @click="openSupModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('master.suppliers.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Kode Supplier</label>
                        <input type="text" name="code" class="form-input text-sm" placeholder="SUP-005" required>
                    </div>
                    <div>
                        <label class="form-label required">Termin Pembayaran</label>
                        <input type="text" name="payment_terms" class="form-input text-sm" value="Net 30 Hari" required>
                    </div>
                </div>

                <div>
                    <label class="form-label required">Nama Toko / Distributor Material</label>
                    <input type="text" name="name" class="form-input text-sm" placeholder="Contoh: CV Semen & Pasir Nusantara" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Nama PIC / Sales</label>
                        <input type="text" name="contact_person" class="form-input text-sm" required>
                    </div>
                    <div>
                        <label class="form-label required">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-input text-sm" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input text-sm">
                    </div>
                    <div>
                        <label class="form-label">NPWP</label>
                        <input type="text" name="npwp" class="form-input text-sm">
                    </div>
                </div>

                <div>
                    <label class="form-label">Alamat Gudang / Depo Supplier</label>
                    <textarea name="address" rows="2" class="form-input text-sm"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openSupModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Daftarkan Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
