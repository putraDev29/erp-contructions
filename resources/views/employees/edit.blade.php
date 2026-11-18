@extends('layouts.app')

@section('title', 'Edit Pekerja - ' . $employee->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Edit Data Tenaga Kerja</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui informasi tarif upah, jabatan, atau rekening pekerja.</p>
        </div>
        <a href="{{ route('employees.index') }}" class="btn-secondary text-sm">Kembali</a>
    </div>

    <form method="POST" action="{{ route('employees.update', $employee) }}" class="card p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="form-label required">NIK / Kode Pekerja</label>
                <input type="text" name="employee_number" class="form-input" value="{{ old('employee_number', $employee->employee_number) }}" required>
                @error('employee_number') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label required">Nama Lengkap</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $employee->name) }}" required>
                @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label required">Jabatan / Spesialisasi</label>
                <input type="text" name="role_title" class="form-input" value="{{ old('role_title', $employee->role_title) }}" required>
            </div>

            <div>
                <label class="form-label required">Jenis Hubungan Kerja</label>
                <select name="employment_type" class="form-select" required>
                    <option value="daily_worker" {{ old('employment_type', $employee->employment_type) == 'daily_worker' ? 'selected' : '' }}>Harian Lepas (Tukang)</option>
                    <option value="contract" {{ old('employment_type', $employee->employment_type) == 'contract' ? 'selected' : '' }}>Kontrak Proyek</option>
                    <option value="permanent" {{ old('employment_type', $employee->employment_type) == 'permanent' ? 'selected' : '' }}>Karyawan Tetap</option>
                    <option value="borongan" {{ old('employment_type', $employee->employment_type) == 'borongan' ? 'selected' : '' }}>Borongan</option>
                </select>
            </div>

            <div>
                <label class="form-label required">Tarif Upah Harian (Rp)</label>
                <input type="number" step="1000" name="daily_rate" class="form-input font-mono" value="{{ old('daily_rate', $employee->daily_rate) }}" required>
            </div>

            <div>
                <label class="form-label required">Tarif Lembur per Jam (Rp)</label>
                <input type="number" step="1000" name="hourly_rate" class="form-input font-mono" value="{{ old('hourly_rate', $employee->hourly_rate) }}" required>
            </div>

            <div>
                <label class="form-label required">Tanggal Mulai Bekerja</label>
                <input type="date" name="hire_date" class="form-input" value="{{ old('hire_date', $employee->hire_date?->format('Y-m-d')) }}" required>
            </div>

            <div>
                <label class="form-label">Nomor WhatsApp / HP</label>
                <input type="text" name="phone" class="form-input" value="{{ old('phone', $employee->phone) }}">
            </div>

            <div>
                <label class="form-label required">Status Keaktifan</label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $employee->is_active) ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !old('is_active', $employee->is_active) ? 'selected' : '' }}>Nonaktif / Berhenti</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="address" rows="2" class="form-input">{{ old('address', $employee->address) }}</textarea>
            </div>

            <div class="md:col-span-2 border-t border-slate-100 pt-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Informasi Rekening Pembayaran Gaji</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label text-xs">Nama Bank</label>
                        <input type="text" name="bank_name" class="form-input text-sm" value="{{ old('bank_name', $employee->bank_name) }}">
                    </div>
                    <div>
                        <label class="form-label text-xs">Nomor Rekening</label>
                        <input type="text" name="bank_account_number" class="form-input text-sm" value="{{ old('bank_account_number', $employee->bank_account_number) }}">
                    </div>
                    <div>
                        <label class="form-label text-xs">Atas Nama Rekening</label>
                        <input type="text" name="bank_account_holder" class="form-input text-sm" value="{{ old('bank_account_holder', $employee->bank_account_holder) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('employees.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Perbarui Data Pekerja</button>
        </div>
    </form>
</div>
@endsection
