@extends('layouts.app')

@section('title', 'Evaluasi Diniyah')

@section('content')
<div class="space-y-6 sm:space-y-8">
    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Evaluasi Diniyah</span>
            </div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Evaluasi & Nilai Diniyah Santri</h2>
                @if(request('jenis_evaluasi'))
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        {{ request('jenis_evaluasi') }}
                    </span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Pencatatan dan pemantauan nilai ulangan diniyah, hafalan Al-Qur'an, hadis, dan kurikulum asrama.
            </p>
        </div>

        @if(auth()->user()?->isAdminCabang() || auth()->user()?->isAdminPusat())
            <a href="{{ route('evaluasi-diniyah.create', request('jenis_evaluasi') ? ['jenis_evaluasi' => request('jenis_evaluasi')] : []) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/25 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Input Nilai Ulangan</span>
            </a>
        @endif
    </div>

    {{-- Pesan Sukses / Flash Notification --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1 text-xs sm:text-sm font-medium">{{ session('success') }}</div>
        </div>
    @endif

    {{-- Kartu Ringkasan Metrik --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        {{-- Total Ulangan / Evaluasi --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Evaluasi</span>
                    <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_evaluasi']) }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 text-[11px] text-slate-500">
                Ulangan santri tercatat
            </div>
        </div>

        {{-- Rata-rata Nilai --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Rata-rata Nilai</span>
                    <span class="text-2xl font-bold text-slate-900">{{ $stats['rata_rata'] }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 text-[11px] text-slate-500">
                Skala nilai standar 0 - 100
            </div>
        </div>

        {{-- Predikat Mumtaz (A) --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Mumtaz (≥ 85)</span>
                    <span class="text-2xl font-bold text-emerald-700">{{ number_format($stats['mumtaz_count']) }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 text-[11px] text-slate-500">
                Nilai istimewa / mumtaz
            </div>
        </div>

        {{-- Santri Dievaluasi --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Santri Dinilai</span>
                    <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_santri']) }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 text-[11px] text-slate-500">
                Santri dengan catatan nilai
            </div>
        </div>
    </div>

    {{-- Filter Toolbar & Pencarian --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/25 via-white to-white">
            <form method="GET" action="{{ route('evaluasi-diniyah.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-center">
                {{-- Pencarian --}}
                <div class="lg:col-span-2">
                    <label for="filter-search" class="sr-only">Cari santri atau materi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input id="filter-search" type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari nama santri, no. induk, materi..." 
                               class="w-full pl-10 pr-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    </div>
                </div>

                {{-- Filter Jenis Evaluasi --}}
                <div>
                    <label for="filter-kategori" class="sr-only">Jenis Evaluasi</label>
                    <select id="filter-kategori" name="jenis_evaluasi" class="w-full px-3 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        <option value="">Semua Kategori Diniyah</option>
                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori }}" {{ request('jenis_evaluasi') === $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Asrama --}}
                <div>
                    <label for="filter-asrama" class="sr-only">Asrama Cabang</label>
                    <select id="filter-asrama" name="asrama_id" class="w-full px-3 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        @if(auth()->user()?->isAdminCabang())
                            @forelse($asramaOptions as $asrama)
                                <option value="{{ $asrama->id }}" {{ request('asrama_id', $asrama->id) == $asrama->id ? 'selected' : '' }}>
                                    {{ $asrama->nama_asrama }}
                                </option>
                            @empty
                                <option value="" selected>Asrama belum ditentukan</option>
                            @endforelse
                        @else
                            <option value="">Semua Asrama Cabang</option>
                            @foreach($asramaOptions as $asrama)
                                <option value="{{ $asrama->id }}" {{ request('asrama_id') == $asrama->id ? 'selected' : '' }}>
                                    {{ $asrama->nama_asrama }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Tombol Terapkan & Reset --}}
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 text-xs font-semibold rounded-xl bg-slate-900 hover:bg-slate-800 text-white transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'jenis_evaluasi', 'asrama_id', 'predikat', 'bulan', 'tahun']))
                        <a href="{{ route('evaluasi-diniyah.index') }}" class="px-3 py-2.5 text-xs font-semibold rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors" title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Data Evaluasi --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 sm:px-6">Santri / Anak Asuh</th>
                        <th class="py-3.5 px-4">Asrama Cabang</th>
                        <th class="py-3.5 px-4">Kategori & Materi Ulangan</th>
                        <th class="py-3.5 px-4">Tanggal Ulangan</th>
                        <th class="py-3.5 px-4 text-center">Nilai</th>
                        <th class="py-3.5 px-4">Predikat</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse($evaluasis as $evaluasi)
                        <tr class="hover:bg-slate-50/70 transition-colors group">
                            {{-- Santri --}}
                            <td class="py-3.5 px-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center shrink-0 text-xs">
                                        {{ strtoupper(substr($evaluasi->anakAsuh?->nama_lengkap ?? 'A', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-900 group-hover:text-emerald-700 transition-colors">
                                            {{ $evaluasi->anakAsuh?->nama_lengkap ?? '-' }}
                                        </div>
                                        <div class="text-[11px] text-slate-400">
                                            NIS: {{ $evaluasi->anakAsuh?->nomor_induk ?? '-' }} • {{ $evaluasi->anakAsuh?->jenjang_pendidikan ?? '' }} {{ $evaluasi->anakAsuh?->kelas ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Asrama --}}
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-slate-800">{{ $evaluasi->asrama?->nama_asrama ?? '-' }}</span>
                                <div class="text-[11px] text-slate-400">{{ $evaluasi->asrama?->wilayah ?? '' }}</div>
                            </td>

                            {{-- Kategori & Materi --}}
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60 mb-1">
                                    {{ $evaluasi->jenis_evaluasi }}
                                </span>
                                <div class="font-medium text-slate-800 line-clamp-1" title="{{ $evaluasi->materi }}">
                                    {{ $evaluasi->materi }}
                                </div>
                            </td>

                            {{-- Tanggal --}}
                            <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">
                                <div class="font-medium">{{ $evaluasi->tanggal_evaluasi ? $evaluasi->tanggal_evaluasi->format('d M Y') : '-' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $evaluasi->bulan }} {{ $evaluasi->tahun }}</div>
                            </td>

                            {{-- Nilai --}}
                            <td class="py-3.5 px-4 text-center">
                                @php
                                    $nilai = (float) $evaluasi->nilai;
                                    $badgeColor = 'bg-slate-100 text-slate-700 border-slate-200';
                                    if ($nilai >= 85) {
                                        $badgeColor = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                    } elseif ($nilai >= 75) {
                                        $badgeColor = 'bg-blue-100 text-blue-800 border-blue-200';
                                    } elseif ($nilai >= 65) {
                                        $badgeColor = 'bg-amber-100 text-amber-800 border-amber-200';
                                    } else {
                                        $badgeColor = 'bg-rose-100 text-rose-800 border-rose-200';
                                    }
                                @endphp
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-xl text-xs font-bold border {{ $badgeColor }}">
                                    {{ $nilai }}
                                </span>
                            </td>

                            {{-- Predikat --}}
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-800">{{ $evaluasi->predikat ?? '-' }}</span>
                                @if($evaluasi->catatan_pengaju)
                                    <div class="text-[11px] text-slate-400 line-clamp-1" title="{{ $evaluasi->catatan_pengaju }}">
                                        {{ $evaluasi->catatan_pengaju }}
                                    </div>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    {{-- Lihat Detail --}}
                                    <a href="{{ route('evaluasi-diniyah.show', $evaluasi) }}" 
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 transition-colors" 
                                       title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    @if(auth()->user()?->isAdminPusat() || (auth()->user()?->isAdminCabang() && (int) auth()->user()->asrama_id === (int) $evaluasi->asrama_id))
                                        {{-- Edit Nilai --}}
                                        <a href="{{ route('evaluasi-diniyah.edit', $evaluasi) }}" 
                                           class="p-1.5 rounded-lg text-slate-400 hover:text-blue-700 hover:bg-blue-50 transition-colors" 
                                           title="Edit Nilai">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        {{-- Hapus Nilai --}}
                                        <form method="POST" action="{{ route('evaluasi-diniyah.destroy', $evaluasi) }}" class="inline" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data evaluasi ulangan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-4 text-center">
                                <div class="max-w-sm mx-auto text-center space-y-3">
                                    <div class="w-16 h-16 mx-auto rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-800">Belum Ada Data Evaluasi Diniyah</h3>
                                    <p class="text-xs text-slate-500">
                                        Data nilai ulangan diniyah belum dimasukkan. Admin cabang dapat mulai memasukkan nilai evaluasi untuk anak-anak asuh di setiap cabang.
                                    </p>
                                    @if(auth()->user()?->isAdminCabang() || auth()->user()?->isAdminPusat())
                                        <div class="pt-2">
                                            <a href="{{ route('evaluasi-diniyah.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-md shadow-emerald-600/25 transition-all">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                </svg>
                                                <span>Input Nilai Sekarang</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Navigasi Paginasi --}}
        @if($evaluasis->hasPages())
            <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50/50">
                {{ $evaluasis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
