@php
    $headerTitle = trim($__env->yieldContent('header_title'));
    if (empty($headerTitle)) {
        if (request()->routeIs('dashboard') || request()->is('/') || request()->is('dashboard')) {
            $headerTitle = 'Dashboard';
        } elseif (request()->routeIs('asrama.*') || request()->is('asrama*')) {
            $headerTitle = 'Data Asrama';
        } elseif (request()->routeIs('anak-asuh.*') || request()->is('anak-asuh*')) {
            $headerTitle = 'Data Anak Asuh';
        } elseif (request()->routeIs('cabang.*') || request()->is('cabang*')) {
            $headerTitle = 'Data Cabang';
        } else {
            $headerTitle = trim($__env->yieldContent('title', 'Dashboard'));
        }
    }

    $userName = auth()->check() ? auth()->user()->name : 'Admin';
    $userRole = auth()->check() ? (auth()->user()->role ?? 'Pengurus') : 'Pengurus';
@endphp

{{-- Topbar / Header Minimalis Pendidikan Ansuh --}}
<header class="sticky top-0 z-30 h-16 sm:h-20 bg-white/95 backdrop-blur-md border-b border-emerald-100/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between transition-all">
    
    {{-- Sisi Kiri: Tombol Toggle Sidebar Mobile & Judul Halaman Dinamis --}}
    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
        {{-- Tombol Toggle Sidebar (Khusus Mobile / Layar Kecil) --}}
        <button type="button" 
                onclick="toggleSidebar(true)" 
                class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 shrink-0"
                aria-label="Buka Menu Navigasi">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- Judul Halaman Aktif --}}
        <h1 class="text-base sm:text-lg lg:text-xl font-bold text-slate-900 tracking-tight truncate">
            {{ $headerTitle }}
        </h1>
    </div>

    {{-- Sisi Kanan: Identitas Pengguna Ringkas (Avatar, Nama Singkat, Peran) --}}
    <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
        <div class="flex items-center gap-2.5 sm:gap-3 px-2.5 sm:px-3.5 py-1.5 rounded-2xl bg-emerald-50/60 border border-emerald-100/80">
            {{-- Avatar Pengguna --}}
            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-xs shadow-emerald-600/20 shrink-0">
                @if(auth()->check() && !empty(auth()->user()->name))
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                @else
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
            </div>

            {{-- Nama Pengguna & Peran --}}
            <div class="text-left leading-tight hidden min-[360px]:block">
                <span class="block text-xs sm:text-sm font-bold text-slate-800 truncate max-w-[110px] sm:max-w-[150px]">
                    {{ $userName }}
                </span>
                <span class="block text-[10px] sm:text-[11px] font-medium text-emerald-700 truncate max-w-[110px] sm:max-w-[150px]">
                    {{ $userRole }}
                </span>
            </div>
        </div>
    </div>

</header>
