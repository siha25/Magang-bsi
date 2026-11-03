{{-- BAGIAN: PENGURUS CABANG - Daftar Kegiatan Harian Cabang --}}
@extends('cabang.layout.app')

@section('title', 'Kegiatan Harian - ' . ($cabang?->nama_asrama ?? ''))
@section('header_title', 'Kegiatan Harian')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('cabang.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Kegiatan Harian</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Kegiatan Harian Santri</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Catatan aktivitas harian cabang <strong>{{ $cabang?->nama_asrama }}</strong> ({{ $cabang?->wilayah }}).</p>
        </div>

        <a href="{{ route('cabang.kegiatan-harian.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs sm:text-sm font-semibold shadow-md shadow-orange-500/25 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Kegiatan</span>
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

    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        {{-- Filter Bar --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/30 via-white to-white">
            <form method="GET" action="{{ route('cabang.kegiatan-harian.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center">
                <div>
                    <label for="filter-tanggal" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Tanggal</label>
                    <input id="filter-tanggal" type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                </div>
                <div>
                    <label for="filter-kategori" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Kategori</label>
                    <select id="filter-kategori" name="kategori" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        <option value="">Semua Kategori</option>
                        <option value="belajar" {{ request('kategori') === 'belajar' ? 'selected' : '' }}>Belajar</option>
                        <option value="muhadhoroh" {{ request('kategori') === 'muhadhoroh' ? 'selected' : '' }}>Muhadhoroh</option>
                        <option value="olahraga" {{ request('kategori') === 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                        <option value="acara_donatur" {{ request('kategori') === 'acara_donatur' ? 'selected' : '' }}>Acara Donatur</option>
                        <option value="bersih_bersih" {{ request('kategori') === 'bersih_bersih' ? 'selected' : '' }}>Bersih-bersih</option>
                    </select>
                </div>
                <div class="flex gap-2 self-end">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-colors">Terapkan Filter</button>
                    @if(request()->hasAny(['tanggal', 'kategori']))
                        <a href="{{ route('cabang.kegiatan-harian.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Kegiatan --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Nama Kegiatan</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Peserta</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Penginput</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($kegiatans as $kegiatan)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3 text-xs text-slate-700 whitespace-nowrap">{{ $kegiatan->tanggal_kegiatan?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $kegiatan->kategori_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs font-semibold text-slate-900">{{ $kegiatan->nama_kegiatan }}</td>
                            <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">{{ $kegiatan->jam_mulai_format }} - {{ $kegiatan->jam_selesai_format }}</td>
                            <td class="px-4 py-3 text-xs text-slate-700">{{ $kegiatan->jumlah_peserta }} anak</td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $kegiatan->penginput?->name ?? 'Pengurus' }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('cabang.kegiatan-harian.show', $kegiatan->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium text-emerald-700 hover:bg-emerald-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-xs text-slate-500">
                                Belum ada kegiatan harian yang tercatat di cabang ini.
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

