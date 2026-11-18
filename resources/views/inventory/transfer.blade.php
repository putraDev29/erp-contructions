@extends('layouts.app')

@section('title', 'Transfer Antar Gudang')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Transfer Antar Gudang</h1>
            <p class="text-sm text-slate-500 mt-1">Pindahkan fisik material dari satu gudang ke gudang proyek/cabang lain.</p>
        </div>
        <a href="{{ route('inventory.index') }}" class="btn-secondary text-sm">Kembali</a>
    </div>

    <form method="POST" action="{{ route('inventory.transfer.store') }}" 
          x-data="{
              items: [
                  { material_id: '', qty: 1 }
              ],
              addItem() {
                  this.items.push({ material_id: '', qty: 1 });
              },
              removeItem(index) {
                  if (this.items.length > 1) {
                      this.items.splice(index, 1);
                  }
              }
          }" 
          class="card p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="form-label required">Gudang Asal</label>
                <select name="from_warehouse_id" class="form-select" required>
                    <option value="">-- Pilih Asal --</option>
                    @foreach($warehouses as $w)
                        <option value="{{ $w->id }}" {{ old('from_warehouse_id') == $w->id ? 'selected' : '' }}>
                            {{ $w->name }} ({{ $w->code }})
                        </option>
                    @endforeach
                </select>
                @error('from_warehouse_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label required">Gudang Tujuan</label>
                <select name="to_warehouse_id" class="form-select" required>
                    <option value="">-- Pilih Tujuan --</option>
                    @foreach($warehouses as $w)
                        <option value="{{ $w->id }}" {{ old('to_warehouse_id') == $w->id ? 'selected' : '' }}>
                            {{ $w->name }} ({{ $w->code }})
                        </option>
                    @endforeach
                </select>
                @error('to_warehouse_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label required">Tanggal Transfer</label>
                <input type="date" name="transaction_date" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="md:col-span-3">
                <label class="form-label">Catatan Mutasi</label>
                <input type="text" name="notes" class="form-input" placeholder="Misal: Penyesuaian kebutuhan besi ke site Karawang">
            </div>
        </div>

        <!-- Dynamic Items Table -->
        <div class="border-t border-slate-100 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-navy-900">Daftar Material yang Ditransfer</h3>
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
                            <th class="p-3 w-40">Kuantitas</th>
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
            <button type="submit" class="btn-primary">Proses Transfer Antar Gudang</button>
        </div>
    </form>
</div>
@endsection
