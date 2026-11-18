@extends('layouts.app')

@section('title', 'Ubah Password')
@section('page_title', 'Keamanan Akun')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-navy-950">Ubah Kata Sandi</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pastikan akun Anda menggunakan kata sandi yang kuat dan unik.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden p-6 sm:p-8">
        <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password Saat Ini</label>
                <input type="password" id="current_password" name="current_password" required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white transition-all">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password Baru (Min. 8 karakter)</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white transition-all">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 focus:ring-2 focus:ring-navy-500 focus:bg-white transition-all">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="btn-primary">
                    <i data-lucide="key" class="w-4 h-4"></i>
                    <span>Perbarui Password</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
