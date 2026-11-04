{{-- Sidebar Navigasi Admin Pendidikan --}}
<aside id="sidebar" 
       class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-emerald-100 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full lg:static shadow-sm lg:shadow-none"
       aria-label="Sidebar Navigasi">
    
    {{-- Header Sidebar: Logo & Yayasan --}}
    <div class="h-20 px-6 flex items-center justify-between border-b border-emerald-50 bg-gradient-to-r from-emerald-50/60 to-white">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-500 flex items-center justify-center text-white shadow-md shadow-emerald-600/25 group-hover:scale-105 transition-transform duration-200">
                {{-- Logo Icon Motif Pendidikan & Harapan --}}
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

    {{-- Badge Divisi --}}
    <div class="px-6 pt-4 pb-2">
        <div class="px-3 py-1.5 rounded-xl bg-emerald-50/80 border border-emerald-100/80 flex items-center justify-between text-xs font-medium text-emerald-800">
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Divisi Pendidikan Anak Asuh
            </span>
            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-white text-emerald-700 border border-emerald-200">2026/2027</span>
        </div>
    </div>

    {{-- Daftar Navigasi Menu --}}
    <div class="flex-1 overflow-y-auto px-4 py-3 space-y-6 scrollbar-thin scrollbar-thumb-emerald-200">
        {{-- Grup Utama --}}
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                Menu Utama
            </div>
            <nav class="space-y-1">
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                {{-- Data Asrama --}}
                <a href="{{ route('asrama.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('asrama.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('asrama.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Data Asrama</span>
                </a>

                {{-- Data Anak Asuh --}}
                <a href="{{ route('anak-asuh.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('anak-asuh.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('anak-asuh.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Data Anak Asuh</span>
                </a>

                {{-- Kegiatan Harian --}}
                <a href="{{ route('kegiatan-harian.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('kegiatan-harian.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('kegiatan-harian.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Kegiatan Harian</span>
                </a>
            </nav>
        </div>

        {{-- Grup Pembinaan Diniyah --}}
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                Kurikulum & Diniyah
            </div>
            <nav class="space-y-1">
                {{-- Evaluasi Diniyah --}}
                <a href="{{ route('evaluasi-diniyah.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('evaluasi-diniyah.*') && !request()->has('jenis_evaluasi') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('evaluasi-diniyah.*') && !request()->has('jenis_evaluasi') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span>Evaluasi Diniyah</span>
                </a>

                {{-- Hafalan Al-Qur'an --}}
                <a href="{{ route('evaluasi-diniyah.index', ['jenis_evaluasi' => 'Hafalan Al-Qur\'an']) }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('evaluasi-diniyah.*') && request('jenis_evaluasi') === 'Hafalan Al-Qur\'an' ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('evaluasi-diniyah.*') && request('jenis_evaluasi') === 'Hafalan Al-Qur\'an' ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Hafalan Al-Qur'an</span>
                </a>

                {{-- Hafalan Hadis --}}
                <a href="{{ route('evaluasi-diniyah.index', ['jenis_evaluasi' => 'Hafalan Hadis']) }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('evaluasi-diniyah.*') && request('jenis_evaluasi') === 'Hafalan Hadis' ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('evaluasi-diniyah.*') && request('jenis_evaluasi') === 'Hafalan Hadis' ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Hafalan Hadis</span>
                </a>

                {{-- Pengetahuan Diniyah --}}
                <a href="{{ route('evaluasi-diniyah.index', ['jenis_evaluasi' => 'Pengetahuan Diniyah']) }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('evaluasi-diniyah.*') && request('jenis_evaluasi') === 'Pengetahuan Diniyah' ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('evaluasi-diniyah.*') && request('jenis_evaluasi') === 'Pengetahuan Diniyah' ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <span>Pengetahuan Diniyah</span>
                </a>
            </nav>
        </div>

        {{-- Grup Perkembangan & Pembinaan --}}
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                Kesejahteraan & Asrama
            </div>
            <nav class="space-y-1">
                {{-- Kesehatan Anak --}}
                <a href="#kesehatan-anak" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70 transition-all group">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span>Kesehatan Anak</span>
                </a>

                {{-- Kasus & Pembinaan --}}
                <a href="#kasus-pembinaan" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70 transition-all group">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Kasus & Pembinaan</span>
                </a>

                {{-- Laporan --}}
                <a href="#laporan" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70 transition-all group">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Laporan</span>
                </a>

                {{-- Pengaturan --}}
                <a href="#pengaturan" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70 transition-all group">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Pengaturan</span>
                </a>

                @if(auth()->user()?->isAdminPusat())
                {{-- Kelola Akun Pengguna --}}
                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all group {{ request()->routeIs('users.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm shadow-emerald-600/30' : 'font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('users.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }} transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Akun Pengguna</span>
                </a>
                @endif
            </nav>
        </div>
    </div>

    {{-- Footer Sidebar: Info Pengurus Singkat & Bantuan --}}
    <div class="p-4 border-t border-emerald-100 bg-emerald-50/40">
        <div class="p-3 rounded-xl bg-white border border-emerald-100/90 shadow-2xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-xs">
                    YH
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-800 truncate">Rumah Harapan Pusat</p>
                    <p class="text-[11px] text-slate-400 truncate">Sistem v1.0 • 2026</p>
                </div>
                <div class="w-2 h-2 rounded-full bg-emerald-500" title="Koneksi Aman"></div>
            </div>
        </div>
    </div>
</aside>
