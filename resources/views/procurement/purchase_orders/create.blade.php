@extends('layouts.app')

@section('title', 'Buat Purchase Order (PO)')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Buat Purchase Order (PO)</h1>
            <p class="text-sm text-slate-500 mt-1">Terbitkan pesanan pembelian material konstruksi resmi ke supplier.</p>
        </div>
        <a href="{{ route('purchase-orders.index') }}" class="btn-secondary text-sm">Kembali</a>
    </div>

    <form method="POST" action="{{ route('purchase-orders.store') }}" 
          x-data="{
              taxPercent: 11,
              shippingFee: 0,
              items: [
                  { material_id: '', qty: 1, unit_price: 0, total: 0 }
              ],
              materialsList: {{ Js::from($materials) }},
              onMaterialChange(item) {
                  const m = this.materialsList.find(mat => mat.id == item.material_id);
                  if (m) {
                      item.unit_price = parseFloat(m.last_purchase_price || m.reference_price || 0);
                      this.calcItem(item);
                  }
              },
              calcItem(item) {
                  item.total = (parseFloat(item.qty) || 0) * (parseFloat(item.unit_price) || 0);
              },
              addItem() {
                  this.items.push({ material_id: '', qty: 1, unit_price: 0, total: 0 });
              },
              removeItem(index) {
                  if (this.items.length > 1) {
                      this.items.splice(index, 1);
                  }
              },
              get subtotal() {
                  return this.items.reduce((sum, item) => sum + (parseFloat(item.total) || 0), 0);
              },
              get taxAmount() {
                  return (this.subtotal * (parseFloat(this.taxPercent) || 0)) / 100;
              },
              get grandTotal() {
                  return this.subtotal + this.taxAmount + (parseFloat(this.shippingFee) || 0);
              },
              formatRupiah(val) {
                  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
              }
          }" 
          class="card p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="form-label required">Proyek Konstruksi</label>
                <select name="project_id" class="form-select" required>
                    <option value="">-- Pilih Proyek --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ (old('project_id') == $p->id || (isset($selectedProject) && $selectedProject->id == $p->id)) ? 'selected' : '' }}>
                            {{ $p->code }} - {{ $p->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label required">Supplier</label>
                <select name="supplier_id" class="form-select" required>
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }} ({{ $s->payment_terms ?? 'Net 30' }})
                        </option>
                    @endforeach
                </select>
                @error('supplier_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label required">Tanggal PO</label>
                <input type="date" name="po_date" class="form-input" value="{{ old('po_date', date('Y-m-d')) }}" required>
                @error('po_date') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label required">Termin Pembayaran</label>
                <input type="text" name="payment_terms" class="form-input" value="{{ old('payment_terms', 'Net 30 Hari') }}" placeholder="Contoh: Net 30 Hari, CBD, COD" required>
            </div>

            <div>
                <label class="form-label">PPN (%)</label>
                <input type="number" step="0.1" name="tax_percent" x-model="taxPercent" class="form-input" placeholder="11">
            </div>

            <div>
                <label class="form-label">Ongkos Kirim (IDR)</label>
                <input type="number" step="1000" name="shipping_fee" x-model="shippingFee" class="form-input" placeholder="0">
            </div>

            <div class="md:col-span-3">
                <label class="form-label">Catatan Tambahan / Lokasi Pengiriman</label>
                <textarea name="notes" rows="2" class="form-input" placeholder="Petunjuk pengiriman, syarat bongkar muat, dll">{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- Dynamic Items Table -->
        <div class="border-t border-slate-100 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-navy-900">Daftar Item Material PO</h3>
                <button type="button" @click="addItem()" class="btn-secondary text-xs inline-flex items-center gap-1.5">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Item</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border border-slate-200 rounded-lg">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">Material</th>
                            <th class="p-3 w-32">Kuantitas</th>
                            <th class="p-3 w-44">Harga Satuan (Rp)</th>
                            <th class="p-3 w-48 text-right">Subtotal</th>
                            <th class="p-3 w-14 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="p-2">
                                    <select :name="'items[' + index + '][material_id]'" x-model="item.material_id" @change="onMaterialChange(item)" class="form-select text-sm" required>
                                        <option value="">-- Pilih Material --</option>
                                        @foreach($materials as $m)
                                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $m->name }} ({{ $m->unit?->symbol ?? 'pcs' }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-2">
                                    <input type="number" step="0.01" min="0.01" :name="'items[' + index + '][qty]'" x-model="item.qty" @input="calcItem(item)" class="form-input text-sm text-right" required>
                                </td>
                                <td class="p-2">
                                    <input type="number" step="100" min="0" :name="'items[' + index + '][unit_price]'" x-model="item.unit_price" @input="calcItem(item)" class="form-input text-sm text-right" required>
                                </td>
                                <td class="p-2 text-right font-medium font-mono text-slate-800" x-text="formatRupiah(item.total)"></td>
                                <td class="p-2 text-center">
                                    <button type="button" @click="removeItem(index)" class="text-rose-500 hover:text-rose-700 p-1 rounded hover:bg-rose-50" :disabled="items.length === 1">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Financial Summary Box -->
            <div class="mt-6 flex justify-end">
                <div class="w-full md:w-80 bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-2 text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal Item:</span>
                        <span class="font-mono font-medium" x-text="formatRupiah(subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>PPN (<span x-text="taxPercent"></span>%):</span>
                        <span class="font-mono font-medium" x-text="formatRupiah(taxAmount)"></span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Ongkos Kirim:</span>
                        <span class="font-mono font-medium" x-text="formatRupiah(shippingFee)"></span>
                    </div>
                    <div class="border-t border-slate-200 pt-2 flex justify-between font-bold text-navy-950 text-base">
                        <span>Grand Total PO:</span>
                        <span class="font-mono text-construction-600" x-text="formatRupiah(grandTotal)"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('purchase-orders.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Terbitkan Purchase Order</button>
        </div>
    </form>
</div>
@endsection
