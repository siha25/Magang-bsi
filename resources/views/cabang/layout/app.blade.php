{{-- BAGIAN: PENGURUS CABANG - Layout Utama Cabang --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('Cabang')) - {{ __('Pendidikan Ansuh | Yayasan Rumah Harapan') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        (function() {
            try {
                const currentLocale = '{{ app()->getLocale() }}';
                localStorage.setItem('app_locale', currentLocale);

                const savedTheme = localStorage.getItem('app_theme') || '{{ session("theme", auth()->user()?->theme ?? "light") }}';
                if (savedTheme === 'dark' || (savedTheme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', var(--font-sans, system-ui, sans-serif);
        }

        #sidebar { transition: transform .3s ease, width .3s ease; }

        @media (min-width: 1024px) {
            #sidebar.is-collapsed { width: 5rem !important; overflow: hidden; }
            #sidebar.is-collapsed span,
            #sidebar.is-collapsed p,
            #sidebar.is-collapsed .uppercase,
            #sidebar.is-collapsed a .flex-col { display: none !important; }
            #sidebar.is-collapsed > div:nth-child(2),
            #sidebar.is-collapsed > div:last-child { display: none; }
            #sidebar.is-collapsed > div:first-child { padding: 0; justify-content: center; }
            #sidebar.is-collapsed nav a { justify-content: center; }
        }
    </style>
</head>
<body class="h-full bg-[#f8faf9] text-slate-800 antialiased selection:bg-emerald-500 selection:text-white">

    <div class="min-h-screen flex">

        {{-- Overlay untuk Sidebar Mobile --}}
        <div id="sidebar-overlay"
             onclick="toggleSidebar(false)"
             class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs hidden transition-opacity lg:hidden"
             aria-hidden="true"></div>

        {{-- Komponen Sidebar Cabang --}}
        @include('cabang.layout.partials.sidebar')

        {{-- Konten Utama Halaman --}}
        <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">

            {{-- Komponen Navbar Cabang --}}
            @include('cabang.layout.partials.navbar')

            {{-- Area Konten Halaman --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 max-w-7xl w-full mx-auto">
                @yield('content')
            </main>

            {{-- Footer Aplikasi --}}
            <footer class="mt-auto border-t border-emerald-100/60 bg-white/70 py-4 px-6 text-center text-xs text-slate-500">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
                    <p>© {{ date('Y') }} <strong class="text-emerald-800">Yayasan Rumah Harapan</strong> — Divisi Pendidikan Anak Asuh.</p>
                    <div class="flex items-center gap-4 text-[11px] text-slate-400">
                        <span>Portal Pengurus Cabang</span>
                        <span>•</span>
                        <span class="text-emerald-600 font-medium">{{ \App\Shared\Auth\CabangSession::getNamaCabang() }}</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    {{-- Script Interaktivitas Ringan (Vanilla JS) --}}
    <script>
        function toggleSidebar(force) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar) return;

            if (window.innerWidth >= 1024) {
                const collapse = (typeof force === 'boolean') ? !force : !sidebar.classList.contains('is-collapsed');
                sidebar.classList.toggle('is-collapsed', collapse);
                try { localStorage.setItem('sidebar-collapsed', collapse ? '1' : '0'); } catch (e) {}
            } else {
                const open = (typeof force === 'boolean') ? force : sidebar.classList.contains('-translate-x-full');
                sidebar.classList.toggle('-translate-x-full', !open);
                sidebar.classList.toggle('translate-x-0', open);
                overlay.classList.toggle('hidden', !open);
                document.body.classList.toggle('overflow-hidden', open);
            }
        }

        (function () {
            try {
                if (window.innerWidth >= 1024 && localStorage.getItem('sidebar-collapsed') === '1') {
                    document.getElementById('sidebar')?.classList.add('is-collapsed');
                }
            } catch (e) {}
        })();

        function toggleUserMenu(force) {
            const menu = document.getElementById('user-menu');
            const btn = document.getElementById('user-btn');
            const chevron = document.getElementById('user-chevron');
            if (!menu) return;

            const isOpen = (typeof force === 'boolean') ? force : menu.classList.contains('hidden');
            if (isOpen) {
                menu.classList.remove('hidden');
                chevron?.classList.add('rotate-180');
                btn?.setAttribute('aria-expanded', 'true');
            } else {
                menu.classList.add('hidden');
                chevron?.classList.remove('rotate-180');
                btn?.setAttribute('aria-expanded', 'false');
            }
        }

        document.addEventListener('click', function (e) {
            const wrapper = document.getElementById('user-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                toggleUserMenu(false);
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const menu = document.getElementById('user-menu');
                if (menu && !menu.classList.contains('hidden')) {
                    toggleUserMenu(false);
                    document.getElementById('user-btn')?.focus();
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

