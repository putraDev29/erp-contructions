<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk ke Sistem') | CONSTRUCTA ERP</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Local compiled Vite Assets -->
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #0a1424;
            background-image: radial-gradient(rgba(249, 115, 22, 0.08) 1px, transparent 1px), radial-gradient(rgba(43, 80, 119, 0.15) 1px, #0a1424 1px);
            background-size: 32px 32px;
            background-position: 0 0, 16px 16px;
        }
        .auth-card {
            background-color: rgba(15, 31, 51, 0.95);
            border: 1px solid rgba(43, 80, 119, 0.6);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 20px rgba(249, 115, 22, 0.15);
        }
        .btn-login-primary {
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 10px 25px -5px rgba(234, 88, 12, 0.4);
            border: 1px solid rgba(251, 146, 60, 0.3);
            cursor: pointer;
        }
        .btn-login-primary:hover {
            background: linear-gradient(135deg, #f97316 0%, #fb923c 100%) !important;
            box-shadow: 0 15px 30px -5px rgba(234, 88, 12, 0.6);
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 text-slate-100 selection:bg-orange-500 selection:text-white">
    <div class="w-full max-w-md my-8">
        @yield('content')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
