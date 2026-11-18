@extends('layouts.app')

@section('title', 'Surat Perintah Kerja (SPK) Subkontraktor & BAP')

@section('content')
<div class="space-y-6" x-data="{ openContractModal: false, openBapModal: false, selectedContractId: null, selectedContractTitle: '' }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">SPK Subkontraktor & Berita Acara Progres</h1>
            <p class="text-sm text-slate-500 mt-1">Pengendalian kontrak borongan kerja subkon, sertifikasi progres fisik (BAP), dan tagihan termin.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('subcontractors.index') }}" class="btn-secondary text-sm">Daftar Subkon</a>
            <button type="button" @click="openContractModal = true" class="btn-primary inline-flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Terbitkan SPK Baru</span>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. SPK</th>
                        <th>Subkontraktor</th>
                        <th>Proyek</th>
                        <th>Lingkup Pekerjaan</th>
                        <th class="text-right">Nilai Kontrak SPK</th>
                        <th class="text-center">Retensi</th>
                        <th class="text-center">Progres Fisik</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contracts as $spk)
                        @php
                            $latestProgress = $spk->progressRecords ? $spk->progressRecords->sortByDesc('id')->first() : null;
                            $progressPct = $latestProgress ? (float) ($latestProgress->progress_percent ?: $latestProgress->approved_progress_percent) : 0;
                        @endphp
                        <tr>
                            <td class="font-bold text-navy-900 font-mono">{{ $spk->contract_number }}</td>
                            <td>
                                <div class="font-medium text-slate-800">{{ $spk->subcontractor?->name }}</div>
                                <div class="text-xs text-slate-400">{{ $spk->subcontractor?->specialty }}</div>
                            </td>
                            <td>
                                <div class="text-sm text-slate-800">{{ $spk->project?->name }}</div>
                                <div class="text-xs font-mono text-slate-400">{{ $spk->project?->code }}</div>
                            </td>
                            <td class="text-xs text-slate-600 max-w-xs truncate">{{ $spk->work_scope ?: $spk->scope_of_work }}</td>
                            <td class="text-right font-mono font-bold text-navy-950">@rupiah($spk->contract_amount)</td>
                            <td class="text-center text-xs font-medium">{{ $spk->retention_percent }}%</td>
                            <td class="text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <div class="w-16 bg-slate-200 rounded-full h-2">
                                        <div class="bg-construction-500 h-2 rounded-full" style="width: {{ min(100, $progressPct) }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold font-mono text-slate-700">{{ $progressPct }}%</span>
                                </div>
                            </td>
                            <td class="text-right">
                                <button type="button" 
                                        @click="selectedContractId = {{ $spk->id }}; selectedContractTitle = '{{ $spk->contract_number }} - {{ $spk->subcontractor?->name }}'; openBapModal = true" 
                                        class="btn-secondary text-xs px-2.5 py-1 inline-flex items-center gap-1">
                                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600"></i>
                                    <span>Buat BAP</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <i data-lucide="file-text" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada SPK subkontraktor yang diterbitkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $contracts->links() }}
        </div>
    </div>

    <!-- MODAL BUAT SPK SUBKONTRAKTOR -->
    <div x-show="openContractModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full p-6 space-y-6" @click.away="openContractModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Terbitkan SPK Subkontraktor</h3>
                    <p class="text-xs text-slate-500">Kontrak paket pekerjaan pihak ketiga yang dibebankan ke proyek.</p>
                </div>
                <button type="button" @click="openContractModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('subcontractors.contracts.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Subkontraktor</label>
                        <select name="subcontractor_id" class="form-select text-sm" required>
                            <option value="">-- Pilih Subkon --</option>
                            @foreach($subcontractors as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->specialty }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label required">Proyek Konstruksi</label>
                        <select name="project_id" class="form-select text-sm" required>
                            <option value="">-- Pilih Proyek --</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label required">Lingkup Pekerjaan</label>
                    <textarea name="work_scope" rows="2" class="form-input text-sm" placeholder="Contoh: Pekerjaan instalasi pipa air kotor dan bersih seluruh lantai 1-3" required></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Nilai Kontrak SPK (IDR)</label>
                        <input type="number" step="1000" min="1000" name="contract_amount" class="form-input text-sm font-mono font-bold" placeholder="50000000" required>
                    </div>
                    <div>
                        <label class="form-label">Retensi Pemeliharaan (%)</label>
                        <input type="number" step="0.5" min="0" max="20" name="retention_percent" value="5" class="form-input text-sm font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-input text-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="form-label required">Target Selesai</label>
                        <input type="date" name="end_date" class="form-input text-sm" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openContractModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Terbitkan SPK</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL BUAT BERITA ACARA PROGRES (BAP) -->
    <div x-show="openBapModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-6" @click.away="openBapModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Sertifikasi Berita Acara Progres (BAP)</h3>
                    <p class="text-xs text-slate-500" x-text="selectedContractTitle"></p>
                </div>
                <button type="button" @click="openBapModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('subcontractors.progress.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="subcontractor_contract_id" :value="selectedContractId">

                <div>
                    <label class="form-label required">Tanggal Inspeksi Lapangan</label>
                    <input type="date" name="inspection_date" class="form-input text-sm" value="{{ date('Y-m-d') }}" required>
                </div>

                <div>
                    <label class="form-label required">Kumulatif Progres Fisik yang Disahkan (%)</label>
                    <input type="number" step="0.1" min="1" max="100" name="progress_percent" class="form-input text-sm font-mono font-bold" placeholder="50" required>
                    <p class="text-[11px] text-slate-400 mt-1">Nilai sertifikasi akan dihitung proporsional dari nilai kontrak SPK.</p>
                </div>

                <div>
                    <label class="form-label">Catatan Hasil Pemeriksaan Lapangan</label>
                    <textarea name="notes" rows="2" class="form-input text-sm" placeholder="Kualitas pekerjaan rapi, lulus tes uji tekan"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openBapModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm bg-emerald-600 hover:bg-emerald-700">Sahkan BAP Progres</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
