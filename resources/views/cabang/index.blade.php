@extends('layouts.app')

@section('title', 'Data Cabang')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- Header Modul & Tombol Tambah --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Data Cabang</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                Manajemen Data Cabang Asrama
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Kelola informasi seluruh cabang asrama binaan Yayasan Rumah Harapan.
            </p>
        </div>

        <div>
            <a href="{{ route('cabang.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 active:scale-95 text-white text-xs sm:text-sm font-semibold shadow-md shadow-orange-500/25 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Cabang Baru</span>
            </a>
        </div>
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

    {{-- Kartu Statistik Ringkas Cabang --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        {{-- Total Cabang --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Cabang</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $stats['total'] }}</span>
                <span class="text-xs text-slate-500">Asrama</span>
            </div>
            <span class="text-[11px] text-slate-400 block mt-2">Seluruh cabang terdaftar</span>
        </div>

        {{-- Cabang Aktif --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 block mb-1">Cabang Aktif</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-700">{{ $stats['aktif'] }}</span>
                <span class="text-xs text-emerald-600 font-medium">Beroperasi</span>
            </div>
            <span class="text-[11px] text-emerald-600/80 block mt-2">Dapat menerima evaluasi</span>
        </div>

        {{-- Cabang Nonaktif --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-orange-200/70 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-orange-700 block mb-1">Nonaktif / Ditutup</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-orange-600">{{ $stats['nonaktif'] }}</span>
                <span class="text-xs text-slate-500">Cabang</span>
            </div>
            <span class="text-[11px] text-orange-600/80 block mt-2">Tidak aktif saat ini</span>
        </div>

        {{-- Total Wilayah --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-teal-700 block mb-1">Sebaran Wilayah</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-teal-700">{{ $stats['total_wilayah'] }}</span>
                <span class="text-xs text-slate-500">Kota / Kab</span>
            </div>
            <span class="text-[11px] text-teal-600/80 block mt-2">Cakupan wilayah yayasan</span>
        </div>
    </div>

    {{-- Panel Pencarian, Filter & Tabel --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        
        {{-- Formulir Filter & Pencarian --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/30 via-white to-white">
            <form method="GET" action="{{ route('cabang.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                
                {{-- Input Pencarian --}}
                <div class="sm:col-span-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           name="cari" 
                           value="{{ $cari }}"
                           placeholder="Cari kode cabang, nama cabang, atau penanggung jawab..." 
                           class="w-full pl-10 pr-4 py-2 text-xs bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 border border-slate-200 focus:border-emerald-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                </div>

                {{-- Filter Status --}}
                <div class="sm:col-span-3">
                    <select name="status" 
                            class="w-full px-3 py-2 text-xs bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 border border-slate-200 focus:border-emerald-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Hanya Aktif</option>
                        <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Hanya Nonaktif</option>
                    </select>
                </div>

                {{-- Filter Wilayah --}}
                <div class="sm:col-span-3 flex items-center gap-2">
                    <select name="wilayah" 
                            class="w-full px-3 py-2 text-xs bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 border border-slate-200 focus:border-emerald-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        <option value="">Semua Wilayah</option>
                        @foreach($wilayahOptions as $opt)
                            <option value="{{ $opt }}" {{ $wilayah === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>

                    <button type="submit" 
                            class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shrink-0 transition-colors shadow-xs">
                        Terapkan
                    </button>

                    @if($cari !== '' || $status !== '' || $wilayah !== '')
                        <a href="{{ route('cabang.index') }}" 
                           class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                           title="Hapus Filter">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Daftar Cabang --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3.5 px-4 sm:px-6">Kode & Nama Cabang</th>
                        <th class="py-3.5 px-4">Wilayah</th>
                        <th class="py-3.5 px-4">Penanggung Jawab</th>
                        <th class="py-3.5 px-4">Kontak Telepon</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($cabangs as $cabang)
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        
                        {{-- Kode & Nama Cabang --}}
                        <td class="py-4 px-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl {{ $cabang->status_aktif ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ substr($cabang->kode_cabang, 0, 3) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-mono text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-1.5 py-0.5 rounded">
                                            {{ $cabang->kode_cabang }}
                                        </span>
                                    </div>
                                    <span class="font-bold text-slate-900 block mt-1 text-sm">
                                        {{ $cabang->nama_cabang }}
                                    </span>
                                    @if($cabang->alamat)
                                        <span class="text-[11px] text-slate-400 block line-clamp-1 mt-0.5" title="{{ $cabang->alamat }}">
                                            {{ $cabang->alamat }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Wilayah --}}
                        <td class="py-4 px-4">
                            @if($cabang->wilayah)
                                <span class="font-medium text-slate-700">{{ $cabang->wilayah }}</span>
                            @else
                                <span class="text-slate-400 italic">Belum diatur</span>
                            @endif
                        </td>

                        {{-- Penanggung Jawab --}}
                        <td class="py-4 px-4">
                            @if($cabang->penanggung_jawab)
                                <span class="font-semibold text-slate-800 block">{{ $cabang->penanggung_jawab }}</span>
                                <span class="text-[11px] text-slate-400">Kepala Asrama</span>
                            @else
                                <span class="text-slate-400 italic">Belum ditentukan</span>
                            @endif
                        </td>

                        {{-- Nomor Telepon --}}
                        <td class="py-4 px-4 font-mono text-slate-600">
                            @if($cabang->nomor_telepon)
                                <a href="tel:{{ $cabang->nomor_telepon }}" class="hover:text-emerald-700 transition-colors">
                                    {{ $cabang->nomor_telepon }}
                                </a>
                            @else
                                <span class="text-slate-400 italic font-sans">-</span>
                            @endif
                        </td>

                        {{-- Status Aktif / Nonaktif --}}
                        <td class="py-4 px-4 text-center">
                            @if($cabang->status_aktif)
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

                        {{-- Aksi (Edit & Toggle Status) --}}
                        <td class="py-4 px-4 sm:px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('cabang.edit', $cabang) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:bg-emerald-50 border border-emerald-200 transition-colors"
                                   title="Edit Data Cabang">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                {{-- Tombol Toggle Status (Aktif / Nonaktif) --}}
                                <form method="POST" action="{{ route('cabang.toggle-status', $cabang) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status cabang {{ $cabang->nama_cabang }} menjadi {{ $cabang->status_aktif ? 'NONAKTIF' : 'AKTIF' }}?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold {{ $cabang->status_aktif ? 'text-orange-700 hover:bg-orange-50 border border-orange-200' : 'text-emerald-700 hover:bg-emerald-50 border border-emerald-200' }} transition-colors"
                                            title="{{ $cabang->status_aktif ? 'Nonaktifkan Cabang' : 'Aktifkan Cabang' }}">
                                        @if($cabang->status_aktif)
                                            <svg class="w-3.5 h-3.5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                            <span>Nonaktifkan</span>
                                        @else
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Aktifkan</span>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 px-6 text-center">
                            <div class="max-w-md mx-auto space-y-3">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
                                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-800">Belum Ada Data Cabang</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    @if($cari !== '' || $status !== '' || $wilayah !== '')
                                        Tidak ditemukan cabang yang cocok dengan kriteria pencarian Anda. Silakan coba kata kunci lain atau reset filter.
                                    @else
                                        Database cabang masih kosong karena tidak menggunakan data dummy. Silakan tambah cabang baru melalui tombol di bawah.
                                    @endif
                                </p>
                                <div class="pt-2">
                                    @if($cari !== '' || $status !== '' || $wilayah !== '')
                                        <a href="{{ route('cabang.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                                            Reset Filter
                                        </a>
                                    @else
                                        <a href="{{ route('cabang.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold shadow-md shadow-orange-500/25 transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Tambah Cabang Pertama
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

        {{-- Footer Tabel & Navigasi Pagination (> 30 Cabang) --}}
        @if($cabangs->hasPages())
        <div class="p-4 px-6 border-t border-slate-100 bg-slate-50/50">
            {{ $cabangs->links() }}
        </div>
        @else
        <div class="p-4 px-6 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
            <span>Menampilkan <strong>{{ $cabangs->count() }}</strong> dari <strong>{{ $stats['total'] }}</strong> cabang</span>
            <span class="text-emerald-700 font-medium">Sistem Mendukung Multi-Cabang &gt; 30 Asrama</span>
        </div>
        @endif
    </div>

</div>
@endsection
