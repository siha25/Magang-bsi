@extends('layouts.app')

@section('title', 'Data Anak Asuh')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- Header Modul & Tombol Tambah --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Data Anak Asuh</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                Manajemen Data Anak Asuh
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Kelola informasi dan perkembangan data anak asuh Yayasan Rumah Harapan.
            </p>
        </div>

        @if(auth()->user()?->isAdminPusat())
        <div>
            <a href="{{ route('anak-asuh.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 active:scale-95 text-white text-xs sm:text-sm font-semibold shadow-md shadow-orange-500/25 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Anak Asuh</span>
            </a>
        </div>
        @endif
    </div>

    {{-- Pesan Notifikasi Berhasil (Flash Message) --}}
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-xs">
        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="flex-1 text-xs sm:text-sm font-medium">
            {{ session('success') }}
        </div>
    </div>
    @endif

    {{-- Kartu Statistik Ringkas Anak Asuh --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        {{-- Total Anak Asuh --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Anak Asuh</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $stats['total'] }}</span>
                <span class="text-xs text-slate-500">Anak Asuh</span>
            </div>
            <span class="text-[11px] text-slate-400 block mt-2">Seluruh anak asuh terdaftar</span>
        </div>

        {{-- Anak Aktif --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 block mb-1">Anak Aktif</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-700">{{ $stats['aktif'] }}</span>
                <span class="text-xs text-emerald-600 font-medium">Aktif</span>
            </div>
            <span class="text-[11px] text-emerald-600/80 block mt-2">Mengikuti pembinaan asrama</span>
        </div>

        {{-- Anak Nonaktif --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-orange-200/70 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-orange-700 block mb-1">Anak Nonaktif</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-orange-600">{{ $stats['nonaktif'] }}</span>
                <span class="text-xs text-slate-500">Anak Asuh</span>
            </div>
            <span class="text-[11px] text-orange-600/80 block mt-2">Alumni / Tidak aktif</span>
        </div>

        {{-- Distribusi Jenjang (SD, SMP, SMA) --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-teal-700 block mb-1">Distribusi Jenjang</span>
            <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
                    SD: <span class="font-extrabold">{{ $stats['total_sd'] }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-200">
                    SMP: <span class="font-extrabold">{{ $stats['total_smp'] }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                    SMA: <span class="font-extrabold">{{ $stats['total_sma'] }}</span>
                </span>
            </div>
            <span class="text-[11px] text-teal-600/80 block mt-2">Sebaran tingkatan sekolah</span>
        </div>
    </div>

    {{-- Panel Pencarian, Filter & Tabel --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        
        {{-- Formulir Filter & Pencarian Responsif --}}
        <div class="p-4 sm:p-5 border-b border-slate-100 bg-gradient-to-r from-emerald-50/30 via-white to-white">
            <form method="GET" action="{{ route('anak-asuh.index') }}" 
                  class="flex flex-col lg:flex-row lg:items-center gap-3">
                
                {{-- 1. Input Pencarian (Fleksibel melebar) --}}
                <div class="flex-1 min-w-[220px]">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" 
                               name="cari" 
                               value="{{ $cari }}" 
                               placeholder="Cari nomor induk atau nama..." 
                               class="w-full h-11 pl-10 pr-4 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 border border-slate-200 focus:border-emerald-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                    </div>
                </div>

                {{-- Kumpulan Dropdown Filter & Aksi (Responsif: 2 kolom di mobile/tablet, 1 baris di desktop) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:flex xl:items-center gap-2.5 sm:gap-3">
                    {{-- 2. Filter Asrama --}}
                    <div class="min-w-[170px]">
                        <select name="asrama_id" 
                                class="w-full h-11 px-3.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 border border-slate-200 focus:border-emerald-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all cursor-pointer truncate">
                            <option value="" {{ empty($asramaId) ? 'selected' : '' }}>Semua Asrama</option>
                            @foreach($asramas as $asramaOption)
                                <option value="{{ $asramaOption->id }}" {{ (string)$asramaId === (string)$asramaOption->id ? 'selected' : '' }}>
                                    {{ $asramaOption->nama_asrama }} ({{ $asramaOption->kode_asrama }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Filter Jenjang --}}
                    <div class="min-w-[140px]">
                        <select name="jenjang" 
                                class="w-full h-11 px-3.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 border border-slate-200 focus:border-emerald-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all cursor-pointer">
                            <option value="" {{ empty($jenjang) ? 'selected' : '' }}>Semua Jenjang</option>
                            <option value="SD" {{ $jenjang === 'SD' ? 'selected' : '' }}>SD / MI</option>
                            <option value="SMP" {{ $jenjang === 'SMP' ? 'selected' : '' }}>SMP / MTs</option>
                            <option value="SMA" {{ $jenjang === 'SMA' ? 'selected' : '' }}>SMA / MA</option>
                        </select>
                    </div>

                    {{-- 4. Filter Status --}}
                    <div class="min-w-[135px]">
                        <select name="status" 
                                class="w-full h-11 px-3.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 border border-slate-200 focus:border-emerald-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all cursor-pointer">
                            <option value="" {{ empty($status) ? 'selected' : '' }}>Semua Status</option>
                            <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Hanya Aktif</option>
                            <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Hanya Nonaktif</option>
                        </select>
                    </div>

                    {{-- 5. Tombol Aksi: Terapkan & Reset (Bersebelahan rapi) --}}
                    <div class="sm:col-span-2 md:col-span-3 xl:col-auto flex items-center gap-2">
                        <button type="submit" 
                                class="flex-1 xl:flex-none h-11 inline-flex items-center justify-center gap-2 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white text-xs sm:text-sm font-semibold shadow-xs transition-all whitespace-nowrap cursor-pointer"
                                title="Terapkan Pencarian & Filter">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Terapkan</span>
                        </button>

                        @if($cari !== '' || !empty($asramaId) || $jenjang !== '' || $status !== '')
                        <a href="{{ route('anak-asuh.index') }}" 
                           class="h-11 inline-flex items-center justify-center gap-1.5 px-3.5 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-700 border border-slate-200 hover:border-rose-200 text-xs sm:text-sm font-semibold transition-colors whitespace-nowrap"
                           title="Reset Seluruh Filter">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Reset</span>
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabel Daftar Anak Asuh --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm border-collapse min-w-[760px] lg:min-w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-4 sm:px-6 whitespace-nowrap">Nomor Induk</th>
                        <th class="py-3.5 px-4 min-w-[180px]">Nama Lengkap</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap">L/P</th>
                        <th class="py-3.5 px-4 min-w-[150px]">Asrama</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap">Jenjang</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap">Kelas</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap">Status</th>
                        @if(auth()->user()?->isAdminPusat())
                            <th class="py-3.5 px-4 sm:px-6 text-right whitespace-nowrap">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($anakAsuhs as $anak)
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        {{-- Nomor Induk --}}
                        <td class="py-4 px-4 sm:px-6 whitespace-nowrap">
                            <span class="font-mono text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md inline-block">
                                {{ $anak->nomor_induk }}
                            </span>
                        </td>

                        {{-- Nama Lengkap & Info Tambahan --}}
                        <td class="py-4 px-4">
                            <div class="font-semibold text-slate-900">{{ $anak->nama_lengkap }}</div>
                            @if($anak->tempat_lahir || $anak->tanggal_lahir)
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $anak->tempat_lahir ?? '-' }}, {{ $anak->tanggal_lahir ? $anak->tanggal_lahir->format('d/m/Y') : '-' }}
                                </div>
                            @endif
                        </td>

                        {{-- Jenis Kelamin (L/P) --}}
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            @if($anak->jenis_kelamin === 'L')
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-50 text-blue-700 border border-blue-200 font-bold text-xs" title="Laki-laki">
                                    L
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-rose-50 text-rose-700 border border-rose-200 font-bold text-xs" title="Perempuan">
                                    P
                                </span>
                            @endif
                        </td>

                        {{-- Asrama --}}
                        <td class="py-4 px-4">
                            @if($anak->asrama)
                                <div class="font-medium text-slate-800">{{ $anak->asrama->nama_asrama }}</div>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $anak->asrama->kode_asrama }}</span>
                            @else
                                <span class="text-slate-400 italic">Tanpa Asrama</span>
                            @endif
                        </td>

                        {{-- Jenjang Pendidikan --}}
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            @if($anak->jenjang_pendidikan === 'SD')
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    SD
                                </span>
                            @elseif($anak->jenjang_pendidikan === 'SMP')
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                    SMP
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    SMA
                                </span>
                            @endif
                        </td>

                        {{-- Kelas --}}
                        <td class="py-4 px-4 text-center whitespace-nowrap text-slate-600">
                            {{ $anak->kelas ?? '-' }}
                        </td>

                        {{-- Status Aktif / Nonaktif --}}
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            @if($anak->status_aktif)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        @if(auth()->user()?->isAdminPusat())
                        {{-- Aksi (Edit & Toggle Status: sejajar dan rapi) --}}
                        <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-2">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('anak-asuh.edit', $anak) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 hover:text-emerald-800 bg-white hover:bg-emerald-50 border border-emerald-200 shadow-2xs transition-colors"
                                   title="Edit Data Anak Asuh">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                {{-- Tombol Toggle Status (Aktif / Nonaktif via PATCH) --}}
                                <form method="POST" action="{{ route('anak-asuh.toggle-status', $anak) }}" class="inline-flex m-0" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status anak asuh {{ $anak->nama_lengkap }} menjadi {{ $anak->status_aktif ? 'NONAKTIF' : 'AKTIF' }}?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold cursor-pointer shadow-2xs {{ $anak->status_aktif ? 'text-orange-700 bg-white hover:bg-orange-50 border border-orange-200' : 'text-emerald-700 bg-white hover:bg-emerald-50 border border-emerald-200' }} transition-colors"
                                            title="{{ $anak->status_aktif ? 'Nonaktifkan Anak Asuh' : 'Aktifkan Anak Asuh' }}">
                                        @if($anak->status_aktif)
                                            <svg class="w-3.5 h-3.5 text-orange-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                            <span>Nonaktifkan</span>
                                        @else
                                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Aktifkan</span>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()?->isAdminPusat() ? 8 : 7 }}" class="py-12 px-6 text-center">
                            <div class="max-w-md mx-auto space-y-3">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
                                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-800">Belum Ada Data Anak Asuh</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    @if($cari !== '' || !empty($asramaId) || $jenjang !== '' || $status !== '')
                                        Tidak ditemukan data anak asuh yang cocok dengan filter atau kata kunci pencarian Anda. Silakan coba kriteria lain atau reset filter.
                                    @else
                                        @if(auth()->user()?->isAdminPusat())
                                            Database anak asuh masih kosong karena tidak menggunakan data dummy. Silakan daftarkan anak asuh baru melalui tombol di bawah.
                                        @else
                                            Belum ada data anak asuh untuk asrama ini.
                                        @endif
                                    @endif
                                </p>
                                <div class="pt-2">
                                    @if($cari !== '' || !empty($asramaId) || $jenjang !== '' || $status !== '')
                                        <a href="{{ route('anak-asuh.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                                            Reset Filter
                                        </a>
                                    @elseif(auth()->user()?->isAdminPusat())
                                        <a href="{{ route('anak-asuh.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold shadow-md shadow-orange-500/25 transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Tambah Anak Asuh Pertama
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Tabel & Navigasi Pagination --}}
        @if($anakAsuhs->hasPages())
        <div class="p-4 px-6 border-t border-slate-100 bg-slate-50/50">
            {{ $anakAsuhs->links() }}
        </div>
        @else
        <div class="p-4 px-6 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
            <span>Menampilkan <strong>{{ $anakAsuhs->count() }}</strong> dari <strong>{{ $stats['total'] }}</strong> anak asuh</span>
            <span class="text-emerald-700 font-medium">Sistem Terintegrasi Data Anak Asuh SIHA</span>
        </div>
        @endif
    </div>

</div>
@endsection
