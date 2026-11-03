{{-- BAGIAN: PENGURUS CABANG - Navbar Cabang --}}
@php
    $headerTitle = trim($__env->yieldContent('header_title'));
    if (empty($headerTitle)) {
        if (request()->routeIs('cabang.dashboard')) {
            $headerTitle = 'Dashboard Cabang';
        } elseif (request()->routeIs('cabang.kegiatan-harian.*')) {
            $headerTitle = 'Kegiatan Harian';
        } elseif (request()->routeIs('cabang.hafalan-quran.*')) {
            $headerTitle = "Hafalan Al-Qur'an";
        } elseif (request()->routeIs('cabang.diniyah.*')) {
            $headerTitle = 'Pengetahuan Diniyah';
        } elseif (request()->routeIs('cabang.santri.*')) {
            $headerTitle = 'Data Santri';
        } else {
            $headerTitle = trim($__env->yieldContent('title', 'Cabang'));
        }
    }

    $user = auth()->user();
    $userName = $user ? $user->name : 'Pengurus Cabang';
    $namaCabang = \App\Shared\Auth\CabangSession::getNamaCabang();
    $daerahCabang = \App\Shared\Auth\CabangSession::getDaerah();
@endphp

<header class="sticky top-0 z-30 h-16 sm:h-20 bg-white/95 backdrop-blur-md border-b border-emerald-100/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between transition-all">
    
    {{-- Sisi Kiri: Judul Halaman Dinamis & Info Cabang --}}
    <div class="flex items-center min-w-0 gap-3">
        <button type="button" onclick="toggleSidebar()" aria-label="Buka/tutup sidebar"
            style="display:flex; align-items:center; justify-content:center; width:42px; height:42px; border:1px solid #d1fae5; border-radius:12px; background:#ecfdf5; color:#047857; cursor:pointer; flex-shrink:0;">
            <svg style="width:20px; height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="flex flex-col min-w-0">
            <h1 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight truncate leading-tight">
                {{ $headerTitle }}
            </h1>
            <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span class="font-medium text-emerald-800 truncate">{{ $namaCabang }}</span>
                <span>•</span>
                <span class="truncate">{{ $daerahCabang }}</span>
            </div>
        </div>
    </div>

    {{-- Sisi Kanan: Identitas Pengguna & Logout --}}
    <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
        @auth
        <div id="user-wrapper" class="relative">
            <button type="button" id="user-btn"
                onclick="document.getElementById('user-menu').classList.toggle('hidden'); document.getElementById('user-chevron').classList.toggle('rotate-180')"
                style="display:flex; align-items:center; gap:12px; padding:7px 18px 7px 7px; background-color:#ecfdf5; border:1px solid #a7f3d0; border-radius:9999px; cursor:pointer;">
                
                {{-- Avatar --}}
                <span style="display:flex; align-items:center; justify-content:center; width:38px; height:38px; min-width:38px; border-radius:9999px; background:linear-gradient(135deg,#0f766e,#059669); color:#ffffff; font-size:14px; font-weight:700; box-shadow:0 4px 8px rgba(5,150,105,.4);">
                    {{ strtoupper(substr($userName, 0, 2)) }}
                </span>

                {{-- Nama & Peran --}}
                <div class="hidden sm:flex flex-col text-left">
                    <span style="font-size:14px; font-weight:700; color:#0f172a; white-space:nowrap; line-height:1.2;">
                        {{ $userName }}
                    </span>
                    <span style="font-size:11px; color:#059669; font-weight:600;">
                        Pengurus Cabang
                    </span>
                </div>

                {{-- Chevron --}}
                <svg id="user-chevron" class="w-4 h-4 text-slate-600 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Dropdown Profil & Keluar --}}
            <div id="user-menu" class="hidden absolute right-0 mt-2 w-60 rounded-2xl border border-slate-200 bg-white shadow-xl z-50 p-2">
                <div class="px-3.5 py-2.5 border-b border-slate-100">
                    <p class="text-xs font-bold text-slate-800">{{ $userName }}</p>
                    <p class="text-[11px] text-emerald-700 truncate font-medium">{{ $namaCabang }} ({{ $daerahCabang }})</p>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0 pt-1">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer text-left">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>
</header>

