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

    {{-- Banner Hero Cabang --}}
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-10 text-white shadow-xl"
         style="background: radial-gradient(ellipse 40% 70% at 80% 50%, rgba(217,119,6,.25) 0%, transparent 65%), linear-gradient(135deg, #064e3b 0%, #056a4c 55%, #044a3f 100%);">
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
                    Portal operasional pengurus cabang <strong>{{ $cabang?->nama_asrama }}</strong>. Input dan kelola kegiatan harian, evaluasi hafalan Al-Qur'an, dan nilai pengetahuan diniyah santri cabang Anda.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2.5">
                <a href="{{ route('cabang.kegiatan-harian.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs sm:text-sm shadow-md shadow-orange-500/20 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Input Kegiatan</span>
                </a>
                <a href="{{ route('cabang.hafalan-quran.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/25 text-white font-semibold text-xs sm:text-sm transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Input Hafalan</span>
                </a>
            </div>
        </div>
    </div>

    {{-- 4 Kartu Ringkasan Statistik Cabang --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        {{-- Total Santri Aktif --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Santri Aktif</span>
                    <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_santri']) }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-slate-100 text-[11px] text-slate-500">
                Santri terdaftar di cabang ini
            </div>
        </div>

        {{-- Kegiatan Bulan Ini --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Kegiatan Bulan Ini</span>
                    <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_kegiatan_bulan_ini']) }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-slate-100 text-[11px] text-slate-500">
                Aktivitas harian tercatat
            </div>
        </div>

        {{-- Evaluasi & Ulangan --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Evaluasi Diniyah</span>
                    <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_evaluasi_bulan_ini']) }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-slate-100 text-[11px] text-slate-500">
                Hafalan & ulangan santri
            </div>
        </div>

        {{-- Rata-rata Nilai --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Rata-rata Nilai</span>
                    <span class="text-2xl font-bold text-slate-900">{{ $stats['rata_rata_nilai'] }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-slate-100 text-[11px] text-slate-500">
                Skor kumulatif santri cabang
            </div>
        </div>
    </div>

    {{-- Dua Tabel: Kegiatan Terbaru & Evaluasi Terbaru Cabang --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Kegiatan Harian Terbaru Cabang --}}
        <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Kegiatan Harian Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Catatan kegiatan cabang {{ $cabang?->nama_asrama }}</p>
                </div>
                <a href="{{ route('cabang.kegiatan-harian.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">
                    Lihat Semua &rarr;
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($stats['kegiatan_terbaru'] as $keg)
                    <div class="p-4 hover:bg-slate-50/60 transition-colors flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ $keg->nama_kegiatan }}</p>
                            <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-500">
                                <span>{{ $keg->tanggal_kegiatan?->format('d M Y') }}</span>
                                <span>&bull;</span>
                                <span class="capitalize">{{ $keg->kategori_label }}</span>
                                <span>&bull;</span>
                                <span>{{ $keg->jumlah_peserta }} peserta</span>
                            </div>
                        </div>
                        <a href="{{ route('cabang.kegiatan-harian.show', $keg->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-slate-500">
                        Belum ada kegiatan harian yang tercatat di cabang ini.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Evaluasi Diniyah Terbaru Cabang --}}
        <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Evaluasi & Hafalan Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Penilaian santri di cabang ini</p>
                </div>
                <a href="{{ route('cabang.hafalan-quran.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">
                    Lihat Semua &rarr;
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($stats['evaluasi_terbaru'] as $ev)
                    <div class="p-4 hover:bg-slate-50/60 transition-colors flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $ev->anakAsuh?->nama_lengkap ?? 'Santri' }}</p>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $ev->jenis_evaluasi }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-500">
                                <span>Materi: {{ $ev->materi }}</span>
                                <span>&bull;</span>
                                <span>{{ $ev->tanggal_evaluasi?->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-base font-extrabold {{ $ev->nilai >= 70 ? 'text-emerald-700' : 'text-amber-600' }}">
                                {{ $ev->nilai }}
                            </span>
                            <p class="text-[10px] text-slate-400">{{ $ev->predikat }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-slate-500">
                        Belum ada evaluasi atau hafalan santri yang dicatat.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

