{{-- BAGIAN: PENGURUS PUSAT - Monitoring Pengetahuan Diniyah Seluruh Cabang --}}
@extends('pusat.layout.app')

@section('title', 'Monitoring Pengetahuan Diniyah')
@section('header_title', 'Monitoring Pengetahuan Diniyah')

@section('content')
<div class="space-y-6">

    {{-- Header Banner --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                Monitoring Pengetahuan Diniyah
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pantau capaian nilai Pengetahuan Diniyah seluruh anak asuh dari semua cabang. Data tidak mencakup Hafalan Al-Qur'an dan Hafalan Hadis.
            </p>
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-teal-50 border border-teal-200 text-xs font-semibold text-teal-800 self-start sm:self-auto">
            <span class="w-2 h-2 rounded-full bg-teal-500"></span>
            <span>Total {{ $evaluasis->total() }} Data Penilaian</span>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-3xl p-5 border border-emerald-100/90 shadow-xs">
        <form method="GET" action="{{ route('pusat.monitoring-diniyah.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 items-end">
                {{-- Cari Nama Anak --}}
                <div>
                    <label for="cari" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Cari Nama Anak</label>
                    <input type="text" name="cari" id="cari" value="{{ $filters['cari'] }}" placeholder="Ketik nama / no. induk…"
                           class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                </div>

                {{-- Filter Asrama / Cabang --}}
                <div>
                    <label for="cabang_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Asrama / Cabang</label>
                    <select name="cabang_id" id="cabang_id" class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                        <option value="">Semua Cabang</option>
                        @foreach($cabangOptions as $c)
                            <option value="{{ $c->id }}" {{ (string)$filters['cabang_id'] === (string)$c->id ? 'selected' : '' }}>
                                {{ $c->nama_asrama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Jenjang --}}
                <div>
                    <label for="jenjang" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Jenjang</label>
                    <select name="jenjang" id="jenjang" class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                        <option value="">Semua Jenjang</option>
                        @foreach($jenjangOptions as $j)
                            <option value="{{ $j }}" {{ ($filters['jenjang'] ?? '') === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Materi / Jenis --}}
                <div>
                    <label for="materi" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Materi / Jenis</label>
                    <select name="materi" id="materi" class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                        <option value="">Semua Materi</option>
                        @foreach($materiOptions as $m)
                            <option value="{{ $m }}" {{ $filters['materi'] === $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Tanggal Mulai --}}
                <div>
                    <label for="tanggal_mulai" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $filters['tanggal_mulai'] }}"
                           class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                </div>

                {{-- Filter Tanggal Selesai --}}
                <div>
                    <label for="tanggal_selesai" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $filters['tanggal_selesai'] }}"
                           class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                </div>

                {{-- Tombol Aksi --}}
                <div class="sm:col-span-2 lg:col-span-2 flex items-center justify-start lg:justify-end gap-2">
                    <button type="submit" class="h-10 inline-flex items-center justify-center gap-1.5 px-5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors cursor-pointer whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Terapkan Filter</span>
                    </button>
                    @if(array_filter($filters))
                        <a href="{{ route('pusat.monitoring-diniyah.index') }}" class="h-10 inline-flex items-center justify-center px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Dua Grafik: Rata-Rata per Cabang & Rata-Rata per Materi --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Grafik Rata-Rata Nilai per Cabang --}}
        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-slate-900">Rata-Rata Nilai per Cabang</h3>
                <p class="text-xs text-slate-500">Perbandingan capaian Pengetahuan Diniyah antar cabang (garis putus-putus = KKM 70)</p>
            </div>
            <div class="relative h-64 sm:h-72 w-full">
                <canvas id="chartNilaiCabangDiniyah"></canvas>
            </div>
        </div>

        {{-- Grafik Rata-Rata Nilai per Materi --}}
        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-slate-900">Rata-Rata Nilai per Bidang Materi</h3>
                <p class="text-xs text-slate-500">Materi yang sudah dikuasai vs yang butuh penguatan</p>
            </div>
            <div class="relative h-64 sm:h-72 w-full">
                <canvas id="chartNilaiMateri"></canvas>
            </div>
        </div>
    </div>

    {{-- Tabel Penilaian Pengetahuan Diniyah --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Daftar Nilai Pengetahuan Diniyah</h3>
                <p class="text-xs text-slate-500 mt-0.5">Semua data penilaian Pengetahuan Diniyah dari seluruh cabang (Read-Only)</p>
            </div>
            <span class="text-xs text-slate-500 font-medium">Halaman {{ $evaluasis->currentPage() }} dari {{ $evaluasis->lastPage() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">Nama Anak Asuh</th>
                        <th class="py-3.5 px-4">Asrama / Cabang</th>
                        <th class="py-3.5 px-4">Materi</th>
                        <th class="py-3.5 px-4">Tanggal</th>
                        <th class="py-3.5 px-4 text-center">Nilai</th>
                        <th class="py-3.5 px-4">Predikat</th>
                        <th class="py-3.5 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($evaluasis as $ev)
                        @php
                            $nilai = (float) $ev->nilai;
                            $lulusKKM = $nilai >= 70;
                            $nilaiFormatted = rtrim(rtrim(number_format($nilai, 1, '.', ''), '0'), '.');

                            // Warna predikat sesuai format "Mumtaz (A)", "Jayyid Jiddan (B)", dst.
                            $predikatStr = $ev->predikat ?? '';
                            $predikatClass = match(true) {
                                str_contains($predikatStr, 'Mumtaz')        => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                str_contains($predikatStr, 'Jayyid Jiddan') => 'bg-teal-50 text-teal-700 border-teal-200',
                                str_contains($predikatStr, 'Jayyid')        => 'bg-blue-50 text-blue-700 border-blue-200',
                                str_contains($predikatStr, 'Maqbul')        => 'bg-amber-50 text-amber-700 border-amber-200',
                                default                                     => 'bg-rose-50 text-rose-700 border-rose-200',
                            };

                            $statusClass = match($ev->status) {
                                'disetujui'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'perlu_revisi' => 'bg-amber-50 text-amber-700 border-amber-200',
                                default        => 'bg-slate-50 text-slate-500 border-slate-200',
                            };
                            $statusLabel = match($ev->status) {
                                'disetujui'    => 'Disetujui',
                                'perlu_revisi' => 'Perlu Revisi',
                                default        => 'Pending',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $ev->anakAsuh?->nama_lengkap ?? 'Santri #'.$ev->anak_asuh_id }}</div>
                                <div class="text-[11px] text-slate-400">NIS: {{ $ev->anakAsuh?->nomor_induk ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $ev->asrama?->nama_asrama ?? 'Cabang #'.$ev->asrama_id }}</span>
                                <span class="text-[11px] text-slate-400">{{ $ev->asrama?->wilayah ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 mb-1">
                                    {{ $ev->jenis_evaluasi }}
                                </span>
                                <div class="font-medium text-slate-700 truncate max-w-[180px]" title="{{ $ev->materi }}">{{ $ev->materi }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-slate-800">{{ $ev->tanggal_evaluasi?->format('d M Y') }}</span>
                                @if($ev->bulan && $ev->tahun)
                                    <div class="text-[11px] text-slate-400">{{ $ev->bulan }} {{ $ev->tahun }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-sm font-extrabold {{ $lulusKKM ? 'text-emerald-700' : 'text-rose-600' }}">
                                    {{ $nilaiFormatted }}
                                </span>
                                @if(! $lulusKKM)
                                    <div class="text-[10px] text-rose-500 font-semibold">Di bawah KKM</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($predikatStr)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $predikatClass }}">
                                        {{ $predikatStr }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px]">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">
                                Tidak ada data Pengetahuan Diniyah yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($evaluasis->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $evaluasis->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kkm = 70;
    const isDark = document.documentElement.classList.contains('dark');
    const tickColor = isDark ? '#cbd5e1' : '#475569';
    const gridColor = isDark ? 'rgba(148, 163, 184, 0.14)' : 'rgba(226, 232, 240, 0.6)';

    // 1. Chart Rata-rata per Cabang (sinkron dengan Dashboard Pusat: garis KKM 70 & warna hijau/merah)
    const cabangLabels = @json($cabang_labels);
    const cabangNilai  = @json($cabang_rata_nilai);

    const ctxCabang = document.getElementById('chartNilaiCabangDiniyah');
    if (ctxCabang) {
        const displayLabels = cabangLabels.length ? cabangLabels : ['Belum ada cabang'];
        const displayValues = cabangNilai.length ? cabangNilai : [0];

        new Chart(ctxCabang, {
            type: 'bar',
            data: {
                labels: displayLabels,
                datasets: [
                    {
                        type: 'line',
                        label: 'Garis KKM (' + kkm + ')',
                        data: displayLabels.map(() => kkm),
                        borderColor: '#f97316',
                        borderWidth: 2,
                        borderDash: [6, 4],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        type: 'bar',
                        label: 'Rata-rata Nilai',
                        data: displayValues,
                        backgroundColor: displayValues.map(val => val >= kkm ? 'rgba(16, 185, 129, 0.85)' : 'rgba(239, 68, 68, 0.85)'),
                        borderRadius: 8,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { color: tickColor, font: { size: 11 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.dataset.label}: ${ctx.parsed.y}`
                        }
                    }
                },
                scales: {
                    y: { min: 0, max: 100, ticks: { color: tickColor, font: { size: 11 } }, grid: { color: gridColor } },
                    x: { ticks: { color: tickColor, font: { size: 11 } }, grid: { display: false } }
                }
            }
        });
    }

    // 2. Chart Rata-rata per Materi
    const materiLabels = @json($materi_labels);
    const materiNilai  = @json($materi_rata_nilai);

    const ctxMateri = document.getElementById('chartNilaiMateri');
    if (ctxMateri) {
        new Chart(ctxMateri, {
            type: 'bar',
            data: {
                labels: materiLabels,
                datasets: [{
                    label: 'Rata-rata Nilai Materi',
                    data: materiNilai,
                    backgroundColor: materiNilai.map(val => val >= kkm ? 'rgba(5, 150, 105, 0.85)' : (val > 0 ? 'rgba(239, 68, 68, 0.85)' : 'rgba(148, 163, 184, 0.35)')),
                    borderRadius: 8,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.dataset.label}: ${ctx.parsed.x}`
                        }
                    }
                },
                scales: {
                    x: { min: 0, max: 100, ticks: { color: tickColor, font: { size: 11 } }, grid: { color: gridColor } },
                    y: { ticks: { color: tickColor, font: { size: 11 } }, grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endpush
