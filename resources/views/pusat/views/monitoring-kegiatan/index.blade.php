{{-- BAGIAN: PENGURUS PUSAT - Monitoring Kegiatan Harian Seluruh Cabang --}}
@extends('pusat.layout.app')

@section('title', 'Monitoring Kegiatan Harian')
@section('header_title', 'Monitoring Kegiatan Harian')

@section('content')
<div class="space-y-6">

    {{-- Header Banner --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                Monitoring Kegiatan Harian Multi-Cabang
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pantau seluruh rekam jejak kegiatan santri dan tingkat kehadiran peserta di setiap cabang (Read-Only).
            </p>
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800 self-start sm:self-auto">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Total {{ $kegiatans->total() }} Kegiatan Terdata</span>
        </div>
    </div>

    {{-- Filter Bar Multi-Kriteria --}}
    <div class="bg-white rounded-3xl p-5 border border-emerald-100/90 shadow-xs">
        <form method="GET" action="{{ route('pusat.monitoring-kegiatan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
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

            {{-- Filter Jenis Kegiatan --}}
            <div>
                <label for="kategori" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Jenis Kegiatan</label>
                <select name="kategori" id="kategori" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    <option value="">Semua Jenis</option>
                    @foreach($kategoriOptions as $key => $val)
                        <option value="{{ $key }}" {{ $filters['kategori'] === $key ? 'selected' : '' }}>{{ $val }}</option>
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
                        <a href="{{ route('pusat.monitoring-kegiatan.index') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold shrink-0" title="Reset Filter">
                            &times;
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Grafik Jumlah Kegiatan & Kehadiran per Cabang --}}
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Grafik Jumlah Kegiatan & Total Kehadiran per Cabang</h3>
                <p class="text-xs text-slate-500">Perbandingan intensitas kegiatan dan partisipasi santri antar cabang</p>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-md bg-emerald-600"></span> Jumlah Kegiatan</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-md bg-orange-500"></span> Total Kehadiran Peserta</span>
            </div>
        </div>
        <div class="relative h-72 sm:h-80 w-full">
            <canvas id="chartKegiatanKehadiran"></canvas>
        </div>
    </div>

    {{-- Tabel Kegiatan Terbaru dari Semua Cabang --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Tabel Rekapitulasi Kegiatan Nasional</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar kegiatan yang diinput oleh staff cabang masing-masing</p>
            </div>
            <span class="text-xs text-slate-500 font-medium">Halaman {{ $kegiatans->currentPage() }} dari {{ $kegiatans->lastPage() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">Cabang & Wilayah</th>
                        <th class="py-3.5 px-4">Nama Kegiatan</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Tanggal & Jam</th>
                        <th class="py-3.5 px-4 text-center">Kehadiran</th>
                        <th class="py-3.5 px-4">Penginput Cabang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($kegiatans as $keg)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $keg->asrama?->nama_asrama ?? 'Cabang #' . $keg->asrama_id }}</span>
                                <span class="text-[11px] text-slate-400">{{ $keg->asrama?->wilayah ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    @if($keg->foto_url)
                                        <button type="button" onclick="openLightbox('{{ $keg->foto_url }}', '{{ addslashes($keg->nama_kegiatan) }}', '{{ $keg->tanggal_kegiatan?->format('d/m/Y') }}')" class="shrink-0 group relative block cursor-pointer" title="Lihat Foto Dokumentasi">
                                            <img src="{{ $keg->foto_url }}" alt="Dokumentasi" class="w-8 h-8 object-cover rounded-lg border border-slate-200 group-hover:ring-2 group-hover:ring-emerald-500 transition-all shadow-2xs" onerror="this.style.display='none'">
                                        </button>
                                    @endif
                                    <div>
                                        <span class="font-semibold text-slate-800 block">{{ $keg->nama_kegiatan }}</span>
                                        @if($keg->foto_url)
                                            <span class="text-[10px] text-emerald-600 font-medium">Ada dokumentasi</span>
                                        @elseif($keg->deskripsi)
                                            <span class="text-[11px] text-slate-400 line-clamp-1">{{ Str::limit($keg->deskripsi, 40) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 capitalize">
                                    {{ $keg->kategori_label ?? $keg->kategori }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-slate-800">{{ $keg->tanggal_kegiatan?->format('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400">{{ $keg->jam_mulai ? substr($keg->jam_mulai, 0, 5) : '-' }} WIB</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200">
                                    {{ number_format($keg->jumlah_peserta) }} santri
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-slate-800">{{ $keg->penginput?->name ?? 'Staff Cabang' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                Tidak ada catatan kegiatan harian yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($kegiatans->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $kegiatans->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Modal Lightbox Foto Dokumentasi --}}
<div id="lightbox-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-xs p-4 flex items-center justify-center transition-all opacity-0" onclick="if(event.target === this) closeLightbox()">
    <div class="bg-white rounded-3xl max-w-2xl w-full overflow-hidden shadow-2xl border border-slate-100 transform transition-all scale-95" id="lightbox-card">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
            <div>
                <h3 id="lightbox-title" class="text-sm font-bold text-slate-900">Dokumentasi Kegiatan</h3>
                <p id="lightbox-subtitle" class="text-xs text-slate-500 mt-0.5"></p>
            </div>
            <button type="button" onclick="closeLightbox()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors cursor-pointer" title="Kembali">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali</span>
            </button>
        </div>
        <div class="p-6 bg-slate-900/5 flex items-center justify-center max-h-[70vh] overflow-hidden">
            <img id="lightbox-img" src="" alt="Dokumentasi Full" class="max-h-[65vh] w-auto max-w-full rounded-2xl object-contain shadow-lg">
        </div>
        <div class="px-6 py-3.5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
            <button type="button" onclick="closeLightbox()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-semibold shadow-2xs transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Halaman Monitoring</span>
            </button>
            <a id="lightbox-download" href="" target="_blank" download class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Buka / Unduh Foto</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const labels = @json($chart_labels);
    const dataKegiatan = @json($chart_kegiatan);
    const dataKehadiran = @json($chart_kehadiran);

    const ctx = document.getElementById('chartKegiatanKehadiran');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels.length ? labels : ['Belum ada cabang'],
                datasets: [
                    {
                        label: 'Jumlah Kegiatan',
                        data: dataKegiatan.length ? dataKegiatan : [0],
                        backgroundColor: 'rgba(5, 150, 105, 0.85)',
                        hoverBackgroundColor: 'rgba(4, 120, 87, 1)',
                        borderRadius: 6,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Total Kehadiran Peserta',
                        data: dataKehadiran.length ? dataKehadiran : [0],
                        backgroundColor: 'rgba(249, 115, 22, 0.85)',
                        hoverBackgroundColor: 'rgba(234, 88, 12, 1)',
                        borderRadius: 6,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        title: { display: true, text: 'Jumlah Kegiatan', font: { size: 10 } },
                        ticks: { precision: 0, font: { size: 11 } },
                        grid: { color: 'rgba(226, 232, 240, 0.6)' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        title: { display: true, text: 'Total Kehadiran (Santri)', font: { size: 10 } },
                        ticks: { precision: 0, font: { size: 11 } },
                        grid: { drawOnChartArea: false }
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

function openLightbox(imageUrl, title, subtitle) {
    const modal = document.getElementById('lightbox-modal');
    const card = document.getElementById('lightbox-card');
    const img = document.getElementById('lightbox-img');
    const titleEl = document.getElementById('lightbox-title');
    const subtitleEl = document.getElementById('lightbox-subtitle');
    const downloadEl = document.getElementById('lightbox-download');

    if (!modal || !img) return;

    img.src = imageUrl;
    if (downloadEl) downloadEl.href = imageUrl;
    if (titleEl) titleEl.textContent = title || 'Dokumentasi Kegiatan';
    if (subtitleEl) subtitleEl.textContent = subtitle ? ('Tanggal: ' + subtitle) : '';

    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        if (card) {
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }
    }, 10);
}

function closeLightbox() {
    const modal = document.getElementById('lightbox-modal');
    const card = document.getElementById('lightbox-card');
    if (!modal) return;

    modal.classList.add('opacity-0');
    if (card) {
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
    }
    setTimeout(() => {
        modal.classList.add('hidden');
        const img = document.getElementById('lightbox-img');
        if (img) img.src = '';
    }, 200);
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});
</script>
@endpush

