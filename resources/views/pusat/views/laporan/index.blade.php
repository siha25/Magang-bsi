{{-- BAGIAN: PENGURUS PUSAT - Pusat Laporan & Rekapitulasi Seluruh Cabang --}}
@extends('pusat.layout.app')

@section('title', __('Laporan & Rekapitulasi'))
@section('header_title', __('Laporan & Rekapitulasi'))

@section('content')
@php
    $activeJenis = $filters['jenis'];
    $hasActiveFilter = ! empty($filters['cabang_id'])
        || $filters['wilayah'] !== ''
        || $filters['jenjang'] !== ''
        || ! empty($filters['tanggal_mulai'])
        || ! empty($filters['tanggal_selesai']);
    $periodeLabel = ($filters['tanggal_mulai'] || $filters['tanggal_selesai'])
        ? (($filters['tanggal_mulai'] ?: '...') . ' s/d ' . ($filters['tanggal_selesai'] ?: '...'))
        : __('Semua Periode');
@endphp

<div class="space-y-6">

    {{-- Kop Surat Resmi Khusus Mode Cetak / PDF --}}
    <div class="hidden print:block border-b-2 border-slate-800 pb-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 uppercase tracking-wide">Yayasan Rumah Harapan — Divisi Pendidikan Anak Asuh</h1>
                <p class="text-xs text-slate-600 mt-1">
                    {{ __('Laporan Rekapitulasi Pendidikan & Pembinaan Santri Seluruh Cabang') }}
                </p>
            </div>
            <div class="text-right text-xs text-slate-600">
                <p><strong>{{ __('Jenis Laporan') }}:</strong> {{ __($jenisOptions[$activeJenis] ?? 'Rekapitulasi Asrama') }}</p>
                <p><strong>{{ __('Periode') }}:</strong> {{ $periodeLabel }}</p>
                <p><strong>{{ __('Dicetak') }}:</strong> {{ now()->format('d/m/Y H:i') }} WIB</p>
            </div>
        </div>
    </div>

    {{-- Header Halaman + Tombol Ekspor & Cetak --}}
    <div class="no-print flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                {{ __('Pusat Laporan & Rekapitulasi') }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                {{ __('Rekap data asrama, kegiatan harian, setoran hafalan Al-Qur\'an & Hadis, serta nilai Pengetahuan Diniyah seluruh cabang.') }}
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 self-start sm:self-auto">
            <a href="{{ route('pusat.laporan.export', request()->query()) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm shadow-emerald-600/20 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>{{ __('Unduh CSV (Excel)') }}</span>
            </a>

            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold shadow-sm shadow-orange-500/20 transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>{{ __('Cetak / Simpan PDF') }}</span>
            </button>
        </div>
    </div>

    {{-- Bar Filter Multi-Kriteria --}}
    <div class="no-print bg-white rounded-3xl p-5 border border-emerald-100/90 shadow-xs">
        <form method="GET" action="{{ route('pusat.laporan.index') }}">
            <input type="hidden" name="jenis" value="{{ $activeJenis }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-12 gap-3.5 items-end">
                {{-- Filter Asrama / Cabang --}}
                <div class="xl:col-span-2">
                    <label for="cabang_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">{{ __('Asrama / Cabang') }}</label>
                    <select name="cabang_id" id="cabang_id" class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                        <option value="">{{ __('Semua Cabang') }}</option>
                        @foreach($cabangOptions as $c)
                            <option value="{{ $c->id }}" {{ (string) $filters['cabang_id'] === (string) $c->id ? 'selected' : '' }}>
                                {{ $c->nama_asrama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Wilayah --}}
                <div class="xl:col-span-2">
                    <label for="wilayah" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">{{ __('Wilayah / Daerah') }}</label>
                    <select name="wilayah" id="wilayah" class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                        <option value="">{{ __('Semua Wilayah') }}</option>
                        @foreach($wilayahOptions as $w)
                            <option value="{{ $w }}" {{ $filters['wilayah'] === $w ? 'selected' : '' }}>{{ $w }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Jenjang --}}
                <div class="xl:col-span-2">
                    <label for="jenjang" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">{{ __('Jenjang') }}</label>
                    <select name="jenjang" id="jenjang" class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                        <option value="">{{ __('Semua Jenjang') }}</option>
                        @foreach($jenjangOptions as $j)
                            <option value="{{ $j }}" {{ $filters['jenjang'] === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Dari Tanggal --}}
                <div class="xl:col-span-2">
                    <label for="tanggal_mulai" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">{{ __('Dari Tanggal') }}</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $filters['tanggal_mulai'] }}"
                           class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                </div>

                {{-- Sampai Tanggal --}}
                <div class="xl:col-span-2">
                    <label for="tanggal_selesai" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">{{ __('Sampai Tanggal') }}</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $filters['tanggal_selesai'] }}"
                           class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                </div>

                {{-- Tombol Aksi --}}
                <div class="xl:col-span-2 flex items-center gap-2">
                    <button type="submit" class="h-10 flex-1 inline-flex items-center justify-center gap-1.5 px-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors cursor-pointer whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>{{ __('Terapkan Filter') }}</span>
                    </button>
                    @if($hasActiveFilter)
                        <a href="{{ route('pusat.laporan.index', ['jenis' => $activeJenis]) }}"
                           title="{{ __('Reset') }}"
                           class="h-10 inline-flex items-center justify-center px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors shrink-0">
                            &times;
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- 4 Kartu Metrik Eksekutif --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        {{-- Kartu 1: Santri Aktif --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">{{ __('Santri Aktif') }}</span>
            <span class="text-2xl font-extrabold text-slate-900">{{ number_format($summary['total_anak_aktif']) }}</span>
            <p class="text-[11px] text-slate-500 mt-2">
                SD {{ $summary['sd_count'] }} &bull; SMP {{ $summary['smp_count'] }} &bull; SMA {{ $summary['sma_count'] }}
            </p>
        </div>

        {{-- Kartu 2: Kegiatan Harian --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">{{ __('Kegiatan Harian') }}</span>
            <span class="text-2xl font-extrabold text-slate-900">{{ number_format($summary['total_kegiatan']) }}</span>
            <p class="text-[11px] text-slate-500 mt-2">
                {{ number_format($summary['total_kehadiran']) }} {{ __('total kehadiran santri') }}
            </p>
        </div>

        {{-- Kartu 3: Setoran Hafalan Qur'an & Hadis --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">{{ __("Hafalan Qur'an & Hadis") }}</span>
            <span class="text-2xl font-extrabold text-slate-900">
                {{ number_format($summary['total_hafalan_quran'] + $summary['total_hafalan_hadis']) }}
            </span>
            <p class="text-[11px] text-slate-500 mt-2">
                Qur'an {{ $summary['total_hafalan_quran'] }} ({{ $summary['quran_disetujui'] }} {{ __('disetujui') }}) &bull; Hadis {{ $summary['total_hafalan_hadis'] }}
            </p>
        </div>

        {{-- Kartu 4: Rata-Rata Pengetahuan Diniyah --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">{{ __('Rata-Rata Diniyah') }}</span>
            <span class="text-2xl font-extrabold {{ $summary['rata_nilai_diniyah'] >= 70 || $summary['total_diniyah'] === 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $summary['total_diniyah'] > 0 ? $summary['rata_nilai_diniyah'] : '-' }}
            </span>
            <p class="text-[11px] text-slate-500 mt-2">
                {{ $summary['total_diniyah'] }} {{ __('penilaian') }} &bull; {{ $summary['diniyah_bawah_kkm'] }} {{ __('di bawah KKM') }}
            </p>
        </div>
    </div>

    {{-- Tab Navigasi Jenis Laporan --}}
    <div class="no-print flex flex-wrap items-center gap-2 bg-white p-2 rounded-2xl border border-emerald-100/90 shadow-xs">
        @foreach($jenisOptions as $key => $label)
            @php
                $isActiveTab = $activeJenis === $key;
                $tabParams = array_filter([
                    'jenis' => $key,
                    'cabang_id' => $filters['cabang_id'],
                    'wilayah' => $filters['wilayah'],
                    'jenjang' => $filters['jenjang'],
                    'tanggal_mulai' => $filters['tanggal_mulai'],
                    'tanggal_selesai' => $filters['tanggal_selesai'],
                ], fn ($v) => $v !== null && $v !== '');
            @endphp
            <a href="{{ route('pusat.laporan.index', $tabParams) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all {{ $isActiveTab ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                {{ __($label) }}
            </a>
        @endforeach
    </div>

    {{-- Konten Tabel Laporan Berdasarkan Tab Aktif --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="text-sm font-bold text-slate-900">
                    {{ __($jenisOptions[$activeJenis] ?? 'Rekapitulasi Asrama') }}
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ __('Periode') }}: {{ $periodeLabel }}
                </p>
            </div>
            @if($items)
                <span class="text-xs text-slate-500 font-medium">
                    {{ __('Halaman') }} {{ $items->currentPage() }} {{ __('dari') }} {{ $items->lastPage() }} ({{ $items->total() }} {{ __('data') }})
                </span>
            @else
                <span class="text-xs text-slate-500 font-medium">
                    {{ $rekapAsrama->count() }} {{ __('Asrama Aktif') }}
                </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            @if($activeJenis === 'ringkasan')
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4">No</th>
                            <th class="py-3.5 px-4">{{ __('Asrama / Cabang') }}</th>
                            <th class="py-3.5 px-4 text-center">{{ __('Santri Aktif') }}</th>
                            <th class="py-3.5 px-4 text-center">{{ __('Kegiatan Harian') }}</th>
                            <th class="py-3.5 px-4 text-center">{{ __("Hafalan Al-Qur'an") }}</th>
                            <th class="py-3.5 px-4 text-center">{{ __('Hafalan Hadis') }}</th>
                            <th class="py-3.5 px-4 text-center">{{ __('Rata-Rata Diniyah') }}</th>
                            <th class="py-3.5 px-4 text-center">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($rekapAsrama as $idx => $row)
                            @php
                                $badgeClass = match($row['status_warna']) {
                                    'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'amber' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    default => 'bg-slate-50 text-slate-500 border-slate-200',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 font-semibold text-slate-500">{{ $idx + 1 }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $row['nama_asrama'] }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $row['kode_asrama'] }} &bull; {{ $row['wilayah'] }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-slate-800">{{ number_format($row['santri_aktif']) }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="font-bold text-slate-800 block">{{ number_format($row['total_kegiatan']) }}</span>
                                    <span class="text-[10px] text-slate-400">{{ number_format($row['total_kehadiran']) }} peserta</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="font-bold text-slate-800">{{ $row['quran_total'] }}</span>
                                    <span class="text-[10px] text-emerald-600 block">{{ $row['quran_disetujui'] }} disetujui</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="font-bold text-slate-800">{{ $row['hadis_total'] }}</span>
                                    <span class="text-[10px] text-emerald-600 block">{{ $row['hadis_disetujui'] }} disetujui</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($row['rata_diniyah'] !== null)
                                        <span class="text-sm font-extrabold {{ $row['rata_diniyah'] >= 70 ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $row['rata_diniyah'] }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 block">{{ $row['diniyah_total'] }} ujian</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $badgeClass }}">
                                        {{ $row['status_laporan'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-500">
                                    {{ __('Belum ada data asrama aktif yang sesuai dengan filter.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($activeJenis === 'kegiatan')
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4">{{ __('Tanggal') }}</th>
                            <th class="py-3.5 px-4">{{ __('Asrama / Cabang') }}</th>
                            <th class="py-3.5 px-4">{{ __('Nama Kegiatan') }}</th>
                            <th class="py-3.5 px-4">{{ __('Kategori') }}</th>
                            <th class="py-3.5 px-4 text-center">{{ __('Kehadiran') }}</th>
                            <th class="py-3.5 px-4">{{ __('Penginput') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($items as $keg)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-medium text-slate-800">{{ $keg->tanggal_kegiatan?->format('d M Y') }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $keg->jam_mulai_format }} - {{ $keg->jam_selesai_format }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $keg->asrama?->nama_asrama ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $keg->asrama?->wilayah ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $keg->nama_kegiatan }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $keg->kategori_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-orange-700">{{ number_format($keg->jumlah_peserta) }} santri</td>
                                <td class="py-3.5 px-4">{{ $keg->penginput?->name ?? 'Staff Cabang' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-500">
                                    {{ __('Tidak ada data kegiatan harian yang sesuai dengan filter.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($activeJenis === 'pengetahuan_diniyah')
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4">{{ __('Tanggal') }}</th>
                            <th class="py-3.5 px-4">{{ __('Nama Anak Asuh') }}</th>
                            <th class="py-3.5 px-4">{{ __('Asrama / Cabang') }}</th>
                            <th class="py-3.5 px-4">{{ __('Materi') }}</th>
                            <th class="py-3.5 px-4 text-center">{{ __('Nilai') }}</th>
                            <th class="py-3.5 px-4">{{ __('Predikat') }}</th>
                            <th class="py-3.5 px-4">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($items as $ev)
                            @php
                                $nilai = $ev->nilai !== null ? (float) $ev->nilai : null;
                                $nilaiStr = $nilai !== null ? rtrim(rtrim(number_format($nilai, 1, '.', ''), '0'), '.') : '-';
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 whitespace-nowrap">{{ $ev->tanggal_evaluasi?->format('d M Y') }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $ev->anakAsuh?->nama_lengkap ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $ev->anakAsuh?->nomor_induk ?? '-' }} &bull; {{ $ev->anakAsuh?->jenjang_pendidikan ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4">{{ $ev->asrama?->nama_asrama ?? '-' }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 mb-0.5">{{ $ev->jenis_evaluasi }}</span>
                                    <div class="text-slate-700">{{ $ev->materi }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-center font-extrabold {{ $nilai !== null && $nilai >= 70 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $nilaiStr }}</td>
                                <td class="py-3.5 px-4">{{ $ev->predikat ?? '-' }}</td>
                                <td class="py-3.5 px-4 capitalize">{{ str_replace('_', ' ', $ev->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-500">
                                    {{ __('Tidak ada data penilaian Pengetahuan Diniyah yang sesuai dengan filter.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4">{{ __('Tanggal') }}</th>
                            <th class="py-3.5 px-4">{{ __('Nama Anak Asuh') }}</th>
                            <th class="py-3.5 px-4">{{ __('Asrama / Cabang') }}</th>
                            <th class="py-3.5 px-4">{{ __('Materi Hafalan') }}</th>
                            <th class="py-3.5 px-4 text-center">{{ __('Predikat Cabang') }}</th>
                            <th class="py-3.5 px-4 text-center">{{ __('Predikat Pusat') }}</th>
                            <th class="py-3.5 px-4">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($items as $ev)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 whitespace-nowrap">{{ $ev->tanggal_evaluasi?->format('d M Y') }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $ev->anakAsuh?->nama_lengkap ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $ev->anakAsuh?->nomor_induk ?? '-' }} &bull; {{ $ev->anakAsuh?->jenjang_pendidikan ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4">{{ $ev->asrama?->nama_asrama ?? '-' }}</td>
                                <td class="py-3.5 px-4 font-medium text-slate-800">{{ $ev->materi }}</td>
                                <td class="py-3.5 px-4 text-center font-bold">{{ $ev->predikat ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-center font-bold text-emerald-700">{{ $ev->predikat_akhir ?? '-' }}</td>
                                <td class="py-3.5 px-4 capitalize">{{ str_replace('_', ' ', $ev->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-500">
                                    {{ __('Tidak ada data setoran hafalan yang sesuai dengan filter.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>

        @if($items && $items->hasPages())
            <div class="no-print p-4 border-t border-slate-100">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    {{-- Blok Tanda Tangan Resmi Khusus Mode Cetak --}}
    <div class="hidden print:grid grid-cols-2 gap-12 pt-10 text-xs text-slate-700">
        <div class="text-center">
            <p>{{ __('Mengetahui,') }}</p>
            <p class="font-bold mt-1">{{ __('Pimpinan / Manager Pusat') }}</p>
            <div class="h-16"></div>
            <p class="border-t border-slate-400 inline-block px-8 pt-1">( ________________________ )</p>
        </div>
        <div class="text-center">
            <p>{{ __('Disusun Oleh,') }}</p>
            <p class="font-bold mt-1">{{ auth()->user()?->name ?? 'Admin Pendik' }}</p>
            <div class="h-16"></div>
            <p class="border-t border-slate-400 inline-block px-8 pt-1">( {{ auth()->user()?->name ?? 'Admin Pendik' }} )</p>
        </div>
    </div>

</div>
@endsection
