<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - Pendidikan Ansuh | Yayasan Rumah Harapan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', var(--font-sans, system-ui, sans-serif);
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

        {{-- Komponen Sidebar Navigasi Kiri --}}
        @include('layouts.partials.sidebar')

        {{-- Konten Utama Halaman --}}
        <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
            
            {{-- Komponen Navbar Atas --}}
            @include('layouts.partials.navbar')

            {{-- Area Konten Halaman --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 max-w-7xl w-full mx-auto">
                @yield('content')
            </main>

            {{-- Footer Aplikasi --}}
            <footer class="mt-auto border-t border-emerald-100/60 bg-white/70 py-4 px-6 text-center text-xs text-slate-500">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
                    <p>© {{ date('Y') }} <strong class="text-emerald-800">Yayasan Rumah Harapan</strong> — Divisi Pendidikan Anak Asuh.</p>
                    <div class="flex items-center gap-4 text-[11px] text-slate-400">
                        <span>Portal Pendidikan v1.0</span>
                        <span>•</span>
                        <span class="text-emerald-600 font-medium">Sistem Terintegrasi Asrama</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    {{-- Script Interaktivitas Ringan (Vanilla JS) --}}
    <script>
        function toggleSidebar(open) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (open) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
            }
        }

        function toggleDropdown(id) {
            const el = document.getElementById(id);
            if (!el) return;
            const isHidden = el.classList.contains('hidden');
            
            // Tutup dropdown lain yang terbuka
            document.querySelectorAll('#notif-menu, #profile-menu').forEach(item => {
                if (item.id !== id) item.classList.add('hidden');
            });

            if (isHidden) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        }

        // Tutup dropdown saat klik di luar area
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#notification-wrapper') && !e.target.closest('#profile-wrapper')) {
                const notif = document.getElementById('notif-menu');
                const profile = document.getElementById('profile-menu');
                if (notif) notif.classList.add('hidden');
                if (profile) profile.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
