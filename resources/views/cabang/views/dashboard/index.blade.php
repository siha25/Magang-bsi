{{-- BAGIAN: PENGURUS CABANG - Dashboard Cabang --}}
@extends('cabang.layout.app')

@section('title', 'Dashboard Cabang ' . ($cabang?->nama_asrama ?? ''))
@section('header_title', 'Dashboard Cabang')

@section('content')
@php
    $user = auth()->user();
    $userName = $user?->name ?? 'Pengurus Cabang';
@endphp

<div class="space-y-6">

    {{-- Banner Sambutan Pengurus Cabang --}}
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 text-white shadow-xl"
         style="background: radial-gradient(ellipse 40% 70% at 80% 50%, rgba(217,119,6,.22) 0%, transparent 65%), linear-gradient(135deg, #064e3b 0%, #056a4c 55%, #044a3f 100%);">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/20 text-xs font-semibold text-emerald-100 mb-3">
                    <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                    <span>Wilayah {{ $cabang?->wilayah ?? '-' }} &bull; {{ $cabang?->kode_asrama }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-2">
                    Assalamu'alaikum, {{ $userName }}
                </h1>
                <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed max-w-2xl">
                    Dashboard operasional cabang <strong>{{ $cabang?->nama_asrama }}</strong>. Pantau kondisi santri aktif, catatan kesehatan dan kasus bulanan, tren hasil ujian diniyah, serta evaluasi hafalan Al-Qur'an santri.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2.5">
                <a href="{{ route('cabang.hafalan-quran.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs sm:text-sm shadow-md shadow-orange-500/20 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Input Hafalan</span>
                </a>
                <a href="{{ route('cabang.diniyah.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/25 text-white font-semibold text-xs sm:text-sm transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Input Ujian Diniyah</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ========== 3 KARTU RINGKASAN INDIKATOR UTAMA CABANG ========== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">

        {{-- 1. Jumlah Anak Asuh yang Aktif --}}
        <a href="{{ route('cabang.santri.index') }}" class="group block bg-white rounded-2xl p-5 border border-emerald-100/90 shadow-xs hover:border-emerald-300 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1 group-hover:text-emerald-700 transition-colors">
                        Santri / Anak Asuh Aktif
                    </span>
                    <span class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ number_format($stats['total_santri']) }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Santri terdaftar aktif di cabang ini</span>
                <span class="text-emerald-600 font-semibold group-hover:translate-x-0.5 transition-transform">Kelola &rarr;</span>
            </div>
        </a>

        {{-- 2. Total Evaluasi Hafalan Tercatat --}}
        <a href="{{ route('cabang.hafalan-quran.index') }}" class="group block bg-white rounded-2xl p-5 border border-emerald-100/90 shadow-xs hover:border-emerald-300 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1 group-hover:text-emerald-700 transition-colors">
                        Total Setoran Hafalan
                    </span>
                    <span class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ number_format($stats['evaluasi_hafalan']['total']) }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 group-hover:bg-teal-600 group-hover:text-white flex items-center justify-center transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Rata-rata skor: {{ $stats['evaluasi_hafalan']['rata_rata'] }} poin</span>
                <span class="text-teal-600 font-semibold group-hover:translate-x-0.5 transition-transform">Lihat &rarr;</span>
            </div>
        </a>

        {{-- 3. Santri Predikat Mumtaz (A) --}}
        <div class="bg-white rounded-2xl p-5 border border-amber-100/90 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                        Predikat Mumtaz (A)
                    </span>
                    <span class="text-3xl font-extrabold text-amber-600 tracking-tight">
                        {{ number_format($stats['evaluasi_hafalan']['mumtaz']) }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Nilai evaluasi hafalan &ge; 85</span>
                <span class="text-[11px] font-semibold text-amber-600">Istimewa</span>
            </div>
        </div>

        @if(false) {{-- Kartu Kesehatan & Kasus disembunyikan pada Tahap 1 sesuai instruksi --}}
        {{-- 2. Jumlah Sakit di Bulan Terkini --}}
        <div class="bg-white rounded-2xl p-5 border border-rose-100/90 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            Jumlah Sakit
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            Bulan Terkini
                        </span>
                    </div>
                    <span class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ number_format($stats['total_sakit_bulan_ini']) }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Laporan santri sakit periode {{ $stats['bulan_terkini_nama'] }}</span>
                <span class="text-[11px] font-medium text-slate-400">Kondisi Sehat</span>
            </div>
        </div>

        {{-- 3. Jumlah Kasus Anak di Bulan Terkini --}}
        <div class="bg-white rounded-2xl p-5 border border-amber-100/90 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            Jumlah Kasus Anak
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            Bulan Terkini
                        </span>
                    </div>
                    <span class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ number_format($stats['total_kasus_bulan_ini']) }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Catatan pelanggaran/kasus {{ $stats['bulan_terkini_nama'] }}</span>
                <span class="text-[11px] font-medium text-slate-400">Kondisi Tertib</span>
            </div>
        </div>
        @endif

    </div>

    {{-- ========== 2 KONTEN UTAMA: GRAFIK UJIAN DINIYAH & EVALUASI HAFALAN ========== --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- 4. Grafik Hasil Ujian Diniyah Setiap Bulannya --}}
        <div class="lg:col-span-7 bg-white rounded-3xl border border-emerald-100/90 shadow-xs p-5 sm:p-6 flex flex-col justify-between">
            <div>
                {{-- Header Card Grafik --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                                Grafik Hasil Ujian Diniyah Setiap Bulannya
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Tren rata-rata nilai hasil evaluasi &amp; ujian pengetahuan diniyah (6 bulan terakhir)
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('cabang.diniyah.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 self-start sm:self-auto">
                        Kelola Nilai &rarr;
                    </a>
                </div>

                {{-- Area Tampilan Grafik / Empty State --}}
                <div class="mt-6">
                    @if($stats['grafik_diniyah']['ada_data'])
                        <div class="relative w-full" style="height: 280px;">
                            <canvas id="chartUjianDiniyah"></canvas>
                        </div>
                    @else
                        <div class="py-12 px-4 text-center flex flex-col items-center justify-center rounded-2xl bg-slate-50/70 border border-dashed border-slate-200">
                            <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-3.5 shadow-2xs">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 mb-1">Belum Ada Riwayat Ujian Diniyah</h3>
                            <p class="text-xs text-slate-500 max-w-sm mb-4 leading-relaxed">
                                Grafik perkembangan skor ujian santri akan otomatis tampil setelah data nilai pengetahuan diniyah diinputkan pada cabang ini.
                            </p>
                            <a href="{{ route('cabang.diniyah.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                <span>Input Nilai Ujian Diniyah</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Footer Keterangan Grafik --}}
            <div class="mt-6 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-400">
                <span>Skala penilaian 0 - 100 poin &bull; Standar KKM: 70</span>
                <span>Sumber: Penilaian Staff Cabang</span>
            </div>
        </div>

        {{-- 5. Evaluasi Hafalan (Al-Qur'an & Setoran) --}}
        <div class="lg:col-span-5 bg-white rounded-3xl border border-emerald-100/90 shadow-xs p-5 sm:p-6 flex flex-col justify-between">
            <div>
                {{-- Header Evaluasi Hafalan --}}
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                                Evaluasi Hafalan
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Setoran hafalan Al-Qur'an santri cabang
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('cabang.hafalan-quran.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">
                        Lihat Semua &rarr;
                    </a>
                </div>

                {{-- Mini Ringkasan Indikator Hafalan --}}
                <div class="grid grid-cols-3 gap-2.5 my-4">
                    <div class="p-2.5 rounded-xl bg-slate-50/90 border border-slate-100 text-center">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total</span>
                        <span class="text-base font-extrabold text-slate-800">{{ $stats['evaluasi_hafalan']['total'] }}</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-emerald-50/80 border border-emerald-100 text-center">
                        <span class="block text-[10px] font-bold text-emerald-700 uppercase tracking-wider">Rata-rata</span>
                        <span class="text-base font-extrabold text-emerald-800">{{ $stats['evaluasi_hafalan']['rata_rata'] }}</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-amber-50/80 border border-amber-100 text-center">
                        <span class="block text-[10px] font-bold text-amber-700 uppercase tracking-wider">Mumtaz (A)</span>
                        <span class="text-base font-extrabold text-amber-800">{{ $stats['evaluasi_hafalan']['mumtaz'] }}</span>
                    </div>
                </div>

                {{-- Daftar Riwayat Evaluasi Hafalan Terbaru --}}
                <div class="divide-y divide-slate-100">
                    @forelse($stats['evaluasi_hafalan']['list'] as $hafalan)
                        <div class="py-3 flex items-center justify-between gap-3 hover:bg-slate-50/60 rounded-xl px-2 -mx-2 transition-colors">
                            <div class="min-w-0 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100/70 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($hafalan->anakAsuh?->nama_lengkap ?? 'S', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-900 truncate">
                                        {{ $hafalan->anakAsuh?->nama_lengkap ?? 'Santri' }}
                                    </p>
                                    <div class="flex items-center gap-1.5 text-[11px] text-slate-500 mt-0.5">
                                        <span class="truncate font-medium text-emerald-700">{{ $hafalan->materi }}</span>
                                        <span>&bull;</span>
                                        <span class="shrink-0">{{ $hafalan->tanggal_evaluasi?->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-sm font-extrabold {{ $hafalan->nilai >= 70 ? 'text-emerald-700' : 'text-amber-600' }}">
                                    {{ $hafalan->nilai }}
                                </span>
                                <p class="text-[10px] font-semibold text-slate-400">
                                    {{ $hafalan->predikat }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center flex flex-col items-center justify-center">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2.5">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-slate-700 mb-0.5">Belum Ada Catatan Hafalan</p>
                            <p class="text-[11px] text-slate-400 max-w-xs mb-3">Belum ada evaluasi hafalan santri yang dicatat di cabang ini.</p>
                            <a href="{{ route('cabang.hafalan-quran.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                <span>Input Hafalan</span>
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Footer Aksi Tambah Hafalan --}}
            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="{{ route('cabang.hafalan-quran.create') }}" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold text-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Catat Evaluasi Hafalan Baru</span>
                </a>
            </div>
        </div>

    </div>

</div>

@push('scripts')
{{-- Library Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('chartUjianDiniyah');
    if (!ctx) return;

    const labels = @json($stats['grafik_diniyah']['labels']);
    const dataValues = @json($stats['grafik_diniyah']['data']);
    const pesertaValues = @json($stats['grafik_diniyah']['peserta']);

    Chart.defaults.font.family = "'Plus Jakarta Sans', system-ui, sans-serif";
    Chart.defaults.color = '#64748b';

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rata-rata Nilai Ujian',
                data: dataValues,
                borderColor: '#0d9488',
                backgroundColor: 'rgba(13, 148, 136, 0.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#0d9488',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.35,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const val = context.parsed.y;
                            const idx = context.dataIndex;
                            const peserta = pesertaValues[idx] || 0;
                            return ` Skor: ${val} poin (${peserta} santri diuji)`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 100,
                    ticks: {
                        stepSize: 20,
                        callback: function (val) {
                            return val + ' pts';
                        },
                        font: { size: 11 }
                    },
                    grid: {
                        color: '#f1f5f9'
                    },
                    border: {
                        dash: [4, 4]
                    }
                },
                x: {
                    ticks: {
                        font: { size: 11 }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
