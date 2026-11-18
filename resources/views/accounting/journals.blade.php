@extends('layouts.app')

@section('title', 'Jurnal Umum (General Journals)')

@section('content')
<div class="space-y-6" x-data="{ openReverseModal: false, selectedEntryId: null, selectedEntryNo: '' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Jurnal Umum & Pembukuan</h1>
            <p class="text-sm text-slate-500 mt-1">Seluruh mutasi debit dan kredit otomatis dari transaksi operasional dan manual.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('accounting.general-ledger') }}" class="btn-secondary inline-flex items-center gap-2">
                <i data-lucide="book" class="w-4 h-4"></i>
                <span>Buku Besar</span>
            </a>
            <a href="{{ route('accounting.journals.create') }}" class="btn-primary inline-flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Buat Jurnal Manual</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card p-4">
        <form method="GET" action="{{ route('accounting.journals') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label text-xs">Pilih Proyek</label>
                <select name="project_id" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">Semua Proyek & Umum</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->code }} - {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-input text-sm" value="{{ request('start_date') }}">
            </div>
            <div>
                <label class="form-label text-xs">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-input text-sm" value="{{ request('end_date') }}">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary text-sm">Filter</button>
                <a href="{{ route('accounting.journals') }}" class="btn-secondary text-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- Journal Entries List -->
    <div class="space-y-4">
        @forelse($journals as $entry)
            <div class="card overflow-hidden">
                <div class="bg-slate-50 px-5 py-3 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-navy-950 font-mono text-sm">{{ $entry->entry_number }}</span>
                        <span class="text-slate-500">@dateid($entry->entry_date)</span>
                        @if($entry->project)
                            <span class="badge badge-neutral font-mono text-[10px]">{{ $entry->project->name }}</span>
                        @endif
                        @if($entry->is_reversal)
                            <span class="badge badge-danger text-[10px]">JURNAL PEMBALIK</span>
                        @elseif($entry->reversedByEntry)
                            <span class="badge badge-warning text-[10px]">DIBALIK OLEH {{ $entry->reversedByEntry->entry_number }}</span>
                        @else
                            <span class="badge badge-success text-[10px]">POSTED</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-slate-600 font-medium">{{ $entry->description }}</span>
                        @if(!$entry->is_reversal && !$entry->reversedByEntry && $entry->status === 'posted')
                            <button type="button" 
                                    @click="selectedEntryId = {{ $entry->id }}; selectedEntryNo = '{{ $entry->entry_number }}'; openReverseModal = true"
                                    class="text-rose-600 hover:text-rose-800 text-xs font-semibold hover:underline">
                                Balik Jurnal
                            </button>
                        @endif
                    </div>
                </div>

                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-100/60 text-slate-500 uppercase tracking-wider text-[10px]">
                            <tr>
                                <th class="py-2 px-5">Kode Akun</th>
                                <th class="py-2 px-5">Nama Akun</th>
                                <th class="py-2 px-5">Uraian Baris</th>
                                <th class="py-2 px-5 text-right w-36">Debit</th>
                                <th class="py-2 px-5 text-right w-36">Kredit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($entry->lines as $line)
                                <tr>
                                    <td class="py-2.5 px-5 font-mono text-slate-600">{{ $line->coa?->code }}</td>
                                    <td class="py-2.5 px-5 font-medium text-navy-900 {{ $line->credit > 0 ? 'pl-8' : '' }}">
                                        {{ $line->coa?->name }}
                                    </td>
                                    <td class="py-2.5 px-5 text-slate-500">{{ $line->description ?? '-' }}</td>
                                    <td class="py-2.5 px-5 text-right font-mono font-medium {{ $line->debit > 0 ? 'text-navy-950 font-bold' : 'text-slate-300' }}">
                                        {{ $line->debit > 0 ? 'Rp ' . number_format($line->debit, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="py-2.5 px-5 text-right font-mono font-medium {{ $line->credit > 0 ? 'text-navy-950 font-bold' : 'text-slate-300' }}">
                                        {{ $line->credit > 0 ? 'Rp ' . number_format($line->credit, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-slate-50/50 font-bold">
                                <td colspan="3" class="py-2 px-5 text-right text-slate-500 uppercase text-[10px]">Total Seimbang:</td>
                                <td class="py-2 px-5 text-right font-mono text-navy-950">@rupiah($entry->total_debit)</td>
                                <td class="py-2 px-5 text-right font-mono text-navy-950">@rupiah($entry->total_credit)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="card p-12 text-center text-slate-400">
                <i data-lucide="book-open" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                Tidak ada jurnal pembukuan yang ditemukan.
            </div>
        @endforelse

        <div class="p-4">
            {{ $journals->links() }}
        </div>
    </div>

    <!-- MODAL REVERSAL JURNAL -->
    <div x-show="openReverseModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-6" @click.away="openReverseModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Balik Jurnal Transaksi</h3>
                    <p class="text-xs text-rose-500 font-semibold" x-text="'Membalik jurnal ' + selectedEntryNo"></p>
                </div>
                <button type="button" @click="openReverseModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" :action="'/accounting/journals/' + selectedEntryId + '/reverse'" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label required">Alasan Koreksi / Pembalikan Jurnal</label>
                    <textarea name="reversal_reason" rows="3" class="form-input text-sm" placeholder="Contoh: Koreksi salah posting akun beban atau pembatalan transaksi" required></textarea>
                </div>

                <div class="p-3 bg-amber-50 rounded-lg text-amber-800 text-xs">
                    <i data-lucide="alert-triangle" class="w-4 h-4 inline mr-1"></i>
                    Jurnal asli tidak akan dihapus demi menjaga prinsip audit trail. Sistem akan membuat jurnal pembalik resmi yang menihilkan mutasi sebelumnya.
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openReverseModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm bg-rose-600 hover:bg-rose-700">Eksekusi Jurnal Pembalik</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
