@extends('layouts.app')

@section('title', 'Detail Kegiatan Harian')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div>
        <div class="mb-1 flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('dashboard') }}" class="transition-colors hover:text-emerald-700">Dashboard</a>
            <span>/</span>
            <a href="{{ route('kegiatan-harian.index') }}" class="transition-colors hover:text-emerald-700">Kegiatan Harian</a>
            <span>/</span>
            <span class="font-semibold text-emerald-800">Detail</span>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Detail Kegiatan Harian</h2>
        <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">Informasi lengkap aktivitas harian anak asuh.</p>
    </div>

    <section class="overflow-hidden rounded-3xl border border-emerald-100/90 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-gradient-to-r from-emerald-50/50 via-white to-white p-5 sm:p-7">
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">{{ $kegiatan->kategori_label }}</p>
            <h3 class="mt-2 text-xl font-bold text-slate-900">{{ $kegiatan->nama_kegiatan }}</h3>
        </div>

        <dl class="grid grid-cols-1 gap-x-8 gap-y-6 p-5 sm:grid-cols-2 sm:p-7">
            <div>
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal kegiatan</dt>
                <dd class="mt-1.5 text-sm font-medium text-slate-800">{{ $kegiatan->tanggal_kegiatan->format('d M Y') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Asrama</dt>
                <dd class="mt-1.5 text-sm font-medium text-slate-800">{{ $kegiatan->asrama->nama_asrama ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Jam mulai dan selesai</dt>
                <dd class="mt-1.5 text-sm font-medium text-slate-800">{{ $kegiatan->jam_mulai_format }} - {{ $kegiatan->jam_selesai_format }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Jumlah peserta</dt>
                <dd class="mt-1.5 text-sm font-medium text-slate-800">{{ $kegiatan->jumlah_peserta }} anak</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Deskripsi kegiatan</dt>
                <dd class="mt-1.5 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $kegiatan->deskripsi ?: 'Tidak ada deskripsi.' }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Catatan tambahan</dt>
                <dd class="mt-1.5 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $kegiatan->catatan_tambahan ?: 'Tidak ada catatan tambahan.' }}</dd>
            </div>
        </dl>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/60 p-5 sm:flex-row sm:justify-end sm:p-6">
            <a href="{{ route('kegiatan-harian.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">Kembali ke Kegiatan Harian</a>
        </div>
    </section>
</div>
@endsection
