{{-- BAGIAN: PENGURUS PUSAT - Dashboard Analitik & Ringkasan Pusat --}}
@extends('pusat.layout.app')

@section('title', 'Dashboard Analitik Pusat')
@section('header_title', 'Dashboard Pusat')

@section('content')
<div class="space-y-6">

    {{-- Banner Hero Pengurus Pusat --}}
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-10 text-white shadow-xl"
         style="background: radial-gradient(ellipse 40% 70% at 80% 50%, rgba(217,119,6,.25) 0%, transparent 65%), linear-gradient(135deg, #064e3b 0%, #056a4c 55%, #044a3f 100%);">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/20 text-xs font-semibold text-emerald-100 mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Monitoring Nasional &bull; {{ $dashboardData['total_cabang_aktif'] }} Cabang Aktif</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-2">
                    Monitoring Operasional Multi-Cabang
                </h1>
                <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed max-w-2xl">
                    Pantau kinerja seluruh cabang Yayasan Rumah Harapan secara agregat, bandingkan aktivitas kegiatan harian, evaluasi capaian kurikulum diniyah, dan lakukan tindak lanjut santri.
                </p>
            </div>

            {{-- Filter KKM --}}
            <form method="GET" action="{{ route('pusat.dashboard') }}" class="flex items-center gap-2 bg-white/10 backdrop-blur-xs p-2.5 rounded-2xl border border-white/20">
                <label for="kkm" class="text-xs font-medium text-emerald-100 whitespace-nowrap">Batas KKM:</label>
                <input type="number" step="0.5" min="0" max="100" name="kkm" id="kkm" value="{{ $kkm }}"
                       class="w-16 px-2.5 py-1.5 bg-white text-slate-800 font-bold text-xs rounded-xl focus:ring-2 focus:ring-orange-400 outline-none text-center">
                <button type="submit" class="px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors">
                    Terapkan
                </button>
            </form>
        </div>
    </div>

    {{-- Kartu Ringkasan Statistik Nasional --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        {{-- Total Cabang --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Cabang</span>
                    <span class="text-2xl font-bold text-slate-900">{{ number_format($dashboardData['total_cabang']) }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                <span>{{ $dashboardData['total_cabang_aktif'] }} status aktif</span>
                <a href="{{ route('pusat.kelola-cabang.index') }}" class="font-semibold text-emerald-700 hover:underline">Kelola &rarr;</a>
            </div>
        </div>

        {{-- Total Kegiatan Harian --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Kegiatan</span>
                    <span class="text-2xl font-bold text-slate-900">{{ number_format($dashboardData['total_kegiatan']) }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                <span>Seluruh cabang terdaftar</span>
                <a href="{{ route('pusat.monitoring-kegiatan.index') }}" class="font-semibold text-emerald-700 hover:underline">Lihat &rarr;</a>
            </div>
        </div>

        {{-- Rata-Rata Nilai Diniyah --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Rata-rata Diniyah</span>
                    <span class="text-2xl font-bold text-slate-900">{{ $dashboardData['rata_rata_diniyah'] }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                <span>Skala 0 - 100</span>
                <a href="{{ route('pusat.monitoring-diniyah.index') }}" class="font-semibold text-emerald-700 hover:underline">Detail &rarr;</a>
            </div>
        </div>

        {{-- Santri di Bawah KKM --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Santri &lt; KKM ({{ $kkm }})</span>
                    <span class="text-2xl font-bold {{ $dashboardData['jumlah_santri_kurang'] > 0 ? 'text-amber-600' : 'text-slate-900' }}">
                        {{ number_format($dashboardData['jumlah_santri_kurang']) }}
                    </span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-2 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                <span>Perlu evaluasi pusat</span>
                <a href="{{ route('pusat.evaluasi-diniyah.index', ['kkm' => $kkm]) }}" class="font-semibold text-amber-700 hover:underline">Evaluasi &rarr;</a>
            </div>
        </div>
    </div>

    {{-- Grafik Perbandingan Antar Cabang --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Grafik Kegiatan per Cabang --}}
        <div class="lg:col-span-2 bg-white rounded-3xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Perbandingan Aktivitas Kegiatan per Cabang</h3>
                    <p class="text-xs text-slate-500">Jumlah kegiatan harian yang tercatat di setiap cabang</p>
                </div>
                <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 self-start sm:self-auto">
                    {{ count($dashboardData['cabang_labels']) }} Cabang
                </span>
            </div>
            <div class="relative h-64 sm:h-72 w-full">
                <canvas id="chartKegiatanCabang"></canvas>
            </div>
        </div>

        {{-- Grafik Distribusi Predikat --}}
        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs flex flex-col">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-slate-900">Distribusi Predikat Nilai</h3>
                <p class="text-xs text-slate-500">Penyebaran capaian evaluasi santri</p>
            </div>
            <div class="relative flex-1 flex items-center justify-center min-h-[200px]">
                <canvas id="chartDistribusiPredikat"></canvas>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-2 gap-2 text-[11px]">
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span> Mumtaz (&ge;90): <strong>{{ $dashboardData['distribusi_predikat']['mumtaz'] }}</strong></div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span> Jayyid Jiddan: <strong>{{ $dashboardData['distribusi_predikat']['jayyid_jiddan'] }}</strong></div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Jayyid: <strong>{{ $dashboardData['distribusi_predikat']['jayyid'] }}</strong></div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Maqbul: <strong>{{ $dashboardData['distribusi_predikat']['maqbul'] }}</strong></div>
                <div class="flex items-center gap-1.5 col-span-2"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Kurang (&lt;60): <strong>{{ $dashboardData['distribusi_predikat']['kurang'] }}</strong></div>
            </div>
        </div>
    </div>

    {{-- Grafik Rata-Rata Nilai per Cabang & Ringkasan Cabang --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Grafik Rata-rata Nilai Diniyah per Cabang --}}
        <div class="lg:col-span-2 bg-white rounded-3xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Rata-Rata Capaian Diniyah per Cabang</h3>
                    <p class="text-xs text-slate-500">Perbandingan mutu pembelajaran antar cabang (garis putus-putus = KKM {{ $kkm }})</p>
                </div>
            </div>
            <div class="relative h-64 sm:h-72 w-full">
                <canvas id="chartNilaiCabang"></canvas>
            </div>
        </div>

        {{-- Ringkasan Cabang & Santri Terbanyak --}}
        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Daftar Cabang Yayasan</h3>
                    <p class="text-xs text-slate-500">Status operasional cabang</p>
                </div>
                <a href="{{ route('pusat.kelola-cabang.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">
                    Semua &rarr;
                </a>
            </div>
            <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-emerald-200">
                @forelse($asramas as $cabang)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ $cabang->nama_asrama }}</p>
                            <p class="text-[11px] text-slate-500">{{ $cabang->wilayah ?? '-' }} &bull; PJ: {{ $cabang->penanggung_jawab ?? '-' }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-xs font-bold text-slate-800">{{ $cabang->anak_asuh_count ?? 0 }}</span>
                            <span class="text-[10px] text-slate-400 block">santri</span>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-500">
                        Belum ada cabang yang terdaftar.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const labels = @json($dashboardData['cabang_labels']);
    const kegiatanCounts = @json($dashboardData['cabang_kegiatan_counts']);
    const rataNilai = @json($dashboardData['cabang_rata_nilai']);
    const kkm = {{ $kkm }};

    // 1. Chart Kegiatan per Cabang
    const ctxKegiatan = document.getElementById('chartKegiatanCabang');
    if (ctxKegiatan) {
        new Chart(ctxKegiatan, {
            type: 'bar',
            data: {
                labels: labels.length ? labels : ['Belum ada cabang'],
                datasets: [{
                    label: 'Jumlah Kegiatan',
                    data: kegiatanCounts.length ? kegiatanCounts : [0],
                    backgroundColor: 'rgba(5, 150, 105, 0.85)',
                    hoverBackgroundColor: 'rgba(4, 120, 87, 1)',
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.parsed.y} kegiatan tercatat`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 11 } },
                        grid: { color: 'rgba(226, 232, 240, 0.6)' }
                    },
                    x: {
                        ticks: { font: { size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. Chart Distribusi Predikat
    const ctxDistribusi = document.getElementById('chartDistribusiPredikat');
    if (ctxDistribusi) {
        const predikat = @json($dashboardData['distribusi_predikat']);
        new Chart(ctxDistribusi, {
            type: 'doughnut',
            data: {
                labels: ['Mumtaz (≥90)', 'Jayyid Jiddan (80-89)', 'Jayyid (70-79)', 'Maqbul (60-69)', 'Kurang (<60)'],
                datasets: [{
                    data: [predikat.mumtaz, predikat.jayyid_jiddan, predikat.jayyid, predikat.maqbul, predikat.kurang],
                    backgroundColor: [
                        '#059669',
                        '#14b8a6',
                        '#3b82f6',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                cutout: '65%'
            }
        });
    }

    // 3. Chart Rata-rata Nilai per Cabang
    const ctxNilai = document.getElementById('chartNilaiCabang');
    if (ctxNilai) {
        new Chart(ctxNilai, {
            type: 'bar',
            data: {
                labels: labels.length ? labels : ['Belum ada cabang'],
                datasets: [
                    {
                        type: 'line',
                        label: 'Garis KKM (' + kkm + ')',
                        data: labels.map(() => kkm),
                        borderColor: '#f97316',
                        borderWidth: 2,
                        borderDash: [6, 4],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        type: 'bar',
                        label: 'Rata-rata Nilai',
                        data: rataNilai.length ? rataNilai : [0],
                        backgroundColor: rataNilai.map(val => val >= kkm ? 'rgba(16, 185, 129, 0.85)' : 'rgba(239, 68, 68, 0.85)'),
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
                        labels: { font: { size: 11 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.dataset.label}: ${ctx.parsed.y}`
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        ticks: { font: { size: 11 } },
                        grid: { color: 'rgba(226, 232, 240, 0.6)' }
                    },
                    x: {
                        ticks: { font: { size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }
});
</script>
@endpush

