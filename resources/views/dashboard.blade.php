@extends('layouts.app')

@section('title', 'Dashboard Ikhtisar Pendidikan')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- 1. BANNER SAMBUTAN & FILTER PERIODE --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-800 text-white p-6 sm:p-8 shadow-xl shadow-emerald-950/10">
        {{-- Hiasan Latar Geometris Islami / Cahaya Lembut --}}
        <div class="absolute -right-16 -top-16 w-80 h-80 rounded-full bg-emerald-500/20 blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/4 -bottom-20 w-72 h-72 rounded-full bg-orange-500/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="max-w-2xl space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold text-emerald-100">
                    <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                    <span>Periode Evaluasi: {{ $periode['bulan'] }} {{ $periode['tahun'] }} (Semester {{ $periode['semester'] }})</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Assalamu’alaikum, {{ $pengurus['nama'] }}
                </h2>
                <p class="text-sm sm:text-base text-emerald-100/90 leading-relaxed">
                    Selamat datang di Portal Monitoring Pendidikan Anak Asuh Yayasan Rumah Harapan. Pantau perkembangan hafalan, kedisiplinan ibadah, serta evaluasi diniyah seluruh asrama secara real-time.
                </p>
            </div>

            {{-- Tombol Tindakan Cepat (Quick Actions) --}}
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <button type="button" 
                        onclick="alert('Fitur Form Input Evaluasi Diniyah Baru siap diintegrasikan pada modul berikutnya.')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 active:scale-95 text-white text-xs sm:text-sm font-semibold shadow-lg shadow-orange-500/30 transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Input Evaluasi Baru</span>
                </button>

                <button type="button" 
                        onclick="window.print()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 active:scale-95 text-white text-xs sm:text-sm font-medium border border-white/20 backdrop-blur-md transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Cetak / Ekspor Rekap</span>
                </button>
            </div>
        </div>

        {{-- Ringkasan Mini Bar Bawah Banner --}}
        <div class="mt-6 pt-5 border-t border-emerald-600/40 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-emerald-200/80 block">Tahun Ajaran:</span>
                <span class="font-bold text-white text-sm">{{ $periode['tahun_ajaran'] }}</span>
            </div>
            <div>
                <span class="text-emerald-200/80 block">Cabang Aktif Terdata:</span>
                <span class="font-bold text-white text-sm">{{ $stats['total_cabang'] }} Asrama Cabang</span>
            </div>
            <div>
                <span class="text-emerald-200/80 block">Total Anak Asuh Terbina:</span>
                <span class="font-bold text-white text-sm">{{ $stats['total_anak'] }} Santri / Anak</span>
            </div>
            <div>
                <span class="text-emerald-200/80 block">Status Sistem:</span>
                <span class="font-bold text-emerald-300 flex items-center gap-1.5 text-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Sinkronisasi Normal
                </span>
            </div>
        </div>
    </div>


    {{-- 2. KARTU STATISTIK UTAMA (6 KARTU STATISTIK DENGAN IKON & WARNA HIJAU/PUTIH/OREN) --}}
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
                    {{-- Ikon Grup Anak Asuh --}}
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-emerald-700 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 9.586 14.586 6H12z" clip-rule="evenodd" />
                    </svg>
                    +12 anak (Tahun ini)
                </span>
                <span class="text-slate-400">Tersebar di 6 Asrama</span>
            </div>
        </div>

        {{-- KARTU 2: TOTAL CABANG ASRAMA --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">
                        Total Cabang
                    </span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['total_cabang'] }}</span>
                        <span class="text-xs font-semibold text-slate-500">Cabang Aktif</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    {{-- Ikon Gedung / Asrama --}}
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-teal-700 font-medium">Jawa Barat, DIY, Jawa Timur</span>
                <span class="px-2 py-0.5 rounded-full bg-teal-50 text-teal-700 font-semibold text-[11px]">100% Beroperasi</span>
            </div>
        </div>

        {{-- KARTU 3: JUMLAH ANAK SD, SMP, SMA --}}
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
                    {{-- Ikon Topi Akademik / Buku --}}
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
            </div>

            {{-- Visual Bar Distribusi Jenjang --}}
            <div class="mt-3">
                <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden flex">
                    <div style="width: 33%" class="h-full bg-emerald-500" title="SD: 82 Anak (33%)"></div>
                    <div style="width: 39%" class="h-full bg-teal-500" title="SMP: 96 Anak (39%)"></div>
                    <div style="width: 28%" class="h-full bg-orange-400" title="SMA: 70 Anak (28%)"></div>
                </div>
            </div>

            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> SD (33%)</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-teal-500"></span> SMP (39%)</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-orange-400"></span> SMA (28%)</span>
            </div>
        </div>

        {{-- KARTU 4: EVALUASI DINIYAH BULAN BERJALAN --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">
                        Evaluasi Diniyah Bulan Berjalan
                    </span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-emerald-700 tracking-tight">
                            {{ $stats['evaluasi_bulan_ini']['persentase'] }}%
                        </span>
                        <span class="text-xs font-medium text-slate-500">Selesai</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    {{-- Ikon Checklist Evaluasi --}}
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            {{-- Progress Bar Evaluasi --}}
            <div class="mt-3">
                <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 transition-all duration-500" style="width: {{ $stats['evaluasi_bulan_ini']['persentase'] }}%"></div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-600 font-medium">{{ $stats['evaluasi_bulan_ini']['keterangan'] }}</span>
                <span class="text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded-md">Bulan {{ $periode['bulan'] }}</span>
            </div>
        </div>

        {{-- KARTU 5: TARGET HAFALAN TERCAPAI --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">
                        Target Hafalan Tercapai
                    </span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-orange-600 tracking-tight">
                            {{ $stats['target_hafalan']['persentase'] }}%
                        </span>
                        <span class="text-xs font-medium text-slate-500">Tuntas</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    {{-- Ikon Kitab Hafalan Al-Qur'an --}}
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>

            {{-- Progress Bar Target Hafalan --}}
            <div class="mt-3">
                <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-orange-400 to-amber-500 transition-all duration-500" style="width: {{ $stats['target_hafalan']['persentase'] }}%"></div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-600 font-medium">{{ $stats['target_hafalan']['keterangan'] }}</span>
                <span class="text-orange-700 font-semibold bg-orange-50 px-2 py-0.5 rounded-md">Al-Qur'an & Hadis</span>
            </div>
        </div>

        {{-- KARTU 6: EVALUASI BELUM LENGKAP --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-orange-200/80 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group bg-gradient-to-b from-orange-50/20 to-white">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-orange-700/80 block mb-1">
                        Evaluasi Belum Lengkap
                    </span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-orange-600 tracking-tight">
                            {{ $stats['evaluasi_belum_lengkap']['jumlah'] }}
                        </span>
                        <span class="text-xs font-semibold text-slate-500">Anak Menunggu</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    {{-- Ikon Peringatan / Jam Pasir --}}
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="mt-3">
                <p class="text-xs text-slate-600">
                    Sebagian besar berasal dari Cabang Surabaya (8 anak) dan Cabang Depok (3 anak).
                </p>
            </div>

            <div class="mt-4 pt-3 border-t border-orange-100 flex items-center justify-between text-xs">
                <span class="text-orange-700 font-bold flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-orange-500 animate-ping"></span>
                    {{ $stats['evaluasi_belum_lengkap']['status'] }}
                </span>
                <a href="#tindak-lanjut" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 underline decoration-emerald-300">
                    Kirim Pengingat →
                </a>
            </div>
        </div>

    </div>


    {{-- 3. BARIS UTAMA: TABEL RINGKASAN CABANG & TARGET CAPAIAN --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 items-start">
        
        {{-- KOLOM KIRI (2/3 LEBAR): TABEL RINGKASAN CABANG --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
                
                {{-- Header Tabel & Filter Interaktif --}}
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gradient-to-r from-emerald-50/40 via-white to-white">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900">
                                Tabel Ringkasan Cabang Yayasan Rumah Harapan
                            </h3>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Status pemantauan pendidikan, sebaran jenjang, dan capaian hafalan di 6 asrama cabang.
                        </p>
                    </div>

                    {{-- Search & Filter Input --}}
                    <div class="flex items-center gap-2.5">
                        <div class="relative">
                            <input type="text" 
                                   id="branchSearchInput"
                                   onkeyup="filterBranches()"
                                   placeholder="Filter nama cabang..." 
                                   class="text-xs pl-8 pr-3 py-1.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <select id="statusFilterSelect" 
                                onchange="filterBranches()"
                                class="text-xs px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                            <option value="all">Semua Status</option>
                            <option value="Lengkap">Lengkap</option>
                            <option value="Berjalan">Berjalan</option>
                            <option value="Belum Lengkap">Belum Lengkap</option>
                        </select>
                    </div>
                </div>

                {{-- Tabel Data Responsif --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="branchesTable">
                        <thead>
                            <tr class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                                <th class="py-3.5 px-4 sm:px-6">Nama Cabang & Penanggung Jawab</th>
                                <th class="py-3.5 px-4 text-center">Total Anak</th>
                                <th class="py-3.5 px-4">Sebaran Jenjang</th>
                                <th class="py-3.5 px-4">Capaian Hafalan</th>
                                <th class="py-3.5 px-4 text-center">Nilai Diniyah</th>
                                <th class="py-3.5 px-4 text-center">Status Evaluasi</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach($cabangList as $cabang)
                            <tr class="hover:bg-emerald-50/40 transition-colors branch-row" data-name="{{ strtolower($cabang['nama']) }}" data-status="{{ $cabang['evaluasi_status'] }}">
                                
                                {{-- Cabang & Kepala Asrama --}}
                                <td class="py-4 px-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ substr($cabang['nama'], 7, 2) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-tight text-xs sm:text-sm">
                                                {{ $cabang['nama'] }}
                                            </span>
                                            <span class="text-[11px] text-slate-500 block mt-0.5">
                                                {{ $cabang['kepala_asrama'] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Total Anak --}}
                                <td class="py-4 px-4 text-center font-bold text-slate-800 text-sm">
                                    {{ $cabang['total_anak'] }}
                                    <span class="text-[10px] text-slate-400 font-normal block">Anak</span>
                                </td>

                                {{-- Sebaran Jenjang (SD, SMP, SMA) --}}
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-1.5 text-[11px]">
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-semibold border border-emerald-100" title="Sekolah Dasar">
                                            SD: {{ $cabang['jenjang']['sd'] }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-md bg-teal-50 text-teal-700 font-semibold border border-teal-100" title="Sekolah Menengah Pertama">
                                            SMP: {{ $cabang['jenjang']['smp'] }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-md bg-orange-50 text-orange-700 font-semibold border border-orange-100" title="Sekolah Menengah Atas">
                                            SMA: {{ $cabang['jenjang']['sma'] }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Capaian Hafalan --}}
                                <td class="py-4 px-4">
                                    <div class="w-32 space-y-1">
                                        <div class="flex items-center justify-between text-[11px]">
                                            <span class="font-semibold text-slate-700">{{ $cabang['target_hafalan_persen'] }}%</span>
                                            <span class="text-slate-400">Target</span>
                                        </div>
                                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full rounded-full {{ $cabang['target_hafalan_persen'] >= 80 ? 'bg-emerald-500' : ($cabang['target_hafalan_persen'] >= 70 ? 'bg-teal-500' : 'bg-orange-400') }}"
                                                 style="width: {{ $cabang['target_hafalan_persen'] }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Rata Nilai Diniyah --}}
                                <td class="py-4 px-4 text-center font-bold text-slate-800">
                                    <span class="inline-block px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 font-mono text-xs">
                                        {{ number_format($cabang['rata_nilai_diniyah'], 1) }}
                                    </span>
                                </td>

                                {{-- Status Evaluasi --}}
                                <td class="py-4 px-4 text-center">
                                    @if($cabang['evaluasi_status'] === 'Lengkap')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Lengkap
                                        </span>
                                    @elseif($cabang['evaluasi_status'] === 'Berjalan')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                            <svg class="w-3 h-3 text-amber-600 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            Berjalan ({{ $cabang['evaluasi_progress'] }}%)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-orange-100 text-orange-800 border border-orange-200">
                                            <svg class="w-3 h-3 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            Belum Lengkap
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi / Detail --}}
                                <td class="py-4 px-4 sm:px-6 text-right">
                                    <button type="button" 
                                            onclick="alert('Membuka detail {{ $cabang['nama'] }} (Penanggung Jawab: {{ $cabang['kepala_asrama'] }})')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:bg-emerald-100/70 border border-emerald-200 transition-colors cursor-pointer">
                                        <span>Detail</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer Tabel --}}
                <div class="p-4 px-6 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                    <div>
                        Menampilkan <strong>6 Cabang Asrama</strong> Yayasan Rumah Harapan
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500"></span> 3 Cabang Lengkap
                        <span class="text-slate-300">•</span>
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-amber-500"></span> 2 Cabang Berjalan
                        <span class="text-slate-300">•</span>
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-orange-500"></span> 1 Cabang Belum Lengkap
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
                        <p class="text-xs text-slate-400 mt-0.5">Semester Ganjil 2026/2027</p>
                    </div>
                    <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-xs">
                        90%
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach($targetKategori as $target)
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800">{{ $target['judul'] }}</span>
                            <span class="font-extrabold text-emerald-700">{{ $target['tercapai_persen'] }}%</span>
                        </div>
                        <p class="text-[11px] text-slate-500">{{ $target['subjudul'] }}</p>
                        
                        {{-- Progress Bar --}}
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full {{ $target['warna'] === 'orange' ? 'bg-orange-500' : ($target['warna'] === 'amber' ? 'bg-amber-500' : ($target['warna'] === 'teal' ? 'bg-teal-500' : 'bg-emerald-600')) }}"
                                 style="width: {{ $target['tercapai_persen'] }}%"></div>
                        </div>
                        <span class="text-[10px] text-slate-400 block">{{ $target['detail'] }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-slate-100">
                    <a href="#kurikulum" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-100/80 text-emerald-800 text-xs font-semibold transition-colors">
                        <span>Lihat Rincian Silabus & Target</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Aktivitas & Setoran Diniyah Terkini --}}
            <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-5 sm:p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></span>
                        <h3 class="text-base font-bold text-slate-900">Aktivitas Terkini</h3>
                    </div>
                    <span class="text-[11px] text-slate-400 font-medium">Hari ini</span>
                </div>

                <div class="space-y-3.5">
                    @foreach($aktivitasTerbaru as $aktivitas)
                    <div class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="w-8 h-8 rounded-lg shrink-0 flex items-center justify-center mt-0.5
                            {{ $aktivitas['tipe'] === 'quran' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $aktivitas['tipe'] === 'evaluasi' ? 'bg-teal-100 text-teal-700' : '' }}
                            {{ $aktivitas['tipe'] === 'kesehatan' ? 'bg-rose-100 text-rose-700' : '' }}
                            {{ $aktivitas['tipe'] === 'hadis' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $aktivitas['tipe'] === 'pembinaan' ? 'bg-orange-100 text-orange-700' : '' }}
                        ">
                            @if($aktivitas['tipe'] === 'quran')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            @elseif($aktivitas['tipe'] === 'kesehatan')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-800 leading-snug">
                                {{ $aktivitas['judul'] }}
                            </p>
                            <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-400">
                                <span class="font-medium text-emerald-700">{{ $aktivitas['cabang'] }}</span>
                                <span>•</span>
                                <span>{{ $aktivitas['pembina'] }}</span>
                            </div>
                        </div>

                        <span class="text-[10px] text-slate-400 shrink-0 font-medium">
                            {{ $aktivitas['waktu'] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</div>

{{-- Script Filter Cabang --}}
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
