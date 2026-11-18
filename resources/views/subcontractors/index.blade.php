@extends('layouts.app')

@section('title', 'Mitra Subkontraktor')

@section('content')
<div class="space-y-6" x-data="{ openSubModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Mitra Subkontraktor</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar mitra kerja spesialis untuk pekerjaan borongan konstruksi (Baja, Sipil, MEP, Finishing).</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('subcontractors.contracts') }}" class="btn-secondary inline-flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span>Kelola SPK & BAP</span>
            </a>
            <button type="button" @click="openSubModal = true" class="btn-primary inline-flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Mitra Subkon</span>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode & Nama Subkon</th>
                        <th>Spesialisasi Pekerjaan</th>
                        <th>Penanggung Jawab</th>
                        <th>Kontak Telepon</th>
                        <th>Info Rekening Bank</th>
                        <th>Total SPK</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subcontractors as $sub)
                        <tr>
                            <td>
                                <div class="font-bold text-navy-900">{{ $sub->name }}</div>
                                <div class="text-xs font-mono text-slate-400">{{ $sub->code }}</div>
                            </td>
                            <td>
                                <span class="badge-primary text-xs">{{ $sub->specialty }}</span>
                            </td>
                            <td>{{ $sub->contact_person }}</td>
                            <td class="font-mono text-xs">{{ $sub->phone }}</td>
                            <td>
                                <div class="text-xs text-slate-700 font-medium">{{ $sub->bank_name ?? '-' }}</div>
                                <div class="text-[11px] font-mono text-slate-500">
                                    {{ $sub->bank_account_number ?: ($sub->bank_account_no ?? '-') }} 
                                    @if($sub->bank_account_holder || $sub->bank_account_name)
                                        <span class="text-slate-400">({{ $sub->bank_account_holder ?: $sub->bank_account_name }})</span>
                                    @endif
                                </div>
                            </td>
                            <td class="font-medium text-slate-800">
                                <a href="{{ route('subcontractors.contracts', ['subcontractor_id' => $sub->id]) }}" class="text-construction-600 hover:underline font-bold text-xs">
                                    {{ $sub->contracts_count }} SPK Aktif
                                </a>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('subcontractors.contracts') }}" class="btn-secondary text-xs px-2.5 py-1 inline-flex items-center gap-1">
                                    <i data-lucide="file-plus" class="w-3.5 h-3.5 text-construction-600"></i>
                                    <span>Buat SPK</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <i data-lucide="hammer" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada data subkontraktor terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $subcontractors->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH SUBKONTRAKTOR -->
    <div x-show="openSubModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-6" @click.away="openSubModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Daftarkan Subkontraktor Baru</h3>
                    <p class="text-xs text-slate-500">Mitra pihak ketiga untuk pengerjaan paket pekerjaan konstruksi.</p>
                </div>
                <button type="button" @click="openSubModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('subcontractors.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label required">Nama Perusahaan / Tim Subkon</label>
                    <input type="text" name="name" class="form-input text-sm" placeholder="Contoh: PT Baja Perkasa Konstruksi / Tim Las Pak Joko" required>
                </div>

                <div>
                    <label class="form-label required">Bidang / Spesialisasi</label>
                    <input type="text" name="specialty" class="form-input text-sm" placeholder="Contoh: Fabrikasi & Erection Baja WF, Instalasi MEP, Pengecoran" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Penanggung Jawab (PIC)</label>
                        <input type="text" name="contact_person" class="form-input text-sm" placeholder="Nama PIC" required>
                    </div>
                    <div>
                        <label class="form-label required">Nomor Telepon / WA</label>
                        <input type="text" name="phone" class="form-input text-sm" placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">Alamat / Lokasi Workshop</label>
                    <textarea name="address" rows="2" class="form-input text-sm"></textarea>
                </div>

                <div class="border-t border-slate-100 pt-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Rekening Pembayaran</h4>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <input type="text" name="bank_name" placeholder="Bank" class="form-input text-xs">
                        </div>
                        <div>
                            <input type="text" name="bank_account_number" placeholder="No. Rekening" class="form-input text-xs">
                        </div>
                        <div>
                            <input type="text" name="bank_account_holder" placeholder="Atas Nama" class="form-input text-xs">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openSubModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Daftarkan Subkontraktor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
