@extends('layouts.app')

@section('title', 'Dashboard Ikhtisar Pendidikan')

@section('content')
<div class="space-y-6 sm:space-y-8">

{{-- ========== STYLE BANNER ========== --}}
<style>
    .banner{position:relative;overflow:hidden;border-radius:32px;color:#fff;padding:48px 56px;
        background:
            radial-gradient(ellipse 28% 55% at 76% 48%, rgba(217,119,6,.38) 0%, transparent 70%),
            radial-gradient(ellipse 60% 70% at 12% 95%, #0a8a63 0%, transparent 70%),
            radial-gradient(ellipse 50% 70% at 100% 100%, #033b3c 0%, transparent 70%),
            linear-gradient(135deg,#064e3b 0%,#056a4c 50%,#044a3f 100%);
        box-shadow:0 25px 50px -12px rgba(6,78,59,.35)}
    .banner-inner{position:relative;z-index:1;display:flex;flex-direction:column;gap:28px;align-items:flex-start}
    .banner-left{max-width:760px}

    .pill{display:inline-flex;align-items:center;gap:10px;padding:8px 18px;border-radius:9999px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.22);font-size:14px;font-weight:600;color:#d1fae5;backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px)}
    .pill i{width:10px;height:10px;border-radius:9999px;background:#f97316;display:inline-block}

    .banner h2{font-size:clamp(28px,4vw,48px);font-weight:800;line-height:1.12;letter-spacing:-.02em;margin:20px 0 16px;color:#fff}
    .banner p.desc{font-size:clamp(14px,1.6vw,19px);line-height:1.65;color:rgba(167,243,208,.85);margin:0}

    .date-card{width:100%;padding:28px;border-radius:28px;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.25);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);box-shadow:0 20px 40px -12px rgba(0,0,0,.3)}
    .date-card .d{font-size:32px;font-weight:800;line-height:1.2;margin:0;color:#fff}
    .date-card .status{margin-top:22px;padding-top:20px;border-top:1px solid rgba(255,255,255,.22);display:flex;align-items:center;gap:10px;font-size:17px;color:#d1fae5}
    .date-card .status i{width:14px;height:14px;border-radius:9999px;background:#22e07a;box-shadow:0 0 12px #22e07a;display:inline-block}

    /* DESKTOP: kartu tanggal di KANAN TENGAH */
    @media (min-width:768px){
        .banner{padding:72px 64px}
        .banner-inner{flex-direction:row;align-items:center;justify-content:space-between;gap:40px}
        .banner-left{flex:1}
        .date-card{width:300px;flex-shrink:0}
    }
</style>

{{-- ========== BANNER SAMBUTAN ========== --}}
<div class="banner">
    <div class="banner-inner">

        {{-- Kiri: Sambutan --}}
        <div class="banner-left">
            <div class="pill">
                <i></i>
                <span>Periode Evaluasi: {{ $periode['bulan'] }} {{ $periode['tahun'] }} (Semester {{ $periode['semester'] }})</span>
            </div>

            <h2>Assalamu’alaikum, {{ $pengurus['nama'] ?? 'Admin Pusat Developer' }}</h2>

            <p class="desc">
                Selamat datang di Portal Monitoring Pendidikan Anak Asuh Yayasan Rumah Harapan.
                Sistem informasi pengelolaan data asrama, pembinaan santri, dan rekapitulasi pendidikan anak asuh.
            </p>
        </div>

        {{-- Kanan tengah: Kartu Tanggal --}}
        <div class="date-card">
            <p class="d">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd,') }}</p>
            <p class="d">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM') }}</p>
            <p class="d">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('YYYY') }}</p>

            <div class="status">
                <i></i>
                <span>Aktif & Terhubung</span>
            </div>
        </div>

    </div>
</div>
    
    {{-- 2. KARTU STATISTIK UTAMA (6 KARTU STATISTIK DENGAN DATA NYATA & STATUS VALID) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        
        {{-- KARTU 1: TOTAL ANAK ASUH --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">
                        Total Anak Asuh
                    </span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['total_anak'] }}</span>
                        <span class="text-xs font-semibold text-slate-500">Anak</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-emerald-700 font-medium flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    {{ $stats['anak_aktif'] }} anak aktif
                </span>
                <span class="text-slate-400">Tersebar di {{ $stats['total_asrama'] }} Asrama</span>
            </div>
        </div>

        {{-- KARTU 2: TOTAL ASRAMA --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">
                        Total Asrama
                    </span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['total_asrama'] }}</span>
                        <span class="text-xs font-semibold text-slate-500">Asrama</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-teal-700 font-medium">{{ $stats['asrama_aktif'] }} Asrama Aktif</span>
                <span class="px-2 py-0.5 rounded-full bg-teal-50 text-teal-700 font-semibold text-[11px]">
                    {{ $stats['total_asrama'] > 0 ? round(($stats['asrama_aktif'] / $stats['total_asrama']) * 100) . '% Aktif' : 'Belum ada data' }}
                </span>
            </div>
        </div>

        {{-- KARTU 3: DISTRIBUSI JENJANG PENDIDIKAN --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">
                        Distribusi Jenjang Pendidikan
                    </span>
                    <div class="flex items-baseline gap-3">
                        <span class="text-sm font-semibold text-slate-600">
                            SD: <strong class="text-emerald-700 text-base">{{ $stats['jenjang']['sd'] }}</strong>
                        </span>
                        <span class="text-slate-300">•</span>
                        <span class="text-sm font-semibold text-slate-600">
                            SMP: <strong class="text-teal-700 text-base">{{ $stats['jenjang']['smp'] }}</strong>
                        </span>
                        <span class="text-slate-300">•</span>
                        <span class="text-sm font-semibold text-slate-600">
                            SMA: <strong class="text-orange-600 text-base">{{ $stats['jenjang']['sma'] }}</strong>
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
            </div>

            {{-- Visual Bar Distribusi Jenjang --}}
            <div class="mt-3">
                <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden flex">
                    @if($stats['total_anak'] > 0)
                        <div style="width: {{ $stats['jenjang']['sd_persen'] }}%" class="h-full bg-emerald-500" title="SD: {{ $stats['jenjang']['sd'] }} Anak ({{ $stats['jenjang']['sd_persen'] }}%)"></div>
                        <div style="width: {{ $stats['jenjang']['smp_persen'] }}%" class="h-full bg-teal-500" title="SMP: {{ $stats['jenjang']['smp'] }} Anak ({{ $stats['jenjang']['smp_persen'] }}%)"></div>
                        <div style="width: {{ $stats['jenjang']['sma_persen'] }}%" class="h-full bg-orange-400" title="SMA: {{ $stats['jenjang']['sma'] }} Anak ({{ $stats['jenjang']['sma_persen'] }}%)"></div>
                    @else
                        <div class="w-full h-full bg-slate-200"></div>
                    @endif
                </div>
            </div>

            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> SD ({{ $stats['jenjang']['sd_persen'] }}%)</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-teal-500"></span> SMP ({{ $stats['jenjang']['smp_persen'] }}%)</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-orange-400"></span> SMA ({{ $stats['jenjang']['sma_persen'] }}%)</span>
            </div>
        </div>

        {{-- KARTU 4: EVALUASI DINIYAH --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">
                        Evaluasi Diniyah
                    </span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                            Belum tersedia
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Pencatatan belum dimulai</span>
                <span class="text-slate-400 text-[11px]">Bulan {{ $periode['bulan'] }}</span>
            </div>
        </div>

        {{-- KARTU 5: TARGET HAFALAN TERCAPAI --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">
                        Target Hafalan
                    </span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                            Belum tersedia
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Al-Qur'an & Hadis</span>
                <span class="text-slate-400 text-[11px]">Belum ada setoran</span>
            </div>
        </div>

        {{-- KARTU 6: KESEHATAN & PEMBINAAN --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">
                        Kesehatan & Pembinaan
                    </span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                            Belum tersedia
                        </span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Pemeriksaan & Konseling</span>
                <span class="text-slate-400 text-[11px]">Belum diinput</span>
            </div>
        </div>

    </div>


    {{-- 3. BARIS UTAMA: TABEL RINGKASAN ASRAMA & TARGET CAPAIAN --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 items-start">
        
        {{-- KOLOM KIRI (2/3 LEBAR): TABEL RINGKASAN ASRAMA --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
                
                {{-- Header Tabel & Filter Interaktif --}}
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gradient-to-r from-emerald-50/40 via-white to-white">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900">
                                Tabel Ringkasan Asrama Yayasan Rumah Harapan
                            </h3>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Status data asrama, penanggung jawab, dan distribusi anak asuh terdaftar.
                        </p>
                    </div>

                    {{-- Search & Filter Input --}}
                    <div class="flex items-center gap-2.5">
                        <div class="relative">
                            <input type="text" 
                                   id="branchSearchInput"
                                   onkeyup="filterBranches()"
                                   placeholder="Filter nama asrama..." 
                                   class="text-xs pl-8 pr-3 py-1.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <select id="statusFilterSelect" 
                                onchange="filterBranches()"
                                class="text-xs px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                            <option value="all">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>

                {{-- Tabel Data Responsif --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="branchesTable">
                        <thead>
                            <tr class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                                <th class="py-3.5 px-4 sm:px-6">Nama Asrama & Penanggung Jawab</th>
                                <th class="py-3.5 px-4 text-center">Total Anak</th>
                                <th class="py-3.5 px-4">Sebaran Jenjang</th>
                                <th class="py-3.5 px-4">Wilayah / Alamat</th>
                                <th class="py-3.5 px-4 text-center">Status Asrama</th>
                                <th class="py-3.5 px-4 text-center">Evaluasi Diniyah</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($asramaList as $asrama)
                            <tr class="hover:bg-emerald-50/40 transition-colors branch-row" data-name="{{ strtolower($asrama->nama_asrama) }}" data-status="{{ $asrama->status_aktif ? 'aktif' : 'nonaktif' }}">
                                
                                {{-- Nama Asrama & Penanggung Jawab --}}
                                <td class="py-4 px-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($asrama->nama_asrama, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-tight text-xs sm:text-sm">
                                                {{ $asrama->nama_asrama }}
                                            </span>
                                            <span class="text-[11px] text-slate-500 block mt-0.5">
                                                {{ $asrama->penanggung_jawab ?: 'Belum diatur' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Total Anak --}}
                                <td class="py-4 px-4 text-center font-bold text-slate-800 text-sm">
                                    {{ $asrama->anak_asuh_count }}
                                    <span class="text-[10px] text-slate-400 font-normal block">Anak</span>
                                </td>

                                {{-- Sebaran Jenjang (SD, SMP, SMA) --}}
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-1.5 text-[11px]">
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-semibold border border-emerald-100" title="Sekolah Dasar">
                                            SD: {{ $asrama->sd_count ?? 0 }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-md bg-teal-50 text-teal-700 font-semibold border border-teal-100" title="Sekolah Menengah Pertama">
                                            SMP: {{ $asrama->smp_count ?? 0 }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-md bg-orange-50 text-orange-700 font-semibold border border-orange-100" title="Sekolah Menengah Atas">
                                            SMA: {{ $asrama->sma_count ?? 0 }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Wilayah / Alamat --}}
                                <td class="py-4 px-4">
                                    <span class="font-medium text-slate-700 block">{{ $asrama->wilayah ?: '-' }}</span>
                                    <span class="text-[11px] text-slate-400 block truncate max-w-[180px]">{{ $asrama->alamat ?: '-' }}</span>
                                </td>

                                {{-- Status Asrama --}}
                                <td class="py-4 px-4 text-center">
                                    @if($asrama->status_aktif)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                {{-- Evaluasi Diniyah (Belum Ada Tabel DB) --}}
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-block px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 font-medium text-[11px]">
                                        Belum tersedia
                                    </span>
                                </td>

                                {{-- Aksi / Detail --}}
                                <td class="py-4 px-4 sm:px-6 text-right">
                                    <a href="{{ route('asrama.edit', $asrama) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:bg-emerald-100/70 border border-emerald-200 transition-colors cursor-pointer">
                                        <span>Kelola</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">
                                    Belum ada data asrama yang tersimpan dalam sistem.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer Tabel --}}
                <div class="p-4 px-6 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                    <div>
                        Menampilkan <strong>{{ $asramaList->count() }} Asrama</strong> Yayasan Rumah Harapan
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500"></span> {{ $stats['asrama_aktif'] }} Asrama Aktif
                        <span class="text-slate-300">•</span>
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-slate-400"></span> {{ $stats['total_asrama'] - $stats['asrama_aktif'] }} Asrama Nonaktif
                    </div>
                </div>
            </div>
        </div>


        {{-- KOLOM KANAN (1/3 LEBAR): TARGET CAPAIAN KURIKULUM & AKTIVITAS TERBARU --}}
        <div class="space-y-6">
            
            {{-- Target Capaian Kurikulum Diniyah --}}
            <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-5 sm:p-6 space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Target Kurikulum Diniyah</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Semester Ganjil {{ $periode['tahun_ajaran'] }}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-lg bg-slate-100 text-slate-600 text-[11px] font-medium border border-slate-200">
                        Belum tersedia
                    </span>
                </div>

                <div class="space-y-3.5">
                    @foreach($targetKategori as $target)
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800">{{ $target['judul'] }}</span>
                            <span class="text-[11px] font-medium text-slate-500">{{ $target['status'] }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500">{{ $target['subjudul'] }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-slate-100">
                    <p class="text-[11px] text-slate-400 text-center">
                        Pencatatan kurikulum akan aktif setelah modul pembelajaran terhubung.
                    </p>
                </div>
            </div>

            {{-- Aktivitas Terkini --}}
            <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-5 sm:p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                        <h3 class="text-base font-bold text-slate-900">Aktivitas Terkini</h3>
                    </div>
                    <span class="text-[11px] text-slate-400 font-medium">Bulan Ini</span>
                </div>

                @if(count($aktivitasTerbaru) > 0)
                    <div class="space-y-3.5">
                        @foreach($aktivitasTerbaru as $aktivitas)
                        <div class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-800 leading-snug">{{ $aktivitas['judul'] }}</p>
                                <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-400">
                                    <span class="font-medium text-emerald-700">{{ $aktivitas['asrama'] }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center space-y-2">
                        <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-600">Belum Ada Catatan Aktivitas</p>
                        <p class="text-[11px] text-slate-400 max-w-[200px] mx-auto">
                            Catatan evaluasi atau hafalan terbaru dari asrama akan ditampilkan di sini setelah modul aktif.
                        </p>
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>

{{-- Script Filter Asrama --}}
<script>
    function filterBranches() {
        const query = document.getElementById('branchSearchInput').value.toLowerCase();
        const status = document.getElementById('statusFilterSelect').value;
        const rows = document.querySelectorAll('.branch-row');

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const rowStatus = row.getAttribute('data-status');

            const matchesQuery = name.includes(query);
            const matchesStatus = (status === 'all') || (rowStatus === status);

            if (matchesQuery && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
