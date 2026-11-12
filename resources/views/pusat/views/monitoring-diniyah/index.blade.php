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
                Monitoring Capaian Kurikulum Diniyah
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pantau perbandingan rata-rata nilai antar cabang dan penguasaan per materi kurikulum secara nasional (Read-Only).
            </p>
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-teal-50 border border-teal-200 text-xs font-semibold text-teal-800 self-start sm:self-auto">
            <span class="w-2 h-2 rounded-full bg-teal-500"></span>
            <span>Total {{ $evaluasis->total() }} Data Penilaian Terdata</span>
        </div>
    </div>

    {{-- Filter Bar Multi-Kriteria --}}
    <div class="bg-white rounded-3xl p-5 border border-emerald-100/90 shadow-xs">
        <form method="GET" action="{{ route('pusat.monitoring-diniyah.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            {{-- Filter Cabang --}}
            <div>
                <label for="cabang_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Cabang</label>
                <select name="cabang_id" id="cabang_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    <option value="">Semua Cabang</option>
                    @foreach($cabangOptions as $c)
                        <option value="{{ $c->id }}" {{ (string)$filters['cabang_id'] === (string)$c->id ? 'selected' : '' }}>
                            {{ $c->nama_asrama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Daerah --}}
            <div>
                <label for="daerah" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Wilayah / Daerah</label>
                <select name="daerah" id="daerah" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    <option value="">Semua Wilayah</option>
                    @foreach($daerahOptions as $d)
                        <option value="{{ $d }}" {{ $filters['daerah'] === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Materi --}}
            <div>
                <label for="materi" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Materi Kurikulum</label>
                <select name="materi" id="materi" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    <option value="">Semua Materi</option>
                    @foreach($materiOptions as $m)
                        <option value="{{ $m }}" {{ $filters['materi'] === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tanggal Mulai & Selesai --}}
            <div>
                <label for="tanggal_mulai" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Dari Tanggal</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $filters['tanggal_mulai'] }}"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
            </div>

            <div>
                <label for="tanggal_selesai" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Sampai Tanggal</label>
                <div class="flex items-center gap-2">
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $filters['tanggal_selesai'] }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors shrink-0">
                        Filter
                    </button>
                    @if(array_filter($filters))
                        <a href="{{ route('pusat.monitoring-diniyah.index') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold shrink-0" title="Reset Filter">
                            &times;
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
                <h3 class="text-sm font-bold text-slate-900">Rata-Rata Nilai Diniyah per Cabang</h3>
                <p class="text-xs text-slate-500">Standar mutu pembelajaran antar cabang</p>
            </div>
            <div class="relative h-64 sm:h-72 w-full">
                <canvas id="chartNilaiCabangDiniyah"></canvas>
            </div>
        </div>

        {{-- Grafik Rata-Rata Nilai per Materi --}}
        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-slate-900">Rata-Rata Nilai per Bidang Materi</h3>
                <p class="text-xs text-slate-500">Materi yang sudah dikuasai vs materi yang butuh penguatan</p>
            </div>
            <div class="relative h-64 sm:h-72 w-full">
                <canvas id="chartNilaiMateri"></canvas>
            </div>
        </div>
    </div>

    {{-- Tabel Nilai Terbaru dari Semua Cabang --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Tabel Evaluasi & Hafalan Santri Seluruh Cabang</h3>
                <p class="text-xs text-slate-500 mt-0.5">Semua data penilaian terkini dari seluruh cabang Yayasan</p>
            </div>
            <span class="text-xs text-slate-500 font-medium">Halaman {{ $evaluasis->currentPage() }} dari {{ $evaluasis->lastPage() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">Cabang</th>
                        <th class="py-3.5 px-4">Nama Santri</th>
                        <th class="py-3.5 px-4">Jenis & Materi</th>
                        <th class="py-3.5 px-4">Tanggal Penilaian</th>
                        <th class="py-3.5 px-4 text-center">Nilai</th>
                        <th class="py-3.5 px-4">Predikat</th>
                        <th class="py-3.5 px-4">Penilai Cabang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($evaluasis as $ev)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $ev->asrama?->nama_asrama ?? 'Cabang #' . $ev->asrama_id }}</span>
                                <span class="text-[11px] text-slate-400">{{ $ev->asrama?->wilayah ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $ev->anakAsuh?->nama_lengkap ?? 'Santri #' . $ev->anak_asuh_id }}</div>
                                <div class="text-[11px] text-slate-400">NIS: {{ $ev->anakAsuh?->nis ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 mb-1">
                                    {{ $ev->jenis_evaluasi }}
                                </span>
                                <div class="font-medium text-slate-700 truncate max-w-xs">{{ $ev->materi }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-slate-800">{{ $ev->tanggal_evaluasi?->format('d M Y') }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-sm font-extrabold {{ $ev->nilai >= 70 ? 'text-emerald-700' : 'text-rose-600' }}">
                                    {{ $ev->nilai }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @php
                                    $predikatClass = match($ev->predikat) {
                                        'Mumtaz' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'Jayyid Jiddan' => 'bg-teal-50 text-teal-700 border-teal-200',
                                        'Jayyid' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'Maqbul' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        default => 'bg-rose-50 text-rose-700 border-rose-200',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $predikatClass }}">
                                    {{ $ev->predikat }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-slate-800">{{ $ev->penilai?->name ?? 'Staff Cabang' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">
                                Tidak ada data evaluasi diniyah yang sesuai dengan kriteria filter.
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
    // 1. Chart Rata-rata per Cabang
    const cabangLabels = @json($cabang_labels);
    const cabangNilai = @json($cabang_rata_nilai);

    const ctxCabang = document.getElementById('chartNilaiCabangDiniyah');
    if (ctxCabang) {
        new Chart(ctxCabang, {
            type: 'bar',
            data: {
                labels: cabangLabels.length ? cabangLabels : ['Belum ada cabang'],
                datasets: [{
                    label: 'Rata-rata Nilai Diniyah',
                    data: cabangNilai.length ? cabangNilai : [0],
                    backgroundColor: 'rgba(20, 184, 166, 0.85)',
                    hoverBackgroundColor: 'rgba(13, 148, 136, 1)',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        ticks: { font: { size: 11 } },
                        grid: { color: 'rgba(226, 232, 240, 0.6)' }
                    },
                    x: { ticks: { font: { size: 11 } }, grid: { display: false } }
                }
            }
        });
    }

    // 2. Chart Rata-rata per Materi
    const materiLabels = @json($materi_labels);
    const materiNilai = @json($materi_rata_nilai);

    const ctxMateri = document.getElementById('chartNilaiMateri');
    if (ctxMateri) {
        new Chart(ctxMateri, {
            type: 'bar',
            data: {
                labels: materiLabels,
                datasets: [{
                    label: 'Rata-rata Nilai Materi',
                    data: materiNilai,
                    backgroundColor: 'rgba(5, 150, 105, 0.85)',
                    hoverBackgroundColor: 'rgba(4, 120, 87, 1)',
                    borderRadius: 8,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        min: 0,
                        max: 100,
                        ticks: { font: { size: 11 } },
                        grid: { color: 'rgba(226, 232, 240, 0.6)' }
                    },
                    y: { ticks: { font: { size: 11 } }, grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endpush

