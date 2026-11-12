{{-- BAGIAN: PENGURUS CABANG - Daftar Pengetahuan Diniyah Cabang --}}
@extends('cabang.layout.app')

@section('title', 'Pengetahuan Diniyah - ' . ($cabang?->nama_asrama ?? ''))
@section('header_title', 'Pengetahuan Diniyah')

@section('content')
<div class="space-y-6 sm:space-y-8">
    {{-- Header --}}
    <div class="flex items-center gap-2 text-xs text-slate-500 mb-3">
        <a href="{{ route('cabang.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
        <span>/</span>
        <span class="text-emerald-800 font-semibold">Pengetahuan Diniyah</span>
    </div>

    <div class="flex flex-col gap-6">
        {{-- Judul & Tombol Input --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengetahuan Diniyah</h1>
                <p class="text-sm text-slate-500 mt-2">Kelola penilaian diniyah santri cabang <strong>{{ $cabang?->nama_asrama }}</strong></p>
            </div>
            <a href="{{ route('cabang.diniyah.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-md shadow-emerald-600/25 transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Input Nilai</span>
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1 text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif
    </div>

    {{-- Grid: Kartu Pembelajaran + Ringkasan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kartu Belajar & Ulangan Online (Full Width di Mobile, 2 cols di Desktop) --}}
        <div class="lg:col-span-2 bg-gradient-to-br from-blue-50 to-blue-50/50 rounded-2xl border border-blue-200/50 shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6 sm:p-7">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-600/25 flex-shrink-0">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-slate-900 tracking-tight">Belajar & Ulangan Online</h3>
                        <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                            Akses materi Diniyah, latihan soal, dan ulangan online interaktif melalui <strong>RH Smart Learning</strong>.
                        </p>
                    </div>
                </div>
                <a href="https://rh-smart-learning.lovable.app" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-md shadow-blue-600/20 transition-all hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Buka RH Smart Learning</span>
                </a>
            </div>
        </div>

        {{-- Kartu Info Cepat --}}
        <div class="bg-white rounded-2xl border border-emerald-100/90 shadow-sm p-6">
            <div class="space-y-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Data</span>
                    <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $diniyahs->total() }}</p>
                </div>
                <div class="pt-3 border-t border-slate-100">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Halaman Saat Ini</span>
                    <p class="text-lg font-bold text-slate-700 mt-1">{{ $diniyahs->count() }} data</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Diniyah --}}
    <div class="bg-white rounded-2xl border border-emerald-100/90 shadow-sm overflow-hidden">
        {{-- Header Tabel & Filter --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/40 via-white to-white">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3">Filter & Cari Data</h3>
            </div>
            <form method="GET" action="{{ route('cabang.diniyah.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kategori</label>
                    <select name="jenis_evaluasi" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat }}" {{ request('jenis_evaluasi') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Cari Santri / Materi</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau surah..." class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                </div>
                <div class="flex gap-2 sm:col-span-2 lg:col-span-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-all">Terapkan</button>
                    @if(request()->hasAny(['jenis_evaluasi', 'search']))
                        <a href="{{ route('cabang.diniyah.index') }}" class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-all">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-600">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-600">Santri</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-600">Kategori</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-600">Nilai</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-600">Predikat</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($diniyahs as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap font-medium">{{ $item->tanggal_evaluasi?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-xs">
                                <p class="font-semibold text-slate-900">{{ $item->anakAsuh?->nama_lengkap ?? '-' }}</p>
                                <span class="text-[10px] text-slate-500">NIS: {{ $item->anakAsuh?->nomor_induk }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-1">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 w-fit">
                                        {{ $item->jenis_evaluasi }}
                                    </span>
                                    <span class="text-xs font-medium text-slate-800">{{ $item->materi }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-xs text-center">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg font-bold {{ $item->nilai >= 80 ? 'bg-emerald-100 text-emerald-700' : ($item->nilai >= 70 ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $item->nilai }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1.5 rounded-full text-[10px] font-bold {{ $item->nilai >= 85 ? 'bg-emerald-100 text-emerald-800' : ($item->nilai >= 70 ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ $item->predikat }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('cabang.diniyah.show', $item->id) }}" class="p-2 rounded-lg text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('cabang.diniyah.edit', $item->id) }}" class="p-2 rounded-lg text-slate-400 hover:text-blue-700 hover:bg-blue-50 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-sm font-semibold text-slate-600 mb-1">Belum Ada Data</p>
                                    <p class="text-xs text-slate-500 mb-4">Silakan input nilai pengetahuan diniyah untuk melihat data di sini</p>
                                    <a href="{{ route('cabang.diniyah.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        <span>Input Nilai Diniyah</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($diniyahs->hasPages())
            <div class="px-4 py-4 border-t border-slate-100 bg-slate-50/50">
                <div class="flex items-center justify-between gap-4 text-xs text-slate-600">
                    <div>
                        Menampilkan {{ $diniyahs->firstItem() }} hingga {{ $diniyahs->lastItem() }} dari {{ $diniyahs->total() }} data
                    </div>
                    <div class="overflow-auto">
                        {{ $diniyahs->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

