@extends('layouts.app')

@section('title', 'Catat Faktur Tagihan Supplier (AP)')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Catat Faktur Tagihan Supplier (AP)</h1>
            <p class="text-sm text-slate-500 mt-1">Daftarkan tagihan dari vendor material berdasarkan Purchase Order yang telah diproses.</p>
        </div>
        <a href="{{ route('supplier-invoices.index') }}" class="btn-secondary text-sm">Kembali</a>
    </div>

    <form method="POST" action="{{ route('supplier-invoices.store') }}" class="card p-6 space-y-6"
          x-data="{
              purchaseOrders: {{ Js::from($purchaseOrders) }},
              selectedPo: null,
              totalAmount: '',
              taxAmount: '',
              onPoChange(poId) {
                  this.selectedPo = this.purchaseOrders.find(p => p.id == poId);
                  if (this.selectedPo) {
                      this.totalAmount = this.selectedPo.total_amount;
                      this.taxAmount = this.selectedPo.tax_amount;
                  }
              }
          }">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="form-label required">Pilih Purchase Order (PO) Acuan</label>
                <select name="purchase_order_id" class="form-select" @change="onPoChange($event.target.value)" required>
                    <option value="">-- Pilih PO yang sudah diverifikasi / diterima --</option>
                    @foreach($purchaseOrders as $po)
                        <option value="{{ $po->id }}">
                            {{ $po->po_number }} - {{ $po->supplier?->name }} (Total PO: Rp {{ number_format($po->total_amount, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                @error('purchase_order_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label required">Nomor Faktur / Invoice Vendor</label>
                <input type="text" name="supplier_invoice_ref" class="form-input" placeholder="Contoh: INV-STEEL/2026/045" required>
                <p class="text-[11px] text-slate-400 mt-1">Nomor resmi yang tercetak pada invoice dari supplier.</p>
                @error('supplier_invoice_ref') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label required">Tanggal Faktur</label>
                <input type="date" name="invoice_date" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>

            <div>
                <label class="form-label required">Tanggal Jatuh Tempo Pembayaran</label>
                <input type="date" name="due_date" class="form-input" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
            </div>

            <div>
                <label class="form-label required">Total Nilai Tagihan (IDR)</label>
                <input type="number" step="1" name="total_amount" x-model="totalAmount" class="form-input font-mono font-bold" required>
            </div>

            <div>
                <label class="form-label">PPN / Pajak Tertera (IDR)</label>
                <input type="number" step="1" name="tax_amount" x-model="taxAmount" class="form-input font-mono" placeholder="0">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('supplier-invoices.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Daftarkan Tagihan Supplier</button>
        </div>
    </form>
</div>
@endsection
