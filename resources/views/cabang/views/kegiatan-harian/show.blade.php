{{-- BAGIAN: PENGURUS CABANG - Detail Kegiatan Harian --}}
@extends('cabang.layout.app')

@section('title', 'Detail Kegiatan Harian')
@section('header_title', 'Detail Kegiatan Harian')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('cabang.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <a href="{{ route('cabang.kegiatan-harian.index') }}" class="hover:text-emerald-700 transition-colors">Kegiatan Harian</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Detail</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $kegiatan->nama_kegiatan }}</h2>
        </div>
        <a href="{{ route('cabang.kegiatan-harian.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-6 border-b border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Cabang Penyelenggara</span>
                <p class="font-bold text-slate-900 mt-0.5 text-sm">{{ $kegiatan->asrama?->nama_asrama }} ({{ $kegiatan->asrama?->wilayah }})</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Kategori</span>
                <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $kegiatan->kategori_label }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Tanggal & Waktu</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $kegiatan->tanggal_kegiatan?->translatedFormat('l, d F Y') }}</p>
                <p class="text-slate-500">{{ $kegiatan->jam_mulai_format }} - {{ $kegiatan->jam_selesai_format }} WIB</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Jumlah Peserta</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $kegiatan->jumlah_peserta }} anak asuh mengikuti</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Penginput Data</span>
                <p class="font-medium text-slate-700 mt-0.5">{{ $kegiatan->penginput?->name ?? 'Pengurus' }}</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Waktu Pencatatan</span>
                <p class="text-slate-500 mt-0.5">{{ $kegiatan->created_at?->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div>
            <span class="text-slate-400 block uppercase font-bold text-[10px] mb-1">Deskripsi Kegiatan</span>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs sm:text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                {{ $kegiatan->deskripsi ?: 'Tidak ada deskripsi tambahan.' }}
            </div>
        </div>

        @if($kegiatan->catatan_tambahan)
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px] mb-1">Catatan Tambahan</span>
                <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200/60 text-xs sm:text-sm text-amber-900 leading-relaxed whitespace-pre-line">
                    {{ $kegiatan->catatan_tambahan }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

