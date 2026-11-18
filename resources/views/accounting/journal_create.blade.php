@extends('layouts.app')

@section('title', 'Buat Jurnal Umum Manual')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Buat Jurnal Umum Manual</h1>
            <p class="text-sm text-slate-500 mt-1">Catat transaksi penyesuaian (adjusting journal) atau transaksi non-rutin dengan prinsip double-entry.</p>
        </div>
        <a href="{{ route('accounting.journals') }}" class="btn-secondary text-sm">Kembali</a>
    </div>

    <form method="POST" action="{{ route('accounting.journals.store') }}" 
          x-data="{
              lines: [
                  { coa_id: '', description: '', debit: 0, credit: 0 },
                  { coa_id: '', description: '', debit: 0, credit: 0 }
              ],
              addLine() {
                  this.lines.push({ coa_id: '', description: '', debit: 0, credit: 0 });
              },
              removeLine(index) {
                  if (this.lines.length > 2) {
                      this.lines.splice(index, 1);
                  }
              },
              get totalDebit() {
                  return this.lines.reduce((sum, line) => sum + (parseFloat(line.debit) || 0), 0);
              },
              get totalCredit() {
                  return this.lines.reduce((sum, line) => sum + (parseFloat(line.credit) || 0), 0);
              },
              get isBalanced() {
                  return Math.abs(this.totalDebit - this.totalCredit) < 0.01 && this.totalDebit > 0;
              },
              formatRupiah(val) {
                  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
              }
          }" 
          class="card p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="form-label required">Tanggal Jurnal</label>
                <input type="date" name="entry_date" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>

            <div>
                <label class="form-label">Proyek Terkait</label>
                <select name="project_id" class="form-select">
                    <option value="">-- Umum / Overhead Perusahaan --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label required">Keterangan / Uraian Utama Jurnal</label>
                <input type="text" name="description" class="form-input" placeholder="Contoh: Penyesuaian biaya sewa alat kantor" required>
            </div>
        </div>

        <!-- Dynamic Lines Table -->
        <div class="border-t border-slate-100 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-navy-900">Baris Debit & Kredit Jurnal</h3>
                <button type="button" @click="addLine()" class="btn-secondary text-xs inline-flex items-center gap-1.5">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Baris</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border border-slate-200 rounded-lg">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">Akun Akuntansi</th>
                            <th class="p-3">Uraian Baris</th>
                            <th class="p-3 w-40 text-right">Debit (IDR)</th>
                            <th class="p-3 w-40 text-right">Kredit (IDR)</th>
                            <th class="p-3 w-14 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <template x-for="(line, index) in lines" :key="index">
                            <tr>
                                <td class="p-2">
                                    <select :name="'lines[' + index + '][coa_id]'" x-model="line.coa_id" class="form-select text-xs" required>
                                        <option value="">-- Pilih Akun COA --</option>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }} ({{ strtoupper($acc->normal_balance) }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-2">
                                    <input type="text" :name="'lines[' + index + '][description]'" x-model="line.description" placeholder="Uraian opsional..." class="form-input text-xs">
                                </td>
                                <td class="p-2">
                                    <input type="number" step="100" min="0" :name="'lines[' + index + '][debit]'" x-model="line.debit" class="form-input text-xs font-mono text-right" required>
                                </td>
                                <td class="p-2">
                                    <input type="number" step="100" min="0" :name="'lines[' + index + '][credit]'" x-model="line.credit" class="form-input text-xs font-mono text-right" required>
                                </td>
                                <td class="p-2 text-center">
                                    <button type="button" @click="removeLine(index)" class="text-rose-500 hover:text-rose-700 p-1 rounded hover:bg-rose-50" :disabled="lines.length <= 2">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="bg-slate-50 font-bold text-xs border-t border-slate-200">
                        <tr>
                            <td colspan="2" class="p-3 text-right uppercase text-slate-500">Total Mutasi:</td>
                            <td class="p-3 text-right font-mono text-navy-950" x-text="formatRupiah(totalDebit)"></td>
                            <td class="p-3 text-right font-mono text-navy-950" x-text="formatRupiah(totalCredit)"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Balance Indicator -->
            <div class="mt-4 p-3 rounded-lg flex items-center justify-between text-xs"
                 :class="isBalanced ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200'">
                <div class="flex items-center gap-2">
                    <i :data-lucide="isBalanced ? 'check-circle' : 'alert-circle'" class="w-4 h-4"></i>
                    <span x-text="isBalanced ? 'JURNAL SEIMBANG (Total Debit = Total Kredit)' : 'JURNAL TIDAK SEIMBANG! Selisih: ' + formatRupiah(Math.abs(totalDebit - totalCredit))"></span>
                </div>
                <span class="font-mono font-bold" x-text="'Balance: ' + formatRupiah(totalDebit)"></span>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('accounting.journals') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary" :disabled="!isBalanced">Posting Jurnal Umum</button>
        </div>
    </form>
</div>
@endsection
