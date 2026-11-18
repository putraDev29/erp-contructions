<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-30 shadow-xs">
    <!-- Left: Sidebar Toggle & Breadcrumb -->
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" 
                class="lg:hidden p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-300">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        <!-- Breadcrumbs -->
        <nav class="hidden sm:flex items-center gap-2 text-xs font-medium text-slate-500">
            <a href="{{ route('dashboard') }}" class="hover:text-navy-900 flex items-center gap-1">
                <i data-lucide="home" class="w-3.5 h-3.5"></i>
                <span>ERP</span>
            </a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
            <span class="text-navy-900 font-semibold truncate">@yield('page_title', 'Dashboard')</span>
        </nav>
    </div>

    <!-- Right Actions -->
    <div class="flex items-center gap-3 sm:gap-4">

        <!-- Active Project Quick Switcher -->
        <div class="hidden md:flex items-center text-xs">
            @php
                $headerProjects = \App\Models\Project::whereIn('status', ['in_progress', 'contract', 'preparation'])->orderBy('name')->take(10)->get();
                $currentProjectId = request('project_id');
            @endphp
            <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-1.5">
                @foreach(request()->except('project_id') as $key => $val)
                    @if(is_string($val) || is_numeric($val))
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                <label for="header_project_filter" class="text-slate-500 font-medium whitespace-nowrap flex items-center gap-1">
                    <i data-lucide="building-2" class="w-3.5 h-3.5 text-construction-600"></i>
                    <span>Proyek Aktif:</span>
                </label>
                <select id="header_project_filter" name="project_id" onchange="this.form.submit()" 
                        class="bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-lg focus:ring-navy-500 focus:border-navy-500 py-1 px-2.5 max-w-[200px] truncate">
                    <option value="">-- Semua Proyek --</option>
                    @foreach($headerProjects as $hp)
                        <option value="{{ $hp->id }}" {{ $currentProjectId == $hp->id ? 'selected' : '' }}>
                            {{ $hp->project_code }} - {{ $hp->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Notification Bell Dropdown -->
        @php
            $unreadNotifications = auth()->user() ? auth()->user()->notifications()->where('is_read', false)->latest()->take(5)->get() : collect();
            $unreadCount = auth()->user() ? auth()->user()->notifications()->where('is_read', false)->count() : 0;
        @endphp
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    class="relative p-2 text-slate-500 hover:text-navy-900 hover:bg-slate-100 rounded-lg focus:outline-none transition-colors">
                <i data-lucide="bell" class="w-5 h-5"></i>
                @if($unreadCount > 0)
                    <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white ring-2 ring-white">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-75" 
                 x-transition:leave-start="transform opacity-100 scale-100" 
                 x-transition:leave-end="transform opacity-0 scale-95" 
                 class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-slate-200 py-2 z-50 divide-y divide-slate-100"
                 style="display: none;">
                
                <div class="px-4 py-2 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Notifikasi Sistem</span>
                    @if($unreadCount > 0)
                        <span class="text-[10px] bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full font-semibold">{{ $unreadCount }} Baru</span>
                    @endif
                </div>

                <div class="max-h-72 overflow-y-auto divide-y divide-slate-50 text-xs">
                    @forelse($unreadNotifications as $n)
                        <a href="{{ $n->url ?: '#' }}" class="block px-4 py-2.5 hover:bg-slate-50 transition-colors">
                            <p class="font-semibold text-slate-800">{{ $n->title }}</p>
                            <p class="text-slate-500 text-[11px] mt-0.5 line-clamp-2">{{ $n->message }}</p>
                            <p class="text-slate-400 text-[10px] mt-1">{{ $n->created_at->diffForHumans() }}</p>
                        </a>
                    @empty
                        <div class="px-4 py-6 text-center text-slate-400 text-xs">
                            <i data-lucide="check-circle-2" class="w-8 h-8 mx-auto mb-1 text-slate-300"></i>
                            Tidak ada notifikasi baru
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="relative" x-data="{ userMenu: false }">
            <button @click="userMenu = !userMenu" 
                    class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-slate-100 focus:outline-none transition-colors">
                <div class="w-8 h-8 rounded-full bg-navy-900 text-white font-bold text-xs flex items-center justify-center ring-2 ring-slate-200">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="hidden sm:block text-left text-xs">
                    <p class="font-semibold text-slate-800 leading-tight">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                    <p class="text-[10px] text-slate-500 leading-tight">{{ auth()->user()->role?->display_name ?? 'Staff' }}</p>
                </div>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="userMenu" 
                 @click.away="userMenu = false" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-75" 
                 x-transition:leave-start="transform opacity-100 scale-100" 
                 x-transition:leave-end="transform opacity-0 scale-95" 
                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 py-1 z-50 text-xs"
                 style="display: none;">
                
                <div class="px-4 py-2 border-b border-slate-100">
                    <p class="font-bold text-slate-800">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                    <p class="text-slate-500 truncate text-[11px]">{{ auth()->user()->email ?? '-' }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50 transition-colors">
                    <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                    <span>Profil Pengguna</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50 transition-colors">
                    <i data-lucide="key" class="w-4 h-4 text-slate-400"></i>
                    <span>Ubah Password</span>
                </a>

                <div class="border-t border-slate-100 mt-1 pt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-rose-600 hover:bg-rose-50 transition-colors font-medium">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            <span>Keluar Sistem</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>
