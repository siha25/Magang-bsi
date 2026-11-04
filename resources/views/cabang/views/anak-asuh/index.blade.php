{{-- BAGIAN: PENGURUS CABANG - Daftar Santri Cabang --}}
@extends('cabang.layout.app')

@section('title', 'Data Santri - ' . ($cabang?->nama_asrama ?? ''))
@section('header_title', 'Data Santri')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('cabang.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Santri Cabang</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Data Santri / Anak Asuh</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Daftar santri yang terdaftar dan bernaung di cabang <strong>{{ $cabang?->nama_asrama }}</strong>.</p>
        </div>
    </div>

    {{-- Kartu Statistik Ringkas --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-emerald-100 shadow-xs">
            <span class="text-[10px] font-bold uppercase text-slate-400">Total Santri</span>
            <p class="text-xl font-bold text-slate-900 mt-0.5">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-emerald-100 shadow-xs">
            <span class="text-[10px] font-bold uppercase text-slate-400">Jenjang SD</span>
            <p class="text-xl font-bold text-slate-900 mt-0.5">{{ $stats['total_sd'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-emerald-100 shadow-xs">
            <span class="text-[10px] font-bold uppercase text-slate-400">Jenjang SMP</span>
            <p class="text-xl font-bold text-slate-900 mt-0.5">{{ $stats['total_smp'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-emerald-100 shadow-xs">
            <span class="text-[10px] font-bold uppercase text-slate-400">Jenjang SMA</span>
            <p class="text-xl font-bold text-slate-900 mt-0.5">{{ $stats['total_sma'] }}</p>
        </div>
    </div>

    {{-- Tabel Santri --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/30 via-white to-white">
            <form method="GET" action="{{ route('cabang.santri.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nomor induk atau nama santri..." class="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                </div>
                <div>
                    <select name="jenjang" class="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        <option value="">Semua Jenjang</option>
                        <option value="SD" {{ request('jenjang') === 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ request('jenjang') === 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ request('jenjang') === 'SMA' ? 'selected' : '' }}>SMA</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold">Filter</button>
                    @if(request()->hasAny(['cari', 'jenjang']))
                        <a href="{{ route('cabang.santri.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Nomor Induk</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Nama Lengkap</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">L/P</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Jenjang</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal Masuk</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($anakAsuhs as $santri)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3 text-xs font-mono font-bold text-emerald-800">{{ $santri->nomor_induk }}</td>
                            <td class="px-4 py-3 text-xs font-bold text-slate-900">{{ $santri->nama_lengkap }}</td>
                            <td class="px-4 py-3 text-xs text-slate-600">{{ $santri->jenis_kelamin }}</td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $santri->jenjang_pendidikan }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600">{{ $santri->tanggal_masuk?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-4 py-3 text-center space-x-1">
                                <a href="{{ route('cabang.hafalan-quran.create', ['anak_asuh_id' => $santri->id]) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[11px] font-medium bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors" title="Input Hafalan">
                                    <span>+ Hafalan</span>
                                </a>
                                <a href="{{ route('cabang.diniyah.create', ['anak_asuh_id' => $santri->id]) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[11px] font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors" title="Input Diniyah">
                                    <span>+ Diniyah</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-xs text-slate-500">
                                Belum ada santri yang terdaftar di cabang ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($anakAsuhs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $anakAsuhs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

