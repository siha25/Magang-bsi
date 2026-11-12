{{-- BAGIAN: PENGURUS PUSAT - Review Setoran Hafalan Hadis Pending --}}
@extends('pusat.layout.app')

@section('title', 'Review Hafalan Hadis')
@section('header_title', 'Review Hafalan Hadis')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('pusat.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Review Hafalan Hadis</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Review Setoran Hafalan Hadis</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Daftar setoran yang menunggu persetujuan Admin Pendik.</p>
        </div>
        <a href="{{ route('pusat.review-hafalan.quran.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-semibold transition-all">
            &larr; Lihat Hafalan Al-Qur'an
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1 text-xs sm:text-sm font-medium">{{ session('success') }}</div>
        </div>
    @endif

    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-medium">
        ⏳ <strong>{{ $totalPending }}</strong> setoran hafalan Hadis sedang menunggu review.
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/30 via-white to-white">
            <form method="GET" action="{{ route('pusat.review-hafalan.hadis.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama santri, nomor induk, atau hadis..." class="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                </div>
                <div class="flex gap-2">
                    <select name="asrama_id" class="flex-1 px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        <option value="">Semua Cabang</option>
                        @foreach($asramaOptions as $asrama)
                            <option value="{{ $asrama->id }}" {{ request('asrama_id') == $asrama->id ? 'selected' : '' }}>{{ $asrama->nama_asrama }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold">Cari</button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal Setoran</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Santri</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Cabang</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Hadis Disetorkan</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Predikat Awal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Diajukan</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($hafalans as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">{{ $item->tanggal_evaluasi?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-xs">
                                <p class="font-bold text-slate-900">{{ $item->anakAsuh?->nama_lengkap ?? '-' }}</p>
                                <span class="text-[10px] text-slate-400">NIS: {{ $item->anakAsuh?->nomor_induk ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-700">{{ $item->asrama?->nama_asrama ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-800 font-medium max-w-xs truncate">{{ $item->materi }}</td>
                            <td class="px-4 py-3 text-xs text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">{{ $item->predikat ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">{{ $item->diajukan_pada?->diffForHumans() }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('pusat.review-hafalan.show', $item->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-colors">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-xs text-slate-500">
                                Tidak ada setoran hafalan hadis yang menunggu review saat ini.
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
