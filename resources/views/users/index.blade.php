@extends('layouts.app')

@section('title', 'Manajemen Pengguna & Hak Akses (RBAC)')

@section('content')
<div class="space-y-6" x-data="{ openUserModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-950">Pengguna & Hak Akses (RBAC)</h1>
            <p class="text-sm text-slate-500 mt-1">Pengaturan akun login staf dan penetapan hak otorisasi peran (Role-Based Access Control).</p>
        </div>
        <button type="button" @click="openUserModal = true" class="btn-primary inline-flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Tambah Pengguna</span>
        </button>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="table-th">Nama Pengguna</th>
                        <th class="table-th">Email Login</th>
                        <th class="table-th">Peran (Role)</th>
                        <th class="table-th">No. Telepon</th>
                        <th class="table-th">Tanggal Terdaftar</th>
                        <th class="table-th text-center">Status Akun</th>
                        <th class="table-th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="table-td">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-navy-800 text-white font-bold text-xs flex items-center justify-center">
                                        {{ substr($u->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-navy-950">{{ $u->name }}</div>
                                        @if($u->id === auth()->id())
                                            <span class="text-[10px] text-construction-600 font-semibold font-mono">Akun Sedang Aktif</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="table-td font-mono text-xs text-slate-600">{{ $u->email }}</td>
                            <td class="table-td">
                                <span class="badge badge-primary text-xs">{{ $u->role?->display_name ?? 'User' }}</span>
                            </td>
                            <td class="table-td text-xs text-slate-600 whitespace-nowrap">{{ $u->phone ?? '-' }}</td>
                            <td class="table-td text-xs text-slate-700 whitespace-nowrap">@dateid($u->created_at)</td>
                            <td class="table-td text-center whitespace-nowrap">
                                @if($u->is_active)
                                    <span class="badge badge-success text-[10px]">Aktif</span>
                                @else
                                    <span class="badge badge-danger text-[10px]">Nonaktif</span>
                                @endif
                            </td>
                            <td class="table-td text-right whitespace-nowrap">
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.toggle-status', $u) }}" class="inline" onsubmit="return confirm('Ubah status keaktifan pengguna ini?')">
                                        @csrf
                                        <button type="submit" class="btn-secondary btn-xs text-xs font-semibold {{ $u->is_active ? 'text-rose-600 hover:bg-rose-50' : 'text-emerald-600 hover:bg-emerald-50' }}">
                                            {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400 italic">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <i data-lucide="shield-check" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                Belum ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH PENGGUNA -->
    <div x-show="openUserModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-navy-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-6" @click.away="openUserModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">Tambah Pengguna ERP Baru</h3>
                    <p class="text-xs text-slate-500">Buat kredensial login dan tentukan peran akses pengguna.</p>
                </div>
                <button type="button" @click="openUserModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label required">Nama Lengkap</label>
                    <input type="text" name="name" class="form-input text-sm" placeholder="Contoh: Ahmad Faisal, S.T." required>
                </div>

                <div>
                    <label class="form-label required">Email Login</label>
                    <input type="email" name="email" class="form-input text-sm" placeholder="faisal@konstruksimaju.co.id" required>
                </div>

                <div>
                    <label class="form-label required">Peran Jabatan (Role)</label>
                    <select name="role_id" class="form-select text-sm" required>
                        <option value="">-- Pilih Peran Akses --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }} ({{ $role->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Password Akun</label>
                        <input type="password" name="password" class="form-input text-sm" minlength="6" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div>
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-input text-sm" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openUserModal = false" class="btn-secondary text-sm">Batal</button>
                    <button type="submit" class="btn-primary text-sm">Buat Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
