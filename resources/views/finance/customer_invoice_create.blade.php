@extends('layouts.app')

@section('title', 'Terbitkan Invoice Pelanggan (AR)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Terbitkan Invoice Tagihan Termin</h1>
            <p class="text-sm text-slate-500 mt-1">Buat invoice piutang resmi ke pelanggan dan sistem otomatis mencatat jurnal pendapatan akuntansi.</p>
        </div>
        <a href="{{ route('customer-invoices.index') }}" class="btn-secondary text-sm">Kembali</a>
    </div>

    <form method="POST" action="{{ route('customer-invoices.store') }}" 
          x-data="{
              projects: {{ Js::from($projects) }},
              selectedProject: null,
              grossAmount: 0,
              retentionAmount: 0,
              taxPercent: 11,
              onProjectChange(id) {
                  this.selectedProject = this.projects.find(p => p.id == id);
              },
              get taxAmount() {
                  const base = Math.max(0, (parseFloat(this.grossAmount) || 0) - (parseFloat(this.retentionAmount) || 0));
                  return (base * (parseFloat(this.taxPercent) || 0)) / 100;
              },
              get netAmount() {
                  const base = Math.max(0, (parseFloat(this.grossAmount) || 0) - (parseFloat(this.retentionAmount) || 0));
                  return base + this.taxAmount;
              },
              formatRupiah(val) {
                  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
              }
          }" 
          class="card p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="form-label required">Proyek Konstruksi</label>
                <select name="project_id" class="form-select" @change="onProjectChange($event.target.value)" required>
                    <option value="">-- Pilih Proyek Terkontrak --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ old('project_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->code }} - {{ $p->name }} (Pelanggan: {{ $p->customer?->name }} | Nilai Kontrak: Rp {{ number_format($p->contract_amount, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                @error('project_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label required">Keterangan Termin / Milestone</label>
                <input type="text" name="milestone_description" class="form-input" placeholder="Contoh: Pembayaran Uang Muka (DP 20%), Termin Progres 1" required>
            </div>

            <div>
                <label class="form-label">Sertifikasi Bobot Fisik Progres (%)</label>
                <input type="number" step="0.1" min="0" max="100" name="progress_percent" class="form-input" placeholder="0">
            </div>

            <div>
                <label class="form-label required">Tanggal Invoice Diterbitkan</label>
                <input type="date" name="invoice_date" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>

            <div>
                <label class="form-label required">Tanggal Jatuh Tempo Pembayaran</label>
                <input type="date" name="due_date" class="form-input" value="{{ date('Y-m-d', strtotime('+14 days')) }}" required>
            </div>

            <div>
                <label class="form-label required">Nilai Tagihan Bruto (IDR)</label>
                <input type="number" step="1000" min="1000" name="gross_amount" x-model="grossAmount" class="form-input font-mono font-bold" placeholder="100000000" required>
            </div>

            <div>
                <label class="form-label">Potongan Retensi Pemeliharaan (IDR)</label>
                <input type="number" step="1000" min="0" name="retention_amount" x-model="retentionAmount" class="form-input font-mono" placeholder="0">
            </div>

            <div>
                <label class="form-label">PPN (%)</label>
                <input type="number" step="0.5" min="0" max="50" name="tax_percent" x-model="taxPercent" class="form-input font-mono">
            </div>
        </div>

        <!-- Summary Box -->
        <div class="mt-6 flex justify-end">
            <div class="w-full md:w-80 bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-2 text-sm">
                <div class="flex justify-between text-slate-600">
                    <span>Tagihan Bruto:</span>
                    <span class="font-mono font-medium" x-text="formatRupiah(grossAmount)"></span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Potongan Retensi:</span>
                    <span class="font-mono font-medium text-rose-600" x-text="'-' + formatRupiah(retentionAmount)"></span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>PPN (<span x-text="taxPercent"></span>%):</span>
                    <span class="font-mono font-medium" x-text="formatRupiah(taxAmount)"></span>
                </div>
                <div class="border-t border-slate-200 pt-2 flex justify-between font-bold text-navy-950 text-base">
                    <span>Total Tagihan Bersih:</span>
                    <span class="font-mono text-construction-600" x-text="formatRupiah(netAmount)"></span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('customer-invoices.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Terbitkan Invoice & Catat Piutang</button>
        </div>
    </form>
</div>
@endsection
