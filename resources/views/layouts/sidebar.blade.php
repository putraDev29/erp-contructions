<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed inset-y-0 left-0 z-50 w-64 lg:static lg:block flex-shrink-0 bg-navy-950 text-slate-300 transition-transform duration-300 ease-in-out border-r border-navy-900 shadow-xl flex flex-col">

    <!-- Brand Header -->
    <div class="h-16 flex items-center justify-between px-5 bg-navy-900/80 border-b border-navy-800/80">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-construction-600 to-construction-400 flex items-center justify-center text-white shadow-md shadow-construction-600/30 group-hover:scale-105 transition-transform duration-200">
                <i data-lucide="hard-hat" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-base font-extrabold tracking-tight text-white block leading-none">CONSTRUCTA</span>
                <span class="text-[10px] font-semibold tracking-wider text-construction-400 uppercase mt-1 block">Contractor ERP</span>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Navigation List -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6 text-sm">

        <!-- Section: Overview -->
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">Overview</div>
            <nav class="space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-construction-600 text-white shadow-sm' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span>Dashboard Eksekutif</span>
                </a>
            </nav>
        </div>

        <!-- Section: Manajemen Proyek & RAB -->
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">Operasional Proyek</div>
            <nav class="space-y-1">
                <a href="{{ route('projects.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('projects.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                    <span>Proyek & Kontrak</span>
                </a>
                <a href="{{ route('budgets.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('budgets.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="calculator" class="w-4 h-4"></i>
                    <span>Estimasi & RAB</span>
                </a>
                <a href="{{ route('variation-orders.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('variation-orders.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="git-pull-request" class="w-4 h-4"></i>
                    <span>Variation Order (VO)</span>
                </a>
            </nav>
        </div>

        <!-- Section: Pengadaan & Logistik -->
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">Pengadaan & Material</div>
            <nav class="space-y-1">
                <a href="{{ route('material-requests.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('material-requests.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                    <span>Permintaan Material (MR)</span>
                </a>
                <a href="{{ route('purchase-orders.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('purchase-orders.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    <span>Purchase Order (PO)</span>
                </a>
                <a href="{{ route('goods-receipts.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('goods-receipts.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="package-check" class="w-4 h-4"></i>
                    <span>Penerimaan Barang (GR)</span>
                </a>
                <a href="{{ route('inventory.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('inventory.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="boxes" class="w-4 h-4"></i>
                    <span>Gudang & Mutasi Stok</span>
                </a>
            </nav>
        </div>

        <!-- Section: Tenaga Kerja & Subkontraktor -->
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">Tenaga Kerja & Subkon</div>
            <nav class="space-y-1">
                <a href="{{ route('employees.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('employees.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    <span>Tenaga Kerja & Tukang</span>
                </a>
                <a href="{{ route('attendances.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('attendances.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="calendar-check" class="w-4 h-4"></i>
                    <span>Absensi Lapangan</span>
                </a>
                <a href="{{ route('payrolls.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('payrolls.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                    <span>Payroll & Slip Upah</span>
                </a>
                <a href="{{ route('subcontractors.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('subcontractors.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="hammer" class="w-4 h-4"></i>
                    <span>Subkontraktor & BAP</span>
                </a>
                <a href="{{ route('equipment.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('equipment.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                    <span>Alat Berat & Kendaraan</span>
                </a>
            </nav>
        </div>

        <!-- Section: Finansial, Kas & Bank -->
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">Kas & Piutang / Utang</div>
            <nav class="space-y-1">
                <a href="{{ route('customer-invoices.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('customer-invoices.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                    <span>Invoice Pelanggan (AR)</span>
                </a>
                <a href="{{ route('supplier-invoices.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('supplier-invoices.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    <span>Hutang Supplier (AP)</span>
                </a>
                <a href="{{ route('finance.cash-bank') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('finance.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                    <span>Kas & Rekening Bank</span>
                </a>
            </nav>
        </div>

        <!-- Section: Akuntansi & Laporan Keuangan -->
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">Akuntansi & Laporan</div>
            <nav class="space-y-1">
                <a href="{{ route('accounting.coa') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('accounting.coa') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="list-tree" class="w-4 h-4"></i>
                    <span>Bagan Akun (COA)</span>
                </a>
                <a href="{{ route('accounting.journals') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('accounting.journals*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    <span>Jurnal Umum</span>
                </a>
                <a href="{{ route('reports.cost-control') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('reports.cost-control') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                    <span>Cost Control Proyek</span>
                </a>
                <a href="{{ route('reports.project-profit-loss') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('reports.project-profit-loss') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="pie-chart" class="w-4 h-4"></i>
                    <span>Laba Rugi Proyek</span>
                </a>
                <a href="{{ route('reports.financial') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('reports.financial') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    <span>Laporan Keuangan</span>
                </a>
            </nav>
        </div>

        <!-- Section: Master Data & Setting -->
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">Konfigurasi</div>
            <nav class="space-y-1">
                <a href="{{ route('materials.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('materials.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                    <span>Master Material</span>
                </a>
                <a href="{{ route('master.partners') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('master.partners*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="handshake" class="w-4 h-4"></i>
                    <span>Pelanggan & Supplier</span>
                </a>
                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('users.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    <span>Pengguna & Role</span>
                </a>
                <a href="{{ route('settings.company') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('settings.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    <span>Pengaturan ERP</span>
                </a>
                <a href="{{ route('audit-logs.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition-colors {{ request()->routeIs('audit-logs.*') ? 'bg-navy-800 text-white font-semibold border-l-4 border-construction-500' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
                    <i data-lucide="history" class="w-4 h-4"></i>
                    <span>Audit Trail Log</span>
                </a>
            </nav>
        </div>

    </div>

    <!-- User Mini Profile Footer -->
    <div class="p-3 bg-navy-900/60 border-t border-navy-800/80">
        <div class="flex items-center gap-3 px-2 py-2 rounded-lg">
            <div class="w-9 h-9 rounded-full bg-navy-700 border border-navy-600 flex items-center justify-center text-white font-bold text-sm">
                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Guest User' }}</p>
                <p class="text-[11px] text-construction-400 truncate">{{ auth()->user()->role?->display_name ?? 'User' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" title="Keluar dari sistem" class="text-slate-400 hover:text-rose-400 p-1.5 rounded-md hover:bg-navy-800 transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
