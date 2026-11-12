{{-- BAGIAN: PENGURUS CABANG - Input & Daftar Hafalan Hadis Cabang --}}
@extends('cabang.layout.app')

@section('title', 'Hafalan Hadis - ' . ($cabang?->nama_asrama ?? ''))
@section('header_title', 'Hafalan Hadis')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('cabang.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Hafalan Hadis</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Hafalan Hadis Santri</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pencatatan setoran hafalan hadis santri cabang <strong>{{ $cabang?->nama_asrama }}</strong>.</p>
        </div>

        <a href="{{ route('cabang.hafalan-hadist.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/25 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Input Setoran Hadis</span>
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

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-xs">
            <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1 text-xs sm:text-sm font-medium">{{ session('error') }}</div>
        </div>
    @endif

    {{-- Kartu Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Setoran</span>
            <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['total']) }}</span>
            <p class="text-[11px] text-slate-500 mt-1">Hafalan hadis tercatat</p>
        </div>
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-amber-100/90 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Menunggu Review</span>
            <span class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</span>
            <p class="text-[11px] text-slate-500 mt-1">Setoran belum disetujui Admin Pendik</p>
        </div>
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Disetujui</span>
            <span class="text-2xl font-bold text-emerald-700">{{ $stats['disetujui'] }}</span>
            <p class="text-[11px] text-slate-500 mt-1">Setoran telah disetujui</p>
        </div>
    </div>

    {{-- Tabel Setoran Hadis --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/30 via-white to-white">
            <form method="GET" action="{{ route('cabang.hafalan-hadist.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div class="sm:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama santri, nomor induk, atau hadis..." class="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                </div>
                <div>
                    <select name="status" class="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold">Cari</button>
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('cabang.hafalan-hadist.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Santri / Anak Asuh</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Materi / Hadis</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Predikat Awal</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Predikat Akhir</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($hafalans as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">{{ $item->tanggal_evaluasi?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-xs">
                                <p class="font-bold text-slate-900">{{ $item->anakAsuh?->nama_lengkap ?? '-' }}</p>
                                <span class="text-[10px] text-slate-400">NIS: {{ $item->anakAsuh?->nomor_induk ?? '-' }} &bull; {{ $item->anakAsuh?->jenjang_pendidikan }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-800 font-medium">{{ $item->materi }}</td>
                            <td class="px-4 py-3 text-xs text-center">
                                @if($item->status === 'pending')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700">Pending</span>
                                @elseif($item->status === 'disetujui')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">Disetujui</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">{{ $item->predikat ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-center">
                                @if($item->predikat_akhir)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">{{ $item->predikat_akhir }}</span>
                                @else
                                    <span class="text-[10px] text-slate-400 italic">Belum direview</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                                <a href="{{ route('cabang.hafalan-hadist.show', $item->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 transition-colors inline-block" title="Detail">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @if($item->status === 'pending')
                                    <a href="{{ route('cabang.hafalan-hadist.edit', $item->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-700 hover:bg-blue-50 transition-colors inline-block" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                @else
                                    <span class="p-1.5 rounded-lg text-slate-200 inline-block cursor-not-allowed" title="Tidak dapat diedit (sudah disetujui)">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-xs text-slate-500">
                                Belum ada riwayat hafalan hadis untuk cabang ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($hafalans->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $hafalans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
