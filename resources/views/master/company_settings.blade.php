@extends('layouts.app')

@section('title', 'Pengaturan Perusahaan (ERP Settings)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Pengaturan Identitas Perusahaan</h1>
            <p class="text-sm text-slate-500 mt-1">Konfigurasi kop surat resmi, alamat penagihan, nomor kontak, dan aturan default dokumen ERP.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('settings.company.update') }}" class="card p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="form-label required">Nama Perusahaan Kontraktor</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $company->name) }}" required>
            </div>

            <div>
                <label class="form-label required">Nomor Telepon Kantor</label>
                <input type="text" name="phone" class="form-input" value="{{ old('phone', $company->phone) }}" required>
            </div>

            <div>
                <label class="form-label required">Email Perusahaan</label>
                <input type="email" name="email" class="form-input" value="{{ old('email', $company->email) }}" required>
            </div>

            <div class="md:col-span-2">
                <label class="form-label required">Alamat Kantor Pusat / Workshop</label>
                <textarea name="address" rows="2" class="form-input" required>{{ old('address', $company->address) }}</textarea>
            </div>

            <div>
                <label class="form-label">Nomor Pokok Wajib Pajak (NPWP)</label>
                <input type="text" name="npwp" class="form-input" value="{{ old('npwp', $company->npwp) }}">
            </div>

            <div>
                <label class="form-label required">Tarif Standar PPN (%)</label>
                <input type="number" step="0.5" min="0" max="50" name="default_tax_percent" class="form-input font-mono" value="{{ old('default_tax_percent', $company->default_tax_percent) }}" required>
            </div>

            <div>
                <label class="form-label required">Standar Retensi Pemeliharaan (%)</label>
                <input type="number" step="0.5" min="0" max="20" name="default_retention_percent" class="form-input font-mono" value="{{ old('default_retention_percent', $company->default_retention_percent) }}" required>
            </div>

            <div class="md:col-span-2">
                <label class="form-label">Teks Catatan Kaki / Footer Dokumen Cetak</label>
                <textarea name="document_footer_text" rows="2" class="form-input" placeholder="Dokumen ini sah dan diterbitkan secara elektronik oleh CONSTRUCTA ERP.">{{ old('document_footer_text', $company->document_footer_text) }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="btn-primary">Simpan Perubahan Pengaturan</button>
        </div>
    </form>
</div>
@endsection
