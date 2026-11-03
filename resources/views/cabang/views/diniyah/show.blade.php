{{-- BAGIAN: PENGURUS CABANG - Detail Pengetahuan Diniyah --}}
@extends('cabang.layout.app')

@section('title', 'Detail Nilai Pengetahuan Diniyah')
@section('header_title', 'Detail Nilai Diniyah')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('cabang.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <a href="{{ route('cabang.diniyah.index') }}" class="hover:text-emerald-700 transition-colors">Pengetahuan Diniyah</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Detail</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $evaluasi->materi }}</h2>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('cabang.diniyah.edit', $evaluasi->id) }}" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold">
                Edit
            </a>
            <a href="{{ route('cabang.diniyah.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold">
                &larr; Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-6 border-b border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Santri</span>
                <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $evaluasi->anakAsuh?->nama_lengkap }}</p>
                <p class="text-slate-500">NIS: {{ $evaluasi->anakAsuh?->nomor_induk }} &bull; Jenjang: {{ $evaluasi->anakAsuh?->jenjang_pendidikan }}</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Kategori Diniyah</span>
                <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                    {{ $evaluasi->jenis_evaluasi }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Nilai & Predikat</span>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-2xl font-extrabold text-emerald-700">{{ $evaluasi->nilai }}</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                        {{ $evaluasi->predikat }}
                    </span>
                </div>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Tanggal Ujian</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $evaluasi->tanggal_evaluasi?->translatedFormat('l, d F Y') }}</p>
                <p class="text-slate-500">Cabang: {{ $evaluasi->asrama?->nama_asrama }}</p>
            </div>
        </div>

        <div>
            <span class="text-slate-400 block uppercase font-bold text-[10px] mb-1">Catatan Penguji / Ustadz</span>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs sm:text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                {{ $evaluasi->catatan_pengaju ?: 'Tidak ada catatan.' }}
            </div>
        </div>

        @if($evaluasi->catatan_evaluasi_pusat)
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-emerald-800 block uppercase font-bold text-[10px]">Catatan Evaluasi Pengurus Pusat</span>
                </div>
                <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200 text-xs sm:text-sm text-emerald-950 leading-relaxed whitespace-pre-line">
                    {{ $evaluasi->catatan_evaluasi_pusat }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

