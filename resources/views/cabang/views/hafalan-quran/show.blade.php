{{-- BAGIAN: PENGURUS CABANG - Detail Hafalan Qur'an --}}
@extends('cabang.layout.app')

@section('title', "Detail Setoran Hafalan Al-Qur'an")
@section('header_title', "Detail Setoran Hafalan")

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('cabang.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <a href="{{ route('cabang.hafalan-quran.index') }}" class="hover:text-emerald-700 transition-colors">Hafalan Al-Qur'an</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Detail</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $evaluasi->materi }}</h2>
        </div>
        <div class="flex items-center gap-2">
            @if($evaluasi->status === 'pending')
                <a href="{{ route('cabang.hafalan-quran.edit', $evaluasi->id) }}" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold">
                    Edit
                </a>
            @endif
            <a href="{{ route('cabang.hafalan-quran.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold">
                &larr; Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-6 border-b border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Santri / Anak Asuh</span>
                <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $evaluasi->anakAsuh?->nama_lengkap }}</p>
                <p class="text-slate-500">NIS: {{ $evaluasi->anakAsuh?->nomor_induk }} &bull; Jenjang: {{ $evaluasi->anakAsuh?->jenjang_pendidikan }}</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Cabang</span>
                <p class="font-bold text-slate-900 mt-0.5">{{ $evaluasi->asrama?->nama_asrama }} ({{ $evaluasi->asrama?->wilayah }})</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Tanggal Setoran</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $evaluasi->tanggal_evaluasi?->translatedFormat('l, d F Y') }}</p>
                <p class="text-slate-500">Penguji: {{ $evaluasi->pengaju?->name ?? 'Pengurus Cabang' }}</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Status</span>
                <div class="mt-1">
                    @if($evaluasi->status === 'pending')
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">⏳ Menunggu Review Admin Pendik</span>
                    @elseif($evaluasi->status === 'disetujui')
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">✓ Disetujui</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">{{ $evaluasi->status }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Blok Predikat: Awal dan Akhir Terpisah --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200">
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700 block mb-2">Predikat Awal (Staff Cabang)</span>
                <div class="flex items-center gap-2">
                    <span class="text-3xl font-extrabold text-blue-700">{{ $evaluasi->predikat ?? '-' }}</span>
                    <span class="text-xs text-blue-600">
                        @if($evaluasi->predikat === 'A') Istimewa
                        @elseif($evaluasi->predikat === 'B') Baik
                        @elseif($evaluasi->predikat === 'C') Cukup
                        @else -
                        @endif
                    </span>
                </div>
            </div>
            <div class="p-4 rounded-2xl {{ $evaluasi->predikat_akhir ? 'bg-emerald-50 border border-emerald-200' : 'bg-slate-50 border border-slate-200' }}">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ $evaluasi->predikat_akhir ? 'text-emerald-700' : 'text-slate-400' }} block mb-2">Predikat Akhir (Admin Pendik)</span>
                @if($evaluasi->predikat_akhir)
                    <div class="flex items-center gap-2">
                        <span class="text-3xl font-extrabold text-emerald-700">{{ $evaluasi->predikat_akhir }}</span>
                        <span class="text-xs text-emerald-600">
                            @if($evaluasi->predikat_akhir === 'A') Istimewa
                            @elseif($evaluasi->predikat_akhir === 'B') Baik
                            @elseif($evaluasi->predikat_akhir === 'C') Cukup
                            @endif
                        </span>
                    </div>
                    @if($evaluasi->penilai)
                        <p class="text-[10px] text-emerald-600 mt-1">Oleh: {{ $evaluasi->penilai->name }} &bull; {{ $evaluasi->dinilai_pada?->translatedFormat('d M Y') }}</p>
                    @endif
                @else
                    <p class="text-sm text-slate-400 italic mt-1">Belum direview</p>
                @endif
            </div>
        </div>

        {{-- Catatan Penguji --}}
        <div>
            <span class="text-slate-400 block uppercase font-bold text-[10px] mb-1">Catatan Penguji / Ustadz</span>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs sm:text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                {{ $evaluasi->catatan_pengaju ?: 'Tidak ada catatan khusus.' }}
            </div>
        </div>

        {{-- Catatan dari Admin Pendik (hanya tampil setelah review) --}}
        @if($evaluasi->catatan_evaluasi_pusat)
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-emerald-800 block uppercase font-bold text-[10px]">Catatan dari Admin Pendik</span>
                </div>
                <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200 text-xs sm:text-sm text-emerald-950 leading-relaxed whitespace-pre-line">
                    {{ $evaluasi->catatan_evaluasi_pusat }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
