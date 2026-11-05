{{-- BAGIAN: PENGURUS CABANG - Navbar & Dropdown Profil Cabang --}}
@php
    $headerTitle = trim($__env->yieldContent('header_title'));
    if (empty($headerTitle)) {
        if (request()->routeIs('cabang.dashboard')) {
            $headerTitle = __('Dashboard Cabang');
        } elseif (request()->routeIs('cabang.profil.*')) {
            $headerTitle = __('Profil Cabang');
        } elseif (request()->routeIs('cabang.pengaturan.*')) {
            $headerTitle = __('Pengaturan');
        } elseif (request()->routeIs('cabang.kegiatan-harian.*')) {
            $headerTitle = __('Kegiatan Harian');
        } elseif (request()->routeIs('cabang.hafalan-quran.*')) {
            $headerTitle = __("Hafalan Al-Qur'an");
        } elseif (request()->routeIs('cabang.diniyah.*')) {
            $headerTitle = __('Pengetahuan Diniyah');
        } elseif (request()->routeIs('cabang.santri.*')) {
            $headerTitle = __('Data Santri');
        } else {
            $headerTitle = trim($__env->yieldContent('title', __('Cabang')));
        }
    }

    $user = auth()->user();
    $userName = $user ? $user->name : 'Pengurus Cabang';
    $cabang = \App\Shared\Auth\CabangSession::getCabang();
    $namaCabang = $cabang?->nama_asrama ?? \App\Shared\Auth\CabangSession::getNamaCabang();
    $kodeCabang = $cabang?->kode_asrama ?? 'CBG';
    $daerahCabang = $cabang?->wilayah ?? \App\Shared\Auth\CabangSession::getDaerah();
    $currentLocale = app()->getLocale();
@endphp

<header class="sticky top-0 z-30 h-16 sm:h-20 bg-white/95 backdrop-blur-md border-b border-emerald-100/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between transition-all">
    
    {{-- Sisi Kiri: Tombol Sidebar & Judul Halaman Dinamis --}}
    <div class="flex items-center min-w-0 gap-3">
        <button type="button" onclick="toggleSidebar()" aria-label="{{ __('Buka/tutup sidebar') }}"
            class="flex items-center justify-center w-10 h-10 sm:w-11 sm:h-11 border border-emerald-200/80 rounded-xl bg-emerald-50 text-emerald-800 hover:bg-emerald-100 hover:text-emerald-900 transition-colors shrink-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                <span>&bull;</span>
                <span class="truncate">{{ $daerahCabang }}</span>
            </div>
        </div>
    </div>

    {{-- Sisi Kanan: Profil Cabang & Dropdown Interaktif --}}
    <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
        @auth
        <div id="user-wrapper" class="relative">
            {{-- Tombol Pemicu Dropdown --}}
            <button type="button" 
                    id="user-btn"
                    onclick="toggleUserMenu()"
                    aria-haspopup="true"
                    aria-expanded="false"
                    class="flex items-center gap-2.5 sm:gap-3 py-1.5 pl-1.5 pr-3 sm:pr-4 bg-emerald-50/80 hover:bg-emerald-100/70 border border-emerald-200/80 rounded-full transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
                
                {{-- Avatar Inisial --}}
                <span class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-gradient-to-tr from-emerald-700 via-emerald-600 to-teal-500 text-white text-xs sm:text-sm font-bold shadow-sm shadow-emerald-700/25 shrink-0">
                    {{ strtoupper(substr($userName, 0, 2)) }}
                </span>

                {{-- Info Pengurus & Nama Cabang (Desktop) --}}
                <div class="hidden sm:flex flex-col text-left min-w-0 max-w-[150px]">
                    <span class="text-xs font-bold text-slate-900 truncate leading-tight">
                        {{ $userName }}
                    </span>
                    <span class="text-[10px] text-emerald-700 font-semibold truncate leading-tight mt-0.5">
                        {{ $namaCabang }}
                    </span>
                </div>

                {{-- Ikon Chevron --}}
                <svg id="user-chevron" class="w-4 h-4 text-slate-500 transition-transform duration-200 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Panel Menu Dropdown Profil --}}
            <div id="user-menu" 
                 class="hidden absolute right-0 mt-2.5 w-72 rounded-2xl border border-emerald-100 bg-white shadow-xl shadow-slate-900/10 z-50 p-2 transform origin-top-right transition-all"
                 role="menu"
                 aria-orientation="vertical"
                 aria-labelledby="user-btn">
                
                {{-- Header Dropdown: Info Singkat Cabang --}}
                <div class="p-3 bg-gradient-to-br from-emerald-50/70 to-teal-50/40 rounded-xl border border-emerald-100/70 mb-2">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <span class="text-xs font-bold text-slate-900 truncate">{{ $userName }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-white text-emerald-800 border border-emerald-200 shrink-0">
                            {{ $kodeCabang }}
                        </span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] text-emerald-800 font-medium truncate">
                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="truncate">{{ $namaCabang }}</span>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-0.5 truncate">{{ $daerahCabang }}</p>
                </div>

                {{-- Navigasi Item Menu --}}
                <div class="space-y-1">
                    {{-- 1. Profil Cabang --}}
                    <a href="{{ route('cabang.profil.index') }}" 
                       role="menuitem"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('cabang.profil.*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('cabang.profil.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>{{ __('Profil Cabang') }}</span>
                    </a>

                    {{-- 2. Pengaturan --}}
                    <a href="{{ route('cabang.pengaturan.index') }}" 
                       role="menuitem"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('cabang.pengaturan.*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('cabang.pengaturan.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ __('Pengaturan') }}</span>
                    </a>
                </div>

                {{-- 3. Shortcut Ganti Bahasa (i18n) --}}
                <div class="my-2 pt-2 border-t border-slate-100 px-3 py-1 flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-500 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        {{ __('Bahasa') }}
                    </span>

                    {{-- Switch Pill ID / EN --}}
                    <div class="inline-flex rounded-lg bg-slate-100 p-0.5 text-[10px] font-bold">
                        <form action="{{ route('cabang.pengaturan.ganti-bahasa') }}" method="POST" class="inline m-0">
                            @csrf
                            <input type="hidden" name="locale" value="id">
                            <button type="submit" 
                                    class="px-2 py-0.5 rounded-md transition-all cursor-pointer {{ $currentLocale === 'id' ? 'bg-white text-emerald-800 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-800' }}">
                                ID
                            </button>
                        </form>
                        <form action="{{ route('cabang.pengaturan.ganti-bahasa') }}" method="POST" class="inline m-0">
                            @csrf
                            <input type="hidden" name="locale" value="en">
                            <button type="submit" 
                                    class="px-2 py-0.5 rounded-md transition-all cursor-pointer {{ $currentLocale === 'en' ? 'bg-white text-emerald-800 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-800' }}">
                                EN
                            </button>
                        </form>
                    </div>
                </div>

                {{-- 4. Tombol Logout --}}
                <div class="pt-1 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" 
                                role="menuitem"
                                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors cursor-pointer text-left focus:outline-none focus:bg-rose-50">
                            <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>{{ __('Keluar') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endauth
    </div>
</header>
