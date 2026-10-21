@extends('layouts.app')

@section('title', 'Kegiatan Harian')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Kegiatan Harian</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Kegiatan Harian Anak Asuh</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Catatan aktivitas harian berdasarkan asrama dan kategori kegiatan.</p>
        </div>

        @if(auth()->user()?->isAdminCabang() || auth()->user()?->isAdminPusat())
            <a href="{{ route('kegiatan-harian.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs sm:text-sm font-semibold shadow-md shadow-orange-500/25 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Kegiatan</span>
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1 text-xs sm:text-sm font-medium">{{ session('success') }}</div>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/30 via-white to-white">
            <form method="GET" action="{{ route('kegiatan-harian.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-center">
                <div>
                    <label for="filter-tanggal" class="sr-only">Tanggal kegiatan</label>
                    <input id="filter-tanggal" type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full px-3 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                </div>
                <div>
                    <label for="filter-kategori" class="sr-only">Kategori kegiatan</label>
                    <select id="filter-kategori" name="kategori" class="w-full px-3 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        <option value="">Semua Kategori</option>
                        <option value="belajar" {{ request('kategori') === 'belajar' ? 'selected' : '' }}>Belajar</option>
                        <option value="muhadhoroh" {{ request('kategori') === 'muhadhoroh' ? 'selected' : '' }}>Muhadhoroh</option>
                        <option value="olahraga" {{ request('kategori') === 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                        <option value="acara_donatur" {{ request('kategori') === 'acara_donatur' ? 'selected' : '' }}>Acara Donatur</option>
                        <option value="bersih_bersih" {{ request('kategori') === 'bersih_bersih' ? 'selected' : '' }}>Bersih-bersih</option>
                    </select>
                </div>
                <div>
                    <label for="filter-asrama" class="sr-only">Asrama</label>
                    <select id="filter-asrama" name="asrama_id" class="w-full px-3 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        @if(auth()->user()?->isAdminCabang())
                            @forelse($asramaOptions as $asrama)
                                <option value="{{ $asrama->id }}" {{ request('asrama_id', $asrama->id) == $asrama->id ? 'selected' : '' }}>{{ $asrama->nama_asrama }}</option>
                            @empty
                                <option value="" selected>Asrama belum ditentukan</option>
                            @endforelse
                        @else
                            <option value="">Semua Asrama</option>
                            @foreach($asramaOptions as $asrama)
                                <option value="{{ $asrama->id }}" {{ request('asrama_id') == $asrama->id ? 'selected' : '' }}>{{ $asrama->nama_asrama }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <button type="submit" class="w-full px-3.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold lg:self-end">Terapkan</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Asrama</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Nama Kegiatan</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Peserta</th>
                        <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kegiatans as $kegiatan)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-3 text-slate-700">{{ $kegiatan->tanggal_kegiatan->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $kegiatan->asrama->nama_asrama ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $kegiatan->kategori_label }}</td>
                            <td class="px-4 py-3 text-slate-700 font-medium">{{ $kegiatan->nama_kegiatan }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $kegiatan->jam_mulai_format }} - {{ $kegiatan->jam_selesai_format }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $kegiatan->jumlah_peserta }} anak</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('kegiatan-harian.show', $kegiatan) }}" class="inline-flex items-center rounded-lg border border-emerald-200 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition-colors hover:bg-emerald-50">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                                    </svg>
                                </div>
                                <p class="mt-3 text-sm font-semibold text-slate-700">Kegiatan tidak ditemukan</p>
                                <p class="mt-1 text-xs text-slate-500">Coba ubah filter tanggal, kategori, atau asrama.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kegiatans->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $kegiatans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
