{{-- BAGIAN: PENGURUS CABANG - Sidebar Navigasi Cabang --}}
<aside id="sidebar" 
       class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-emerald-100 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full lg:static shadow-sm lg:shadow-none"
       aria-label="Sidebar Navigasi Cabang">
    
    {{-- Header Sidebar: Logo & Yayasan --}}
    <div class="h-20 px-6 flex items-center justify-between border-b border-emerald-50 bg-gradient-to-r from-emerald-50/60 to-white">
        <a href="{{ route('cabang.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-500 flex items-center justify-center text-white shadow-md shadow-emerald-600/25 group-hover:scale-105 transition-transform duration-200">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="flex flex-col">
                <div class="flex items-center gap-1.5">
                    <span class="font-bold text-lg text-slate-800 tracking-tight leading-none group-hover:text-emerald-700 transition-colors">Pendidikan</span>
                    <span class="inline-block w-2 h-2 rounded-full bg-orange-500"></span>
                </div>
                <span class="text-xs text-emerald-700 font-medium tracking-wide mt-1">Yayasan Rumah Harapan</span>
            </div>
        </a>

        {{-- Mobile Close Button --}}
        <button type="button" 
                onclick="toggleSidebar(false)"
                class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                aria-label="Tutup Menu">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Badge Identitas Cabang yang Sedang Login --}}
    <div class="px-6 pt-4 pb-2">
        <div class="px-3 py-2 rounded-xl bg-emerald-50/80 border border-emerald-100/80 flex flex-col gap-0.5 text-xs text-emerald-900 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="flex items-center gap-1.5 font-bold truncate">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                    {{ \App\Shared\Auth\CabangSession::getNamaCabang() }}
                </span>
                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-white text-emerald-700 border border-emerald-200">Staff Cabang</span>
            </div>
            <span class="text-[11px] text-slate-500 pl-3.5 truncate">
                Wilayah: {{ \App\Shared\Auth\CabangSession::getDaerah() }}
            </span>
        </div>
    </div>

    {{-- Daftar Navigasi Menu Pengurus Cabang --}}
    <div class="flex-1 overflow-y-auto px-4 py-3 space-y-5 scrollbar-thin scrollbar-thumb-emerald-200">
        {{-- Grup Utama Cabang --}}
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                {{ __('Operasional Cabang') }}
            </div>
            <nav class="space-y-1">
                {{-- Dashboard Cabang --}}
                <a href="{{ route('cabang.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 {{ request()->routeIs('cabang.dashboard') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('cabang.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>{{ __('Dashboard Cabang') }}</span>
                </a>

                {{-- Kegiatan Harian Cabang --}}
                <a href="{{ route('cabang.kegiatan-harian.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('cabang.kegiatan-harian.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('cabang.kegiatan-harian.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ __('Kegiatan Harian') }}</span>
                </a>

                {{-- Data Santri Cabang --}}
                <a href="{{ route('cabang.santri.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('cabang.santri.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('cabang.santri.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>{{ __('Santri / Anak Asuh') }}</span>
                </a>
            </nav>
        </div>

        {{-- Grup Pembinaan Diniyah Cabang --}}
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                {{ __('Pendidikan') }}
            </div>
            <nav class="space-y-1">
                {{-- Hafalan Al-Qur'an --}}
                <a href="{{ route('cabang.hafalan-quran.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('cabang.hafalan-quran.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('cabang.hafalan-quran.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>{{ __("Hafalan Al-Qur'an") }}</span>
                </a>

                {{-- Hafalan Hadist --}}
                <a href="{{ route('cabang.hafalan-hadist.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('cabang.hafalan-hadist.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('cabang.hafalan-hadist.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Hafalan Hadist</span>
                </a>

                {{-- Pengetahuan Diniyah --}}
                <a href="{{ route('cabang.diniyah.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('cabang.diniyah.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('cabang.diniyah.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <span>{{ __('Pengetahuan Diniyah') }}</span>
                </a>
            </nav>
        </div>

        {{-- Grup Pengaturan & Akun --}}
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                {{ __('Pengaturan & Akun') }}
            </div>
            <nav class="space-y-1">
                {{-- Profil Cabang --}}
                <a href="{{ route('cabang.profil.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('cabang.profil.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('cabang.profil.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>{{ __('Profil Cabang') }}</span>
                </a>

                {{-- Pengaturan --}}
                <a href="{{ route('cabang.pengaturan.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('cabang.pengaturan.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('cabang.pengaturan.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>{{ __('Pengaturan') }}</span>
                </a>
            </nav>
        </div>
    </div>

    {{-- Footer Sidebar: Info Cabang Singkat --}}
    <div class="p-4 border-t border-emerald-100 bg-emerald-50/40">
        <div class="p-3 rounded-xl bg-white border border-emerald-100/90 shadow-2xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                    CB
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-800 truncate">{{ \App\Shared\Auth\CabangSession::getNamaCabang() }}</p>
                    <p class="text-[11px] text-slate-400 truncate">Sistem Multi-Cabang v1.0</p>
                </div>
                <div class="w-2 h-2 rounded-full bg-emerald-500" title="Akun Terhubung"></div>
            </div>
        </div>
    </div>
</aside>

