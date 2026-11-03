@extends('layouts.app')

@section('title', 'Dashboard Admin Pusat')

@section('header_title', 'Dashboard Admin Pusat')

@section('content')
{{-- ================================================================== --}}
{{-- DASHBOARD ADMIN PUSAT - SIMPENA (Sistem Informasi Manajemen          --}}
{{-- Pendidikan dan Pembinaan Anak Asuh) Yayasan Rumah Harapan           --}}
{{-- ================================================================== --}}

@php
    $user = auth()->user();
    $userName = $user?->name ?? 'Admin Pusat';
@endphp

<div class="space-y-6">

{{-- ========== STYLES KHUSUS DASHBOARD ========== --}}
<style>
/* ─── Banner Hero ─────────────────────────────────────────────── */
.db-banner {
    position: relative; overflow: hidden; border-radius: 24px;
    color: #fff; padding: 36px 40px;
    background:
        radial-gradient(ellipse 30% 60% at 80% 50%, rgba(217,119,6,.28) 0%, transparent 65%),
        radial-gradient(ellipse 55% 75% at 10% 90%, #0a8a63 0%, transparent 65%),
        linear-gradient(135deg, #064e3b 0%, #056a4c 55%, #044a3f 100%);
    box-shadow: 0 20px 40px -8px rgba(6,78,59,.30);
}
.db-banner::before {
    content:''; position:absolute; inset:0; z-index:0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.db-banner .inner { position:relative; z-index:1; }
.db-badge {
    display:inline-flex; align-items:center; gap:8px; padding:6px 16px;
    border-radius:9999px; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.2);
    font-size:12px; font-weight:600; color:#d1fae5;
}
.db-badge span.dot { width:8px; height:8px; border-radius:50%; background:#f97316; display:inline-block; }
.db-banner h1 { font-size:clamp(22px,3.2vw,36px); font-weight:800; line-height:1.15; letter-spacing:-.02em; margin:16px 0 10px; color:#fff; }
.db-banner .sub { font-size:clamp(12px,1.4vw,15px); color:rgba(167,243,208,.8); line-height:1.6; }
.db-date-card {
    padding:20px 24px; border-radius:20px; background:rgba(255,255,255,.13);
    border:1px solid rgba(255,255,255,.22); backdrop-filter:blur(12px);
}
.db-date-card .day { font-size:28px; font-weight:800; color:#fff; line-height:1.2; }
.db-date-card .online { margin-top:14px; padding-top:14px; border-top:1px solid rgba(255,255,255,.2); display:flex; align-items:center; gap:8px; font-size:13px; color:#a7f3d0; }
.db-date-card .online-dot { width:10px; height:10px; border-radius:50%; background:#22e07a; box-shadow:0 0 8px #22e07a; }
@media(min-width:768px){ .db-date-card { width:260px; flex-shrink:0; } .db-banner{padding:48px 56px;} }

/* ─── Stat Cards ──────────────────────────────────────────────── */
.stat-card { background:#fff; border-radius:20px; padding:20px 22px; border:1px solid rgba(16,185,129,.1); box-shadow:0 1px 3px rgba(0,0,0,.05); transition:box-shadow .2s, transform .2s; position:relative; overflow:hidden; }
.stat-card:hover { box-shadow:0 8px 25px -5px rgba(16,185,129,.15); transform:translateY(-2px); }
.stat-icon { width:48px; height:48px; border-radius:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:transform .2s; }
.stat-card:hover .stat-icon { transform:scale(1.1); }
.stat-num { font-size:2rem; font-weight:800; letter-spacing:-.03em; color:#0f172a; line-height:1; }
.stat-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:6px; }

/* ─── Filter Bar ──────────────────────────────────────────────── */
.filter-bar { background:#fff; border:1px solid #ecfdf5; border-radius:18px; padding:18px 22px; box-shadow:0 1px 3px rgba(0,0,0,.05); }

/* ─── Section Headers ─────────────────────────────────────────── */
.section-hd { display:flex; align-items:center; gap:10px; margin-bottom:16px; }
.section-hd .hd-icon { width:36px; height:36px; border-radius:10px; background:#065f46; color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.section-hd h2 { font-size:15px; font-weight:700; color:#0f172a; margin:0; }
.section-hd p { font-size:11px; color:#94a3b8; margin:2px 0 0; }

/* ─── Chart Cards ─────────────────────────────────────────────── */
.chart-card { background:#fff; border-radius:20px; border:1px solid rgba(16,185,129,.1); box-shadow:0 1px 4px rgba(0,0,0,.06); padding:22px; }
.empty-state { text-align:center; padding:36px 0; }
.empty-state .es-icon { width:56px; height:56px; background:#f1f5f9; border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 12px; color:#94a3b8; }
.empty-state p { font-size:13px; font-weight:600; color:#64748b; margin:0 0 4px; }
.empty-state small { font-size:11px; color:#94a3b8; }

/* ─── Table ───────────────────────────────────────────────────── */
.mon-table { width:100%; border-collapse:collapse; font-size:13px; }
.mon-table th { padding:10px 14px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#64748b; background:#f8fafc; border-bottom:1px solid #e2e8f0; text-align:left; }
.mon-table td { padding:12px 14px; border-bottom:1px solid #f1f5f9; color:#334155; vertical-align:middle; }
.mon-table tr:last-child td { border-bottom:none; }
.mon-table tr:hover td { background:#f8fffe; }
.badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:9999px; font-size:11px; font-weight:600; }
.badge-emerald { background:#d1fae5; color:#065f46; }
.badge-amber   { background:#fef3c7; color:#92400e; }
.badge-slate   { background:#f1f5f9; color:#475569; }
</style>

{{-- ========== 1. BANNER HERO ========== --}}
<div class="db-banner">
    <div class="inner flex flex-col md:flex-row md:items-center md:justify-between gap-5">
        <div class="flex-1">
            <div class="db-badge">
                <span class="dot"></span>
                <span>Dashboard Admin Pusat &bull; {{ $filterAktif['bulan_nama'] }} {{ $filterAktif['tahun'] }}</span>
            </div>
            <h1>Assalamu'alaikum, {{ $userName }}</h1>
            <p class="sub">Monitoring Perkembangan Pendidikan dan Pembinaan Anak Asuh<br>Yayasan Rumah Harapan — Seluruh Asrama Aktif</p>
        </div>
        <div class="db-date-card">
            <div class="day">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd') }}</div>
            <div class="day">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</div>
            <div class="online">
                <span class="online-dot"></span>
                <span>Sistem Aktif &amp; Terhubung</span>
            </div>
        </div>
    </div>
</div>

{{-- ========== 2. FILTER DASHBOARD ========== --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('dashboard') }}" id="form-filter">
        <div class="flex flex-wrap items-end gap-4">
            {{-- Periode Bulan --}}
            <div class="flex-1 min-w-[130px]">
                <label class="block text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5">Bulan</label>
                <select name="bulan" id="filter-bulan"
                    class="w-full text-sm border border-emerald-200 rounded-xl px-3 py-2.5 text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                    @foreach(['Januari'=>1,'Februari'=>2,'Maret'=>3,'April'=>4,'Mei'=>5,'Juni'=>6,'Juli'=>7,'Agustus'=>8,'September'=>9,'Oktober'=>10,'November'=>11,'Desember'=>12] as $nama => $num)
                        <option value="{{ $num }}" {{ $filterAktif['bulan_num'] == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Tahun --}}
            <div class="min-w-[100px]">
                <label class="block text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5">Tahun</label>
                <select name="tahun" id="filter-tahun"
                    class="w-full text-sm border border-emerald-200 rounded-xl px-3 py-2.5 text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                    @foreach($tahunOptions as $yr)
                        <option value="{{ $yr }}" {{ $filterAktif['tahun'] == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Jenjang --}}
            <div class="min-w-[130px]">
                <label class="block text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5">Jenjang</label>
                <select name="jenjang" id="filter-jenjang"
                    class="w-full text-sm border border-emerald-200 rounded-xl px-3 py-2.5 text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                    <option value="">Semua Jenjang</option>
                    <option value="SD"  {{ $filterAktif['jenjang'] === 'SD'  ? 'selected' : '' }}>SD / MI</option>
                    <option value="SMP" {{ $filterAktif['jenjang'] === 'SMP' ? 'selected' : '' }}>SMP / MTs</option>
                    <option value="SMA" {{ $filterAktif['jenjang'] === 'SMA' ? 'selected' : '' }}>SMA / MA</option>
                </select>
            </div>
            {{-- Cakupan Asrama --}}
            <div class="flex-1 min-w-[170px]">
                <label class="block text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5">Cakupan Asrama</label>
                <select name="asrama_id" id="filter-asrama"
                    class="w-full text-sm border border-emerald-200 rounded-xl px-3 py-2.5 text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                    <option value="">Seluruh Asrama Aktif</option>
                    @foreach($asramaListFilter as $as)
                        <option value="{{ $as->id }}" {{ $filterAktif['asrama_id'] == $as->id ? 'selected' : '' }}>
                            {{ $as->nama_asrama }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Tombol --}}
            <div class="flex gap-2">
                <button type="submit" id="btn-terapkan"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 active:scale-95 transition-all shadow-sm shadow-emerald-600/30">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Terapkan Filter
                </button>
                <a href="{{ route('dashboard') }}" id="btn-reset"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-500 text-sm font-medium hover:bg-slate-50 active:scale-95 transition-all">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- ========== 3. KARTU STATISTIK UTAMA (4 KARTU) ========== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

    {{-- Kartu 1: Total Anak Asuh Aktif --}}
    <div class="stat-card">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="stat-label">Total Anak Asuh</div>
                <div class="flex items-baseline gap-1.5">
                    <span class="stat-num">{{ $totalAnakAktif }}</span>
                    <span class="text-xs font-semibold text-slate-400">Anak</span>
                </div>
                {{-- Breakdown jenjang --}}
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center gap-3 text-[11px]">
                    @if($sdCount + $smpCount + $smaCount > 0)
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-slate-600 font-semibold">SD: {{ $sdCount }}</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                            <span class="text-slate-600 font-semibold">SMP: {{ $smpCount }}</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                            <span class="text-slate-600 font-semibold">SMA: {{ $smaCount }}</span>
                        </span>
                    @else
                        <span class="text-slate-400">Belum ada data anak asuh</span>
                    @endif
                </div>
            </div>
            <div class="stat-icon bg-emerald-50 text-emerald-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Kartu 2: Asrama Aktif --}}
    <div class="stat-card">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="stat-label">Total Asrama Aktif</div>
                <div class="flex items-baseline gap-1.5">
                    <span class="stat-num">{{ $totalAsramaAktif }}</span>
                    <span class="text-xs font-semibold text-slate-400">Asrama</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 text-[11px]">
                    @if($totalAsramaAktif > 0)
                        <span class="text-teal-700 font-semibold">Berstatus aktif dan beroperasi</span>
                    @else
                        <span class="text-slate-400">Belum ada asrama yang terdaftar</span>
                    @endif
                </div>
            </div>
            <div class="stat-icon bg-teal-50 text-teal-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Kartu 3: Evaluasi Selesai (%) --}}
    <div class="stat-card">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="stat-label">Evaluasi Selesai</div>
                <div class="flex items-baseline gap-1.5">
                    @if($totalEvaluasiPeriode > 0)
                        <span class="stat-num">{{ $persenEvaluasiSelesai }}<span class="text-xl">%</span></span>
                    @else
                        <span class="text-xl font-bold text-slate-400">0%</span>
                    @endif
                </div>
                {{-- Progress bar visual --}}
                <div class="mt-2 w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-700" style="width: {{ $persenEvaluasiSelesai }}%"></div>
                </div>
                <div class="mt-2 text-[11px] text-slate-500">{{ $evalStatusTeks }}</div>
            </div>
            <div class="stat-icon bg-emerald-50 text-emerald-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Kartu 4: Evaluasi Pending --}}
    <div class="stat-card">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="stat-label">Evaluasi Pending</div>
                <div class="flex items-baseline gap-1.5">
                    <span class="stat-num {{ $evalPendingTotal > 0 ? 'text-amber-600' : '' }}">{{ $evalPendingTotal }}</span>
                    <span class="text-xs font-semibold text-slate-400">Pengajuan</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 text-[11px]">
                    @if($evalPendingTotal > 0)
                        <span class="text-amber-600 font-semibold">Menunggu penilaian Admin Pusat</span>
                    @else
                        <span class="text-slate-400">Tidak ada evaluasi yang menunggu</span>
                    @endif
                </div>
            </div>
            <div class="stat-icon {{ $evalPendingTotal > 0 ? 'bg-amber-50 text-amber-500' : 'bg-slate-50 text-slate-400' }}">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

</div>

{{-- ========== 4. GRAFIK BARIS 1: LINE CHART + DOUGHNUT CHART ========== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Grafik 1: Perkembangan Evaluasi Diniyah (Line Chart, 2/3 lebar) --}}
    <div class="lg:col-span-2 chart-card">
        <div class="section-hd">
            <div class="hd-icon">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
            <div>
                <h2>Perkembangan Evaluasi Diniyah</h2>
                <p>Persentase evaluasi selesai — 6 bulan terakhir</p>
            </div>
        </div>
        @if($grafikPerkembanganAdaData)
            <div style="height:240px; position:relative;">
                <canvas id="chartPerkembangan"></canvas>
            </div>
        @else
            <div class="empty-state">
                <div class="es-icon">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18"/>
                    </svg>
                </div>
                <p>Belum ada data evaluasi diniyah</p>
                <small>Grafik akan muncul setelah evaluasi pertama dicatat</small>
            </div>
        @endif
    </div>

    {{-- Grafik 2: Distribusi Predikat (Doughnut, 1/3 lebar) --}}
    <div class="chart-card">
        <div class="section-hd">
            <div class="hd-icon">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
            </div>
            <div>
                <h2>Distribusi Predikat</h2>
                <p>Berdasarkan rata-rata nilai akhir</p>
            </div>
        </div>
        @if($grafikPredikatAdaData)
            <div style="height:180px; position:relative;" class="mt-2">
                <canvas id="chartPredikat"></canvas>
            </div>
            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between text-[12px]">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-sm" style="background:#10b981;"></span>A — Baik Sekali (&ge;85)</span>
                    <span class="font-bold text-slate-700">{{ $predikatA }}</span>
                </div>
                <div class="flex items-center justify-between text-[12px]">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-sm" style="background:#3b82f6;"></span>B — Baik (70–84)</span>
                    <span class="font-bold text-slate-700">{{ $predikatB }}</span>
                </div>
                <div class="flex items-center justify-between text-[12px]">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-sm" style="background:#f59e0b;"></span>C — Perlu Pembinaan (&lt;70)</span>
                    <span class="font-bold text-slate-700">{{ $predikatC }}</span>
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="es-icon">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    </svg>
                </div>
                <p>Belum ada hasil penilaian</p>
                <small>Diagram akan muncul setelah evaluasi disetujui</small>
            </div>
        @endif
    </div>

</div>

{{-- ========== 5. GRAFIK BARIS 2: KEGIATAN HARIAN + KASUS & PEMBINAAN + KESEHATAN ========== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Grafik 3: Kegiatan Harian per Kategori (Bar) --}}
    <div class="chart-card">
        <div class="section-hd">
            <div class="hd-icon">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2>Aktivitas Kegiatan Harian</h2>
                <p>Jumlah kegiatan per kategori bulan ini</p>
            </div>
        </div>
        @if($grafikKegiatanAdaData)
            <div style="height:220px; position:relative;">
                <canvas id="chartKegiatan"></canvas>
            </div>
        @else
            <div class="empty-state">
                <div class="es-icon">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10"/>
                    </svg>
                </div>
                <p>Belum ada kegiatan yang dilaporkan</p>
                <small>Total kegiatan pada {{ $filterAktif['bulan_nama'] }} {{ $filterAktif['tahun'] }}: 0</small>
            </div>
        @endif
    </div>

    {{-- Grafik 4: Kasus & Pembinaan (Bar 2 Indikator) --}}
    <div class="chart-card">
        <div class="section-hd">
            <div class="hd-icon" style="background:#78350f;">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h2>Kasus &amp; Pembinaan</h2>
                <p>Jumlah anak asuh unik pada periode ini</p>
            </div>
        </div>
        @if($kasusModulAda)
            {{-- Akan aktif jika modul kasus sudah dibuat --}}
            <canvas id="chartKasus" style="height:220px;"></canvas>
        @else
            <div class="empty-state">
                <div class="es-icon" style="background:#fef3c7; color:#d97706;">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <p>Belum ada data kasus dan pembinaan</p>
                <small class="block text-[11px] text-slate-400 max-w-[200px] mx-auto mt-1">Modul kasus &amp; pembinaan belum tersedia. Anak melakukan kasus: <strong>{{ $kasusAnakTotal }}</strong> &bull; Sudah dibina: <strong>{{ $pembinaanTotal }}</strong></small>
            </div>
        @endif
    </div>

    {{-- Grafik 5: Kesehatan Anak (Empty — modul belum tersedia) --}}
    <div class="chart-card">
        <div class="section-hd">
            <div class="hd-icon" style="background:#be123c;">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div>
                <h2>Monitoring Kesehatan</h2>
                <p>Laporan kondisi kesehatan anak asuh</p>
            </div>
        </div>
        <div class="empty-state">
            <div class="es-icon" style="background:#fff1f2; color:#be123c;">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <p>Belum ada laporan kesehatan</p>
            <small>Modul rekam kesehatan anak asuh Belum tersedia. Total laporan: 0</small>
        </div>
    </div>

</div>

{{-- ========== 6. TABEL MONITORING PER ASRAMA ========== --}}
<div class="chart-card">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div class="section-hd mb-0">
            <div class="hd-icon">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2>Monitoring Perkembangan per Asrama</h2>
                <p>{{ $filterAktif['bulan_nama'] }} {{ $filterAktif['tahun'] }}{{ $filterAktif['jenjang'] ? ' · Jenjang ' . $filterAktif['jenjang'] : '' }}</p>
            </div>
        </div>
        @can('admin_pusat')
        <a href="{{ route('asrama.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-emerald-200 text-emerald-700 text-sm font-semibold hover:bg-emerald-50 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Kelola Asrama
        </a>
        @endcan
    </div>

    @if($asramaMonitoringList->count() > 0)
        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="mon-table">
                <thead>
                    <tr>
                        <th style="width:50px">No</th>
                        <th>Nama Asrama</th>
                        <th class="text-center">Anak Asuh</th>
                        <th class="text-center">Evaluasi Selesai</th>
                        <th class="text-center">Pending</th>
                        <th class="text-center">Status Laporan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asramaMonitoringList as $i => $asrama)
                    <tr>
                        <td class="text-slate-400 font-medium">{{ $i + 1 }}</td>
                        <td>
                            <div class="font-semibold text-slate-800">{{ $asrama->nama_asrama }}</div>
                            @if($asrama->wilayah)
                                <div class="text-[11px] text-slate-400 mt-0.5">{{ $asrama->wilayah }}</div>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="font-bold text-slate-800">{{ $asrama->anak_aktif_count }}</span>
                            <span class="text-[11px] text-slate-400 ml-1">anak</span>
                        </td>
                        <td class="text-center">
                            @if($asrama->total_eval > 0)
                                <div class="font-bold text-slate-800">{{ $asrama->persen_selesai }}%</div>
                                <div class="text-[11px] text-slate-400">{{ $asrama->selesai_eval }}/{{ $asrama->total_eval }}</div>
                            @else
                                <span class="text-[11px] text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($asrama->pending_eval > 0)
                                <span class="badge badge-amber">{{ $asrama->pending_eval }}</span>
                            @else
                                <span class="text-[12px] text-slate-400">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php $warna = $asrama->status_warna; @endphp
                            <span class="badge badge-{{ $warna }}">
                                <span class="w-1.5 h-1.5 rounded-full
                                    {{ $warna === 'emerald' ? 'bg-emerald-500' : ($warna === 'amber' ? 'bg-amber-500' : 'bg-slate-400') }}">
                                </span>
                                {{ $asrama->status_laporan }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('asrama.index') }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-colors">
                                Detail
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state py-12">
            <div class="es-icon mx-auto">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <p>Belum ada data asrama.</p>
            <small>Silakan tambahkan data asrama terlebih dahulu.</small>
            @can('admin_pusat')
            <div class="mt-4">
                <a href="{{ route('asrama.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition-all shadow-sm shadow-emerald-600/25">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Data Asrama
                </a>
            </div>
            @endcan
        </div>
    @endif
</div>

</div>{{-- end .space-y-6 --}}

{{-- ========== SCRIPTS CHART.JS ========== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
    Chart.defaults.font.family = "'Plus Jakarta Sans', system-ui, sans-serif";
    Chart.defaults.color = '#64748b';

    // ─── Data dari PHP ──────────────────────────────────────────────────
    const perkembLabels = @json($grafikPerkembanganLabels);
    const perkembData   = @json($grafikPerkembanganData);
    const kegLabels     = @json($kegiatanLabels);
    const kegData       = @json($kegiatanData);
    const predA = {{ $predikatA }};
    const predB = {{ $predikatB }};
    const predC = {{ $predikatC }};

    // ─── Grafik 1: Line Chart Perkembangan Evaluasi ─────────────────────
    const ctxLine = document.getElementById('chartPerkembangan');
    if (ctxLine) {
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: perkembLabels,
                datasets: [{
                    label: '% Evaluasi Selesai',
                    data: perkembData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: (ctx) => ` ${ctx.parsed.y}% selesai` }
                    }
                },
                scales: {
                    y: {
                        min: 0, max: 100,
                        ticks: { callback: v => v + '%', font: { size: 11 } },
                        grid: { color: '#f1f5f9' },
                        border: { dash: [4, 4] }
                    },
                    x: { ticks: { font: { size: 11 } }, grid: { display: false } }
                }
            }
        });
    }

    // ─── Grafik 2: Doughnut Distribusi Predikat ─────────────────────────
    const ctxDnt = document.getElementById('chartPredikat');
    if (ctxDnt && (predA + predB + predC) > 0) {
        new Chart(ctxDnt, {
            type: 'doughnut',
            data: {
                labels: ['A — Baik Sekali', 'B — Baik', 'C — Perlu Pembinaan'],
                datasets: [{
                    data: [predA, predB, predC],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b'],
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (ctx) => ` ${ctx.label}: ${ctx.parsed} anak` } }
                }
            }
        });
    }

    // ─── Grafik 3: Bar Chart Kegiatan Harian ───────────────────────────
    const ctxBar = document.getElementById('chartKegiatan');
    if (ctxBar && kegData.some(v => v > 0)) {
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: kegLabels,
                datasets: [{
                    label: 'Jumlah Kegiatan',
                    data: kegData,
                    backgroundColor: [
                        'rgba(16,185,129,.75)',
                        'rgba(59,130,246,.75)',
                        'rgba(245,158,11,.75)',
                        'rgba(168,85,247,.75)',
                        'rgba(239,68,68,.75)',
                    ],
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (ctx) => ` ${ctx.parsed.y} kegiatan` } }
                },
                scales: {
                    y: {
                        ticks: { stepSize: 1, font: { size: 11 } },
                        grid: { color: '#f1f5f9' },
                        border: { dash: [4, 4] }
                    },
                    x: { ticks: { font: { size: 10 } }, grid: { display: false } }
                }
            }
        });
    }
})();
</script>

@endsection
