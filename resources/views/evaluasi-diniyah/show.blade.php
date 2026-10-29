@extends('layouts.app')

@section('title', 'Detail Evaluasi Diniyah - ' . ($evaluasiDiniyah->anakAsuh?->nama_lengkap ?? 'Santri'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Breadcrumb & Title --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <a href="{{ route('evaluasi-diniyah.index') }}" class="hover:text-emerald-700 transition-colors">Evaluasi Diniyah</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Detail Evaluasi</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Lembar Evaluasi Diniyah Santri</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Rincian capaian nilai ulangan dan catatan perkembangan kurikulum diniyah.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('evaluasi-diniyah.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali</span>
            </a>

            @if(auth()->user()?->isAdminPusat() || (auth()->user()?->isAdminCabang() && (int) auth()->user()->asrama_id === (int) $evaluasiDiniyah->asrama_id))
                <a href="{{ route('evaluasi-diniyah.edit', $evaluasiDiniyah) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/25 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit Nilai</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Kartu Profil Santri & Skor Utama --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        {{-- Banner Header --}}
        <div class="p-6 sm:p-8 bg-gradient-to-r from-emerald-600 via-emerald-700 to-teal-800 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-xs text-white font-bold text-xl flex items-center justify-center border border-white/30 shrink-0">
                        {{ strtoupper(substr($evaluasiDiniyah->anakAsuh?->nama_lengkap ?? 'A', 0, 2)) }}
                    </div>
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/25 text-white uppercase tracking-wider mb-1">
                            {{ $evaluasiDiniyah->jenis_evaluasi }}
                        </span>
                        <h3 class="text-xl sm:text-2xl font-bold tracking-tight">
                            {{ $evaluasiDiniyah->anakAsuh?->nama_lengkap }}
                        </h3>
                        <p class="text-xs sm:text-sm text-emerald-100 mt-0.5">
                            NIS: {{ $evaluasiDiniyah->anakAsuh?->nomor_induk ?? '-' }} • {{ $evaluasiDiniyah->anakAsuh?->jenjang_pendidikan }} {{ $evaluasiDiniyah->anakAsuh?->kelas }} • {{ $evaluasiDiniyah->asrama?->nama_asrama }}
                        </p>
                    </div>
                </div>

                {{-- Nilai Besar --}}
                <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/20 text-center sm:text-right shrink-0">
                    <span class="text-xs uppercase tracking-wider text-emerald-200 block font-semibold">Hasil Nilai</span>
                    <div class="text-3xl sm:text-4xl font-extrabold tracking-tight mt-0.5">
                        {{ (float) $evaluasiDiniyah->nilai }}
                        <span class="text-sm font-normal text-emerald-200">/ 100</span>
                    </div>
                    <span class="inline-block mt-1 text-xs font-bold px-2.5 py-0.5 rounded-full bg-white text-emerald-800 shadow-xs">
                        {{ $evaluasiDiniyah->predikat ?? 'Selesai' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Detail Informasi Ulangan --}}
        <div class="p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Materi / Ujian</span>
                    <span class="text-sm font-bold text-slate-800 block">{{ $evaluasiDiniyah->materi }}</span>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Tanggal Evaluasi</span>
                    <span class="text-sm font-bold text-slate-800 block">
                        {{ $evaluasiDiniyah->tanggal_evaluasi ? $evaluasiDiniyah->tanggal_evaluasi->format('d F Y') : '-' }}
                    </span>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Periode Semester</span>
                    <span class="text-sm font-bold text-slate-800 block">
                        Semester {{ $evaluasiDiniyah->semester ?? 'Ganjil' }} {{ $evaluasiDiniyah->tahun ?? date('Y') }}
                    </span>
                </div>
            </div>

            {{-- Catatan Pengajar --}}
            <div class="p-5 rounded-2xl bg-emerald-50/50 border border-emerald-100">
                <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-900 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    Catatan & Ulasan Pengajar
                </h4>
                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed">
                    {{ $evaluasiDiniyah->catatan_pengaju ?: 'Tidak ada catatan tambahan untuk evaluasi ini.' }}
                </p>
            </div>

            {{-- Metadata & Penginput --}}
            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-400 gap-2">
                <div>
                    Diinput oleh: <span class="font-semibold text-slate-600">{{ $evaluasiDiniyah->pengaju?->name ?? 'Admin Cabang' }}</span>
                    @if($evaluasiDiniyah->diajukan_pada)
                        pada {{ $evaluasiDiniyah->diajukan_pada->format('d M Y, H:i') }}
                    @endif
                </div>
                <div>
                    Status: <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Terverifikasi</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
