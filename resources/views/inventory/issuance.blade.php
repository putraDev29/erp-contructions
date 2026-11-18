@extends('layouts.app')

@section('title', 'Pengeluaran Material ke Proyek')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Keluarkan Material ke Proyek</h1>
            <p class="text-sm text-slate-500 mt-1">Mengurangi stok persediaan gudang dan membebankannya secara langsung sebagai Biaya Material Aktual Proyek.</p>
        </div>
        <a href="{{ route('inventory.index') }}" class="btn-secondary text-sm">Kembali</a>
    </div>

    <form method="POST" action="{{ route('inventory.issuance.store') }}" 
          x-data="{
              items: [
                  { material_id: '', qty: 1, notes: '' }
              ],
              addItem() {
                  this.items.push({ material_id: '', qty: 1, notes: '' });
              },
              removeItem(index) {
                  if (this.items.length > 1) {
                      this.items.splice(index, 1);
                  }
              }
          }" 
          class="card p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="form-label required">Gudang Asal</label>
                <select name="warehouse_id" class="form-select" required>
                    <option value="">-- Pilih Gudang Sumber --</option>
                    @foreach($warehouses as $w)
                        <option value="{{ $w->id }}" {{ old('warehouse_id') == $w->id ? 'selected' : '' }}>
                            {{ $w->name }} ({{ $w->code }})
                        </option>
                    @endforeach
                </select>
                @error('warehouse_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label required">Proyek Konstruksi Tujuan</label>
                <select name="project_id" class="form-select" required>
                    <option value="">-- Pilih Proyek Penerima --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ old('project_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->code }} - {{ $p->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label required">Tanggal Pengeluaran Lapangan</label>
                <input type="date" name="transaction_date" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>

            <div>
                <label class="form-label">Catatan Pemakaian</label>
                <input type="text" name="notes" class="form-input" placeholder="Misal: Pemasangan kolom baja lantai 2">
            </div>
        </div>

        <!-- Dynamic Items Table -->
        <div class="border-t border-slate-100 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-navy-900">Daftar Material yang Dikeluarkan</h3>
                <button type="button" @click="addItem()" class="btn-secondary text-xs inline-flex items-center gap-1.5">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Material</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border border-slate-200 rounded-lg">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">Material</th>
                            <th class="p-3 w-36">Kuantitas</th>
                            <th class="p-3">Keterangan Khusus</th>
                            <th class="p-3 w-14 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="p-2">
                                    <select :name="'items[' + index + '][material_id]'" x-model="item.material_id" class="form-select text-sm" required>
                                        <option value="">-- Pilih Material --</option>
                                        @foreach($materials as $m)
                                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $m->name }} ({{ $m->unit?->symbol ?? 'pcs' }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-2">
                                    <input type="number" step="0.01" min="0.01" :name="'items[' + index + '][qty]'" x-model="item.qty" class="form-input text-sm text-right" required>
                                </td>
                                <td class="p-2">
                                    <input type="text" :name="'items[' + index + '][notes]'" x-model="item.notes" class="form-input text-sm" placeholder="Opsional...">
                                </td>
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
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('inventory.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Proses Pengeluaran Material</button>
        </div>
    </form>
</div>
@endsection
