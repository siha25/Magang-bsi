{{-- BAGIAN: PENGURUS CABANG - Daftar Pengetahuan Diniyah Cabang --}}
@extends('cabang.layout.app')

@section('title', 'Pengetahuan Diniyah - ' . ($cabang?->nama_asrama ?? ''))
@section('header_title', 'Pengetahuan Diniyah')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('cabang.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Pengetahuan Diniyah</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Nilai Pengetahuan Diniyah</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Penilaian materi hadis, fiqih, aqidah, akhlak, dan tajwid cabang <strong>{{ $cabang?->nama_asrama }}</strong>.</p>
        </div>

        <a href="{{ route('cabang.diniyah.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/25 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Input Nilai Diniyah</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1 text-xs sm:text-sm font-medium">{{ session('success') }}</div>
        </div>
    @endif

    {{-- Tabel Diniyah --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/30 via-white to-white">
            <form method="GET" action="{{ route('cabang.diniyah.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <select name="jenis_evaluasi" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        <option value="">Semua Kategori Diniyah</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat }}" {{ request('jenis_evaluasi') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari santri atau materi..." class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold">Terapkan</button>
                    @if(request()->hasAny(['jenis_evaluasi', 'search']))
                        <a href="{{ route('cabang.diniyah.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Santri</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Kategori & Materi</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Nilai</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Predikat</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($diniyahs as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">{{ $item->tanggal_evaluasi?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-xs">
                                <p class="font-bold text-slate-900">{{ $item->anakAsuh?->nama_lengkap ?? '-' }}</p>
                                <span class="text-[10px] text-slate-400">NIS: {{ $item->anakAsuh?->nomor_induk }} &bull; {{ $item->anakAsuh?->jenjang_pendidikan }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    {{ $item->jenis_evaluasi }}
                                </span>
                                <p class="font-medium text-slate-800 mt-1">{{ $item->materi }}</p>
                            </td>
                            <td class="px-4 py-3 text-xs text-center font-bold {{ $item->nilai >= 70 ? 'text-emerald-700' : 'text-amber-600' }}">
                                {{ $item->nilai }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $item->nilai >= 85 ? 'bg-emerald-100 text-emerald-800' : ($item->nilai >= 70 ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ $item->predikat }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                                <a href="{{ route('cabang.diniyah.show', $item->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 transition-colors inline-block" title="Detail">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('cabang.diniyah.edit', $item->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-700 hover:bg-blue-50 transition-colors inline-block" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-xs text-slate-500">
                                Belum ada data nilai pengetahuan diniyah yang dicatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($diniyahs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $diniyahs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

