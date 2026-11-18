@extends('layouts.auth')

@section('title', 'Masuk ke Sistem ERP')

@section('content')
<div class="auth-card rounded-2xl p-6 sm:p-8 backdrop-blur-xl relative overflow-hidden">
    <!-- Top Decorative Line -->
    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-construction-600 via-construction-500 to-amber-400"></div>

    <!-- Brand Header -->
    <div class="text-center mb-6 pt-2">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-construction-600 to-construction-400 mx-auto flex items-center justify-center text-white shadow-xl shadow-construction-600/50 mb-3 border border-construction-400/30">
            <i data-lucide="hard-hat" class="w-9 h-9"></i>
        </div>
        <h1 class="text-2xl font-black text-white tracking-tight flex items-center justify-center gap-2">
            CONSTRUCTA <span class="text-construction-500">ERP</span>
        </h1>
        <p class="text-xs text-slate-400 mt-1">Sistem Manajemen Kontraktor & Konstruksi Terpadu</p>
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-construction-500/10 border border-construction-500/30 text-[11px] text-construction-400 font-semibold mt-2.5">
            <span class="w-1.5 h-1.5 rounded-full bg-construction-500 animate-pulse"></span>
            PT Konstruksi Maju Nusantara
        </div>
    </div>

    <!-- Error Flash Alerts -->
    @if($errors->any())
        <div class="mb-5 p-3.5 rounded-xl bg-rose-500/15 border border-rose-500/40 text-rose-300 text-xs flex items-start gap-2.5">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400 flex-shrink-0 mt-0.5"></i>
            <div>
                <strong class="font-bold block">Gagal Masuk:</strong>
                <span>{{ $errors->first() }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 p-3.5 rounded-xl bg-rose-500/15 border border-rose-500/40 text-rose-300 text-xs flex items-start gap-2.5">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400 flex-shrink-0 mt-0.5"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Login Form -->
    <form method="POST" action="{{ route('login.post') }}" class="space-y-4" id="loginForm">
        @csrf

        <!-- Email Field -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-300 mb-1.5 uppercase tracking-wider">
                Alamat Email Pengguna
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </div>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', 'admin@konstruksimaju.co.id') }}" 
                       required 
                       autofocus
                       class="w-full pl-10 pr-3.5 py-3 bg-navy-950/80 border border-navy-700/80 rounded-xl text-white text-sm focus:ring-2 focus:ring-construction-500 focus:border-construction-500 placeholder-slate-500 transition-all outline-none"
                       placeholder="nama@konstruksimaju.co.id">
            </div>
        </div>

        <!-- Password Field -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">
                    Kata Sandi
                </label>
                <span class="text-[11px] text-slate-400">Default dev: <code class="text-construction-400 font-mono">Admin123!</code></span>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <input type="password" 
                       id="password" 
                       name="password" 
                       value="Admin123!" 
                       required
                       class="w-full pl-10 pr-10 py-3 bg-navy-950/80 border border-navy-700/80 rounded-xl text-white text-sm focus:ring-2 focus:ring-construction-500 focus:border-construction-500 placeholder-slate-500 transition-all outline-none"
                       placeholder="••••••••">
                <button type="button" 
                        onclick="togglePasswordVisibility()" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition-colors"
                        title="Tampilkan/Sembunyikan Sandi">
                    <i data-lucide="eye" id="eyeIcon" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between pt-1 text-xs text-slate-400">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="remember" checked class="w-4 h-4 rounded bg-navy-950 border-navy-700 text-construction-500 focus:ring-construction-500">
                <span>Ingat saya di perangkat ini</span>
            </label>
            <span class="text-[11px] text-slate-400">Timezone: Asia/Jakarta</span>
        </div>

        <!-- PRIMARY LOGIN BUTTON (Unmistakable & Prominent) -->
        <div class="pt-2">
            <button type="submit" 
                    id="btn-login-submit"
                    class="btn-login-primary w-full py-3.5 px-6 rounded-xl font-bold text-sm tracking-wide flex items-center justify-center gap-2.5 transition-all duration-200 uppercase shadow-lg">
                <i data-lucide="log-in" class="w-5 h-5 flex-shrink-0"></i>
                <span class="text-sm font-extrabold tracking-wider">MASUK KE SISTEM (LOGIN)</span>
                <i data-lucide="arrow-right" class="w-4 h-4 flex-shrink-0"></i>
            </button>
        </div>
    </form>

    <!-- Quick Development Demo Login Buttons -->
    <div class="mt-6 pt-5 border-t border-navy-800/80">
        <div class="flex items-center justify-between mb-3">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                Akun Demo Cepat (Klik untuk masuk)
            </p>
            <span class="text-[10px] bg-navy-800 text-slate-300 px-2 py-0.5 rounded-full font-mono">10 Role Aktif</span>
        </div>
        <div class="grid grid-cols-2 gap-2 text-[11px]">
            <button type="button" 
                    onclick="fillAndSubmit('admin@konstruksimaju.co.id')" 
                    class="p-2.5 rounded-xl bg-navy-950 hover:bg-navy-800/80 text-slate-300 hover:text-white border border-navy-700/70 hover:border-construction-500/50 text-left transition-all group flex flex-col justify-between">
                <div>
                    <span class="font-bold block text-white group-hover:text-construction-400 transition-colors">Super Admin</span>
                    <span class="text-[10px] text-slate-400">admin@...</span>
                </div>
                <span class="text-[9px] text-construction-400/80 font-medium mt-1 flex items-center gap-1">
                    Masuk Langsung <i data-lucide="chevron-right" class="w-3 h-3"></i>
                </span>
            </button>

            <button type="button" 
                    onclick="fillAndSubmit('direktur@konstruksimaju.co.id')" 
                    class="p-2.5 rounded-xl bg-navy-950 hover:bg-navy-800/80 text-slate-300 hover:text-white border border-navy-700/70 hover:border-construction-500/50 text-left transition-all group flex flex-col justify-between">
                <div>
                    <span class="font-bold block text-white group-hover:text-construction-400 transition-colors">Direktur / Owner</span>
                    <span class="text-[10px] text-slate-400">direktur@...</span>
                </div>
                <span class="text-[9px] text-construction-400/80 font-medium mt-1 flex items-center gap-1">
                    Masuk Langsung <i data-lucide="chevron-right" class="w-3 h-3"></i>
                </span>
            </button>

            <button type="button" 
                    onclick="fillAndSubmit('pm@konstruksimaju.co.id')" 
                    class="p-2.5 rounded-xl bg-navy-950 hover:bg-navy-800/80 text-slate-300 hover:text-white border border-navy-700/70 hover:border-construction-500/50 text-left transition-all group flex flex-col justify-between">
                <div>
                    <span class="font-bold block text-white group-hover:text-construction-400 transition-colors">Project Manager</span>
                    <span class="text-[10px] text-slate-400">pm@...</span>
                </div>
                <span class="text-[9px] text-construction-400/80 font-medium mt-1 flex items-center gap-1">
                    Masuk Langsung <i data-lucide="chevron-right" class="w-3 h-3"></i>
                </span>
            </button>

            <button type="button" 
                    onclick="fillAndSubmit('finance@konstruksimaju.co.id')" 
                    class="p-2.5 rounded-xl bg-navy-950 hover:bg-navy-800/80 text-slate-300 hover:text-white border border-navy-700/70 hover:border-construction-500/50 text-left transition-all group flex flex-col justify-between">
                <div>
                    <span class="font-bold block text-white group-hover:text-construction-400 transition-colors">Finance / Accounting</span>
                    <span class="text-[10px] text-slate-400">finance@...</span>
                </div>
                <span class="text-[9px] text-construction-400/80 font-medium mt-1 flex items-center gap-1">
                    Masuk Langsung <i data-lucide="chevron-right" class="w-3 h-3"></i>
                </span>
            </button>
        </div>
    </div>
</div>

<script>
function fillAndSubmit(email) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = 'Admin123!';
    document.getElementById('loginForm').submit();
}

function togglePasswordVisibility() {
    const pwd = document.getElementById('password');
    const eye = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        if (eye) eye.setAttribute('data-lucide', 'eye-off');
    } else {
        pwd.type = 'password';
        if (eye) eye.setAttribute('data-lucide', 'eye');
    }
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}
</script>
@endsection
