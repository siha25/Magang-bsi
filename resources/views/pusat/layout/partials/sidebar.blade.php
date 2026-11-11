{{-- BAGIAN: PENGURUS PUSAT - Sidebar Navigasi Pusat --}}
<aside id="sidebar" 
       class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-emerald-100 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full lg:static shadow-sm lg:shadow-none"
       aria-label="Sidebar Navigasi Pusat">
    
    {{-- Header Sidebar: Logo & Yayasan --}}
    <div class="h-20 px-6 flex items-center justify-between border-b border-emerald-50 bg-gradient-to-r from-emerald-50/60 to-white">
        <a href="{{ route('pusat.dashboard') }}" class="flex items-center gap-3 group">
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

        <button type="button" onclick="toggleSidebar(false)" class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors" aria-label="Tutup Menu">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    {{-- Badge Divisi Pusat --}}
    <div class="px-6 pt-4 pb-2">
        <div class="px-3 py-1.5 rounded-xl bg-emerald-50/80 border border-emerald-100/80 flex items-center justify-between text-xs font-medium text-emerald-800">
            <span class="flex items-center gap-1.5 font-bold">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Pengurus Pusat
            </span>
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-white text-emerald-700 border border-emerald-200">Seluruh Cabang</span>
        </div>
    </div>

    {{-- Menu Navigasi Pusat --}}
    <div class="flex-1 overflow-y-auto px-4 py-3 space-y-5 scrollbar-thin scrollbar-thumb-emerald-200">
        {{-- Grup Monitoring & Evaluasi --}}

        {{-- Grup Hafalan (mirip cabang) --}}
        <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            Hafalan
        </div>
        <nav class="space-y-1">
            {{-- Hafalan Al-Qur'an --}}
            <a href="{{ route('cabang.hafalan-quran.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('cabang.hafalan-quran.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('cabang.hafalan-quran.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>{{ __('Hafalan Al-Qur\'an') }}</span>
            </a>

            {{-- Hafalan Hadist --}}
            <a href="{{ route('pusat.hafalan-hadist.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('pusat.hafalan-hadist.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('pusat.hafalan-hadist.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Hafalan Hadist</span>
            </a>
        </nav>

        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                Monitoring & Evaluasi
            </div>
            <nav class="space-y-1">
                {{-- Dashboard Pusat --}}
                <a href="{{ route('pusat.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('pusat.dashboard') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('pusat.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard Analitik</span>
                </a>

                {{-- Monitoring Kegiatan --}}
                <a href="{{ route('pusat.monitoring-kegiatan.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('pusat.monitoring-kegiatan.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('pusat.monitoring-kegiatan.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Monitoring Kegiatan</span>
                </a>

                {{-- Monitoring Pengetahuan Diniyah --}}
                <a href="{{ route('pusat.monitoring-diniyah.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('pusat.monitoring-diniyah.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('pusat.monitoring-diniyah.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span>Monitoring Diniyah</span>
                </a>

                {{-- Evaluasi Diniyah Ringan --}}
                <a href="{{ route('pusat.evaluasi-diniyah.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('pusat.evaluasi-diniyah.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('pusat.evaluasi-diniyah.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span>Evaluasi Diniyah (Ringan)</span>
                </a>
            </nav>
        </div>

        {{-- Grup Manajemen Multi-Cabang --}}
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                Manajemen Cabang
            </div>
            <nav class="space-y-1">
                @if(auth()->user()?->isAdminPusat())
                {{-- Kelola Akun Cabang --}}
                <a href="{{ route('pusat.kelola-cabang.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('pusat.kelola-cabang.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('pusat.kelola-cabang.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Kelola Akun Cabang</span>
                </a>

                {{-- Akun Pengguna Sistem --}}
                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('users.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('users.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Akun Pengguna</span>
                </a>
                @endif

                {{-- Master Santri --}}
                <a href="{{ route('anak-asuh.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('anak-asuh.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('anak-asuh.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Master Santri Nasional</span>
                </a>
            </nav>
        </div>
    </div>

    {{-- Footer Sidebar Pusat --}}
    <div class="p-4 border-t border-emerald-100 bg-emerald-50/40">
        <div class="p-3 rounded-xl bg-white border border-emerald-100/90 shadow-2xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-xs">
                    YH
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-800 truncate">Pusat Yayasan</p>
                    <p class="text-[11px] text-slate-400 truncate">Monitoring Multi-Cabang</p>
                </div>
                <div class="w-2 h-2 rounded-full bg-emerald-500" title="Koneksi Aman"></div>
            </div>
        </div>
    </div>
</aside>

