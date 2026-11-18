<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | CONSTRUCTA ERP - Kontraktor & Manajemen Konstruksi</title>

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN for instant, guaranteed rendering -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            50: '#f0f4f9',
                            100: '#d9e2ee',
                            200: '#b4c7dc',
                            300: '#83a4c5',
                            400: '#557fa9',
                            500: '#386591',
                            600: '#2b5077',
                            700: '#234161',
                            800: '#1b334d',
                            900: '#0f1f33',
                            950: '#0a1424',
                        },
                        construction: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons & Chart.js CDNs -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Local compiled Vite Assets -->
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
        }

        /* Card System */
        .card {
            background-color: #ffffff;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.03);
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.07), 0 2px 4px -2px rgba(15, 23, 42, 0.05);
        }
        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-body {
            padding: 1.25rem;
        }
        .card-footer {
            padding: 0.875rem 1.25rem;
            background-color: #f8fafc;
            border-top: 1px solid #f1f5f9;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }

        /* Table System */
        .table-container {
            width: 100%;
            overflow-x: auto;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
        }
        table.table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8125rem;
            text-align: left;
        }
        .table-th,
        table.table th,
        .table-container table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        .table-td,
        table.table td,
        .table-container table td {
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        tbody tr:hover > td.table-td,
        tbody tr:hover > td,
        tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Form Inputs & Selects */
        .form-input, .form-select, .form-textarea {
            width: 100%;
            border-radius: 0.5rem;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            color: #1e293b;
            line-height: 1.25rem;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #0f1f33;
            box-shadow: 0 0 0 3px rgba(15, 31, 51, 0.12);
        }
        .form-input::placeholder, .form-textarea::placeholder {
            color: #94a3b8;
        }
        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #475569;
            margin-bottom: 0.375rem;
        }

        /* Buttons */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: #0f1f33;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 150ms ease;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .btn-primary:hover {
            background-color: #1e3a5f;
            box-shadow: 0 4px 6px -1px rgba(15, 31, 51, 0.2);
        }
        .btn-accent {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: #ea580c;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(234, 88, 12, 0.2);
            transition: all 150ms ease;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .btn-accent:hover {
            background-color: #c2410c;
            box-shadow: 0 4px 6px -1px rgba(234, 88, 12, 0.3);
        }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: #ffffff;
            color: #334155;
            font-weight: 600;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            border: 1px solid #cbd5e1;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 150ms ease;
            cursor: pointer;
        }
        .btn-secondary:hover {
            background-color: #f8fafc;
            border-color: #94a3b8;
        }
        .btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: #e11d48;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            transition: all 150ms ease;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .btn-danger:hover {
            background-color: #be123c;
        }
        .btn-sm {
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            border-radius: 0.375rem;
        }
        .btn-xs {
            padding: 0.2rem 0.45rem;
            font-size: 0.7rem;
            border-radius: 0.25rem;
        }

        /* Badges */
        .badge, [class^="badge-"], [class*=" badge-"] {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            white-space: nowrap;
        }
        .badge-primary {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .badge-info {
            background-color: #e0f2fe;
            color: #0284c7;
            border: 1px solid #bae6fd;
        }
        .badge-orange {
            background-color: #ffedd5;
            color: #c2410c;
            border: 1px solid #fed7aa;
        }
        .badge-neutral {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* Custom Scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    
    @stack('styles')
</head>
<body class="h-full flex overflow-hidden text-slate-800" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden" 
         @click="sidebarOpen = false" 
         style="display: none;">
    </div>

    <!-- Sidebar Navigation -->
    @include('layouts.sidebar')

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">
        
        <!-- Header -->
        @include('layouts.header')

        <!-- Flash Toast Alerts -->
        @include('layouts.toast')

        <!-- Page Main Body -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
