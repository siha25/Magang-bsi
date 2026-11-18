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
        <div class="flex flex-wrap items-center gap-2.5 self-start sm:self-auto">
            @if(auth()->user()?->isAdminPusat())
                <button type="button" onclick="openKategoriKegiatanModal()" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>+ Kelola Kategori Kegiatan</span>
                </button>
            @endif
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Total {{ $kegiatans->total() }} Kegiatan Terdata</span>
            </div>
        </div>
    </div>

    {{-- Filter Bar Multi-Kriteria --}}
    <div class="bg-white rounded-3xl p-5 border border-emerald-100/90 shadow-xs">
        <form method="GET" action="{{ route('pusat.monitoring-kegiatan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-12 gap-3.5 items-end">
            {{-- Filter Cabang --}}
            <div class="xl:col-span-2">
                <label for="cabang_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Cabang</label>
                <select name="cabang_id" id="cabang_id" class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                    <option value="">Semua Cabang</option>
                    @foreach($cabangOptions as $c)
                        <option value="{{ $c->id }}" {{ (string)$filters['cabang_id'] === (string)$c->id ? 'selected' : '' }}>
                            {{ $c->nama_asrama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Daerah --}}
            <div class="xl:col-span-2">
                <label for="daerah" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Wilayah / Daerah</label>
                <select name="daerah" id="daerah" class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                    <option value="">Semua Wilayah</option>
                    @foreach($daerahOptions as $d)
                        <option value="{{ $d }}" {{ $filters['daerah'] === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Kategori Kegiatan --}}
            <div class="xl:col-span-2">
                <label for="filter_kategori_kegiatan" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Kategori Kegiatan</label>
                <select name="kategori" id="filter_kategori_kegiatan" class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->kode }}" {{ (string)($filters['kategori'] ?? '') === $kat->kode ? 'selected' : '' }}>
                            {{ $kat->nama }} {{ !$kat->is_active ? '(Nonaktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tanggal Mulai --}}
            <div class="xl:col-span-2">
                <label for="tanggal_mulai" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Dari Tanggal</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $filters['tanggal_mulai'] }}"
                       class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
            </div>

            {{-- Filter Tanggal Selesai --}}
            <div class="xl:col-span-2">
                <label for="tanggal_selesai" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $filters['tanggal_selesai'] }}"
                       class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-colors">
            </div>

            {{-- Tombol Aksi --}}
            <div class="xl:col-span-2 flex items-center gap-2">
                <button type="submit" class="h-10 flex-1 inline-flex items-center justify-center gap-1.5 px-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors cursor-pointer whitespace-nowrap">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Terapkan Filter</span>
                </button>
                @if(array_filter($filters))
                    <a href="{{ route('pusat.monitoring-kegiatan.index') }}" class="h-10 inline-flex items-center justify-center px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors shrink-0" title="Reset Filter">
                        &times;
                    </a>
                @endif
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
                <h3 class="text-sm font-bold text-slate-900">Tabel Rekapitulasi Kegiatan</h3>
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
                        <th class="py-3.5 px-4 text-center">Dokumentasi</th>
                        <th class="py-3.5 px-4">Tanggal & Jam</th>
                        <th class="py-3.5 px-4 text-center">Kehadiran</th>
                        <th class="py-3.5 px-4">Penginput Cabang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($kegiatans as $keg)
                        @php
                            $daftarFoto = $keg->semua_foto;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $keg->asrama?->nama_asrama ?? 'Cabang #' . $keg->asrama_id }}</span>
                                <span class="text-[11px] text-slate-400">{{ $keg->asrama?->wilayah ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-800 block">{{ $keg->nama_kegiatan }}</span>
                                @if($keg->deskripsi)
                                    <span class="text-[11px] text-slate-400 line-clamp-1">{{ Str::limit($keg->deskripsi, 40) }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold border bg-emerald-50 text-emerald-700 border-emerald-200 capitalize">
                                    {{ $keg->kategori_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if(count($daftarFoto) > 0)
                                    <button type="button"
                                            onclick="openKegiatanLightbox(this)"
                                            data-fotos="{{ json_encode($daftarFoto) }}"
                                            data-title="{{ $keg->nama_kegiatan }}"
                                            data-subtitle="{{ $keg->tanggal_kegiatan?->format('d/m/Y') ?? '' }}"
                                            class="inline-flex items-center gap-2 px-2.5 py-1 rounded-2xl bg-emerald-50 hover:bg-emerald-100/90 border border-emerald-300 text-emerald-800 text-[11px] font-semibold transition-all shadow-2xs hover:shadow-xs cursor-pointer group"
                                            title="Lihat Foto Dokumentasi">
                                        <img src="{{ $daftarFoto[0]['url'] }}" alt="Dokumentasi" class="w-6 h-6 rounded-md object-cover border border-emerald-300 shrink-0 group-hover:scale-105 transition-transform" onerror="this.style.display='none'">
                                        <span>{{ count($daftarFoto) > 1 ? 'Lihat (' . count($daftarFoto) . ' Foto)' : 'Lihat Foto' }}</span>
                                    </button>
                                @else
                                    <span class="text-xs text-slate-300 font-medium">-</span>
                                @endif
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
                            <td colspan="7" class="py-12 text-center text-slate-500">
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
            <div class="flex items-center gap-2">
                <span id="lightbox-counter" class="hidden text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-200/70 text-slate-700"></span>
                <button type="button" onclick="closeLightbox()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors cursor-pointer" title="Kembali">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </button>
            </div>
        </div>
        <div class="relative p-6 bg-slate-900/5 flex items-center justify-center min-h-[280px] max-h-[70vh] overflow-hidden">
            <button type="button" id="lightbox-prev-btn" onclick="prevLightboxImage()" class="hidden absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-slate-700 shadow-md flex items-center justify-center transition-all cursor-pointer z-10 disabled:opacity-30 disabled:cursor-not-allowed" title="Foto Sebelumnya">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <img id="lightbox-img" src="" alt="Dokumentasi Full" class="max-h-[65vh] w-auto max-w-full rounded-2xl object-contain shadow-lg">
            <button type="button" id="lightbox-next-btn" onclick="nextLightboxImage()" class="hidden absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-slate-700 shadow-md flex items-center justify-center transition-all cursor-pointer z-10 disabled:opacity-30 disabled:cursor-not-allowed" title="Foto Berikutnya">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
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

{{-- MODAL & DRAWER KELOLA KATEGORI KEGIATAN (ADMIN PUSAT) --}}
@if(auth()->user()?->isAdminPusat())
<div id="modal-kategori-kegiatan" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
    <div id="modal-kategori-kegiatan-card" class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl border border-emerald-100 flex flex-col max-h-[92vh] overflow-hidden transform scale-95 transition-all duration-200">
        {{-- Header Modal --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-emerald-50/70 via-white to-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Kelola Master Kategori Kegiatan</h3>
                    <p class="text-xs text-slate-500">Standarisasi kategori kegiatan harian yang menjadi pilihan bagi seluruh cabang</p>
                </div>
            </div>
            <button type="button" onclick="closeKategoriKegiatanModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center transition-colors cursor-pointer" title="Tutup Modal">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        {{-- Toast Notifikasi AJAX --}}
        <div id="kat-toast" class="hidden mx-6 mt-4 p-3 rounded-2xl text-xs font-semibold flex items-center justify-between transition-all">
            <span id="kat-toast-msg"></span>
            <button type="button" onclick="hideKatToast()" class="text-slate-400 hover:text-slate-600 ml-2 cursor-pointer">&times;</button>
        </div>

        {{-- Body Modal: 2 Kolom (Form Tambah/Edit & Daftar Kategori Kegiatan) --}}
        <div class="p-6 overflow-y-auto grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Kolom Kiri: Form Tambah/Edit (5 Kolom di Layar Lebar) --}}
            <div class="lg:col-span-5 bg-slate-50/80 rounded-2xl p-4 sm:p-5 border border-slate-200/80 space-y-4 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-200/60">
                        <h4 id="kat-form-title" class="text-xs font-bold uppercase tracking-wider text-emerald-900 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Tambah Kategori Baru</span>
                        </h4>
                        <span id="kat-edit-indicator" class="hidden px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Mode Edit</span>
                    </div>

                    {{-- Form Inline Error Box --}}
                    <div id="kat-form-error" class="hidden mb-3 p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 space-y-1"></div>

                    <form id="kat-form" onsubmit="submitKategoriKegiatan(event)" class="space-y-3.5">
                        <input type="hidden" id="kat-form-id" value="">

                        {{-- Nama Kategori --}}
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">Nama Kategori <span class="text-rose-500">*</span></label>
                            <input type="text" id="kat-input-nama" required maxlength="100" placeholder="Misal: Bimbingan Belajar" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        </div>

                        {{-- Deskripsi --}}
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">Deskripsi (Opsional)</label>
                            <textarea id="kat-input-deskripsi" rows="3" maxlength="500" placeholder="Uraian tujuan atau cakupan kategori..." class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"></textarea>
                        </div>

                        {{-- Tombol Aksi Form --}}
                        <div class="pt-2 flex items-center gap-2">
                            <button type="submit" id="kat-btn-submit" class="flex-1 py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer text-center">
                                Simpan Kategori
                            </button>
                            <button type="button" id="kat-btn-cancel" onclick="resetKatForm()" class="hidden py-2 px-3 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold transition-colors cursor-pointer">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-200/60 text-[11px] text-slate-400">
                    <p>💡 Kategori aktif muncul di dropdown form input kegiatan seluruh Admin Cabang. Badge kategori seragam bertema hijau.</p>
                </div>
            </div>

            {{-- Kolom Kanan: Daftar Master Kategori Kegiatan (7 Kolom di Layar Lebar) --}}
            <div class="lg:col-span-7 flex flex-col gap-3">
                {{-- Toolbar: Judul + Muat Ulang --}}
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <span>Daftar Kategori</span>
                        <span id="kat-count-badge" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">...</span>
                    </span>
                    <button type="button" onclick="loadKategoriKegiatanList()" class="text-[11px] text-emerald-700 hover:text-emerald-800 font-semibold flex items-center gap-1 cursor-pointer transition-colors">
                        <svg id="kat-reload-icon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Muat Ulang</span>
                    </button>
                </div>

                {{-- Tab Filter Semua / Aktif / Nonaktif + Pencarian --}}
                <div class="flex flex-col sm:flex-row gap-2">
                    {{-- Tab Chips --}}
                    <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1 shrink-0">
                        <button type="button" id="kat-tab-all" onclick="setKatTab('all')" class="kat-tab-btn px-3 py-1 rounded-lg text-[11px] font-semibold transition-all bg-white text-emerald-700 shadow-xs">
                            Semua <span id="kat-tab-count-all" class="font-bold"></span>
                        </button>
                        <button type="button" id="kat-tab-active" onclick="setKatTab('active')" class="kat-tab-btn px-3 py-1 rounded-lg text-[11px] font-semibold transition-all text-slate-500 hover:text-slate-700">
                            Aktif <span id="kat-tab-count-active" class="font-bold"></span>
                        </button>
                        <button type="button" id="kat-tab-inactive" onclick="setKatTab('inactive')" class="kat-tab-btn px-3 py-1 rounded-lg text-[11px] font-semibold transition-all text-slate-500 hover:text-slate-700">
                            Nonaktif <span id="kat-tab-count-inactive" class="font-bold"></span>
                        </button>
                    </div>
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-0">
                        <input type="text" id="kat-search-input" oninput="renderKatList()" placeholder="Cari nama atau slug..." class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                {{-- Container Daftar Item --}}
                <div id="kat-list-container" class="flex-1 min-h-[280px] max-h-[400px] overflow-y-auto space-y-2 pr-0.5" style="scrollbar-width: thin; scrollbar-color: #d1fae5 transparent;">
                    {{-- Skeleton loading awal --}}
                    <div class="kat-skeleton space-y-2">
                        @foreach(range(1,3) as $_)
                        <div class="p-3.5 bg-white rounded-xl border border-slate-200/80 animate-pulse">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-200 shrink-0"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-3 bg-slate-200 rounded w-2/3"></div>
                                    <div class="h-2 bg-slate-100 rounded w-1/2"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Modal --}}
        <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <span class="text-[11px] text-slate-400">Pilihan kategori kegiatan ini otomatis tersinkronisasi dengan filter monitoring.</span>
            <button type="button" onclick="closeKategoriKegiatanModal()" class="px-4 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-100 transition-colors cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>
@endif
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

window._lightboxItems = [];
window._lightboxIndex = 0;
window._lightboxDate = '';

function openKegiatanLightbox(btn) {
    let fotos = [];
    try {
        fotos = JSON.parse(btn.dataset.fotos || '[]');
    } catch (e) {
        fotos = [];
    }
    if (!fotos.length) return;

    window._lightboxItems = fotos;
    window._lightboxIndex = 0;
    window._lightboxDate = btn.dataset.subtitle || '';

    const titleEl = document.getElementById('lightbox-title');
    if (titleEl) titleEl.textContent = btn.dataset.title || 'Dokumentasi Kegiatan';

    updateLightboxGallery();
    showLightboxModal();
}

function openLightbox(imageUrl, title, subtitle) {
    if (!imageUrl) return;
    window._lightboxItems = [{ url: imageUrl, nama: 'Dokumentasi Kegiatan' }];
    window._lightboxIndex = 0;
    window._lightboxDate = subtitle || '';

    const titleEl = document.getElementById('lightbox-title');
    if (titleEl) titleEl.textContent = title || 'Dokumentasi Kegiatan';

    updateLightboxGallery();
    showLightboxModal();
}

function showLightboxModal() {
    const modal = document.getElementById('lightbox-modal');
    const card = document.getElementById('lightbox-card');
    if (!modal) return;

    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        if (card) {
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }
    }, 10);
}

function updateLightboxGallery() {
    const items = window._lightboxItems || [];
    if (!items.length) return;

    if (window._lightboxIndex < 0) window._lightboxIndex = 0;
    if (window._lightboxIndex >= items.length) window._lightboxIndex = items.length - 1;

    const current = items[window._lightboxIndex];
    const img = document.getElementById('lightbox-img');
    const subtitleEl = document.getElementById('lightbox-subtitle');
    const counterEl = document.getElementById('lightbox-counter');
    const downloadEl = document.getElementById('lightbox-download');
    const prevBtn = document.getElementById('lightbox-prev-btn');
    const nextBtn = document.getElementById('lightbox-next-btn');

    if (img) {
        img.src = current.url;
        img.alt = current.nama || 'Dokumentasi Full';
    }
    if (downloadEl) downloadEl.href = current.url;

    const parts = [];
    if (window._lightboxDate) parts.push('Tanggal: ' + window._lightboxDate);
    if (current.nama && items.length > 1) parts.push(current.nama + (current.ukuran ? ' (' + current.ukuran + ')' : ''));
    if (subtitleEl) subtitleEl.textContent = parts.join(' • ');

    if (items.length <= 1) {
        if (counterEl) counterEl.classList.add('hidden');
        if (prevBtn) prevBtn.classList.add('hidden');
        if (nextBtn) nextBtn.classList.add('hidden');
    } else {
        if (counterEl) {
            counterEl.textContent = (window._lightboxIndex + 1) + ' / ' + items.length;
            counterEl.classList.remove('hidden');
        }
        if (prevBtn) {
            prevBtn.classList.remove('hidden');
            prevBtn.disabled = (window._lightboxIndex === 0);
        }
        if (nextBtn) {
            nextBtn.classList.remove('hidden');
            nextBtn.disabled = (window._lightboxIndex === items.length - 1);
        }
    }
}

function prevLightboxImage() {
    if (window._lightboxIndex > 0) {
        window._lightboxIndex--;
        updateLightboxGallery();
    }
}

function nextLightboxImage() {
    if (window._lightboxIndex < (window._lightboxItems || []).length - 1) {
        window._lightboxIndex++;
        updateLightboxGallery();
    }
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
    const lightboxModal = document.getElementById('lightbox-modal');
    if (lightboxModal && !lightboxModal.classList.contains('hidden')) {
        if (e.key === 'Escape') {
            closeLightbox();
            return;
        }
        if (e.key === 'ArrowLeft') {
            prevLightboxImage();
            return;
        }
        if (e.key === 'ArrowRight') {
            nextLightboxImage();
            return;
        }
    }
    if (e.key === 'Escape') {
        closeLightbox();
        closeKategoriKegiatanModal();
    }
});

// =========================================================================
// FITUR: KELOLA MASTER KATEGORI KEGIATAN (ADMIN PUSAT)
// =========================================================================
window._katList = @json($kategoriList ?? []);
window._katTab = 'all';
window._katEditingId = null;

function openKategoriKegiatanModal() {
    const modal = document.getElementById('modal-kategori-kegiatan');
    const card = document.getElementById('modal-kategori-kegiatan-card');
    if (!modal) return;

    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        if (card) {
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }
    }, 10);

    loadKategoriKegiatanList();
}

function closeKategoriKegiatanModal() {
    const modal = document.getElementById('modal-kategori-kegiatan');
    const card = document.getElementById('modal-kategori-kegiatan-card');
    if (!modal) return;

    modal.classList.add('opacity-0');
    if (card) {
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
    }
    setTimeout(() => {
        modal.classList.add('hidden');
        resetKatForm();
        hideKatToast();
    }, 200);
}

function showKatToast(msg, isSuccess = true) {
    const toast = document.getElementById('kat-toast');
    const msgEl = document.getElementById('kat-toast-msg');
    if (!toast || !msgEl) return;

    msgEl.textContent = msg;
    toast.className = isSuccess
        ? 'mx-6 mt-4 p-3 rounded-2xl text-xs font-semibold flex items-center justify-between transition-all bg-emerald-50 text-emerald-800 border border-emerald-200'
        : 'mx-6 mt-4 p-3 rounded-2xl text-xs font-semibold flex items-center justify-between transition-all bg-rose-50 text-rose-800 border border-rose-200';
    toast.classList.remove('hidden');
}

function hideKatToast() {
    const toast = document.getElementById('kat-toast');
    if (toast) toast.classList.add('hidden');
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function setKatTab(tab) {
    window._katTab = tab;
    ['all', 'active', 'inactive'].forEach(t => {
        const btn = document.getElementById('kat-tab-' + t);
        if (!btn) return;
        if (t === tab) {
            btn.className = 'kat-tab-btn px-3 py-1 rounded-lg text-[11px] font-semibold transition-all bg-white text-emerald-700 shadow-xs';
        } else {
            btn.className = 'kat-tab-btn px-3 py-1 rounded-lg text-[11px] font-semibold transition-all text-slate-500 hover:text-slate-700';
        }
    });
    renderKatList();
}

function showKatSkeleton() {
    const container = document.getElementById('kat-list-container');
    if (!container) return;
    container.innerHTML = `
        <div class="space-y-2">
            <div class="p-3.5 bg-white rounded-xl border border-slate-200/80 animate-pulse">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-200 shrink-0"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-3 bg-slate-200 rounded w-2/3"></div>
                        <div class="h-2 bg-slate-100 rounded w-1/2"></div>
                    </div>
                </div>
            </div>
            <div class="p-3.5 bg-white rounded-xl border border-slate-200/80 animate-pulse">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-200 shrink-0"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-3 bg-slate-200 rounded w-1/2"></div>
                        <div class="h-2 bg-slate-100 rounded w-1/3"></div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

async function loadKategoriKegiatanList() {
    const badge = document.getElementById('kat-count-badge');
    const reloadIcon = document.getElementById('kat-reload-icon');
    if (badge) badge.textContent = '...';
    if (reloadIcon) reloadIcon.classList.add('animate-spin');
    showKatSkeleton();

    try {
        const res = await fetch(`{{ route('pusat.kategori-kegiatan.index') }}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await res.json();
        if (data && data.data) {
            window._katList = data.data;
            renderKatList();
            syncFilterKategoriDropdown();
        }
    } catch (err) {
        const container = document.getElementById('kat-list-container');
        if (container) {
            container.innerHTML = `
                <div class="py-16 text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-rose-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <p class="text-xs text-rose-500 font-semibold">Gagal memuat kategori kegiatan</p>
                    <p class="text-[11px] text-slate-400 mt-1">Silakan coba tombol Muat Ulang di atas.</p>
                </div>
            `;
        }
    } finally {
        if (reloadIcon) reloadIcon.classList.remove('animate-spin');
    }
}

function getInitialAvatar(nama) {
    return (nama || '?').trim().charAt(0).toUpperCase();
}

function renderKatList() {
    const container = document.getElementById('kat-list-container');
    const badge = document.getElementById('kat-count-badge');
    const searchInput = document.getElementById('kat-search-input');
    const searchQ = (searchInput?.value || '').toLowerCase().trim();

    if (!container) return;

    const all = window._katList || [];
    const countAll = all.length;
    const countActive = all.filter(x => x.is_active).length;
    const countInactive = all.filter(x => !x.is_active).length;

    const tabAll = document.getElementById('kat-tab-count-all');
    const tabActive = document.getElementById('kat-tab-count-active');
    const tabInactive = document.getElementById('kat-tab-count-inactive');
    if (tabAll) tabAll.textContent = countAll > 0 ? `(${countAll})` : '';
    if (tabActive) tabActive.textContent = countActive > 0 ? `(${countActive})` : '';
    if (tabInactive) tabInactive.textContent = countInactive > 0 ? `(${countInactive})` : '';

    let items = all.slice();

    if (window._katTab === 'active') {
        items = items.filter(it => it.is_active);
    } else if (window._katTab === 'inactive') {
        items = items.filter(it => !it.is_active);
    }

    if (searchQ) {
        items = items.filter(it =>
            (it.nama || '').toLowerCase().includes(searchQ) ||
            (it.kode || '').toLowerCase().includes(searchQ) ||
            (it.deskripsi || '').toLowerCase().includes(searchQ)
        );
    }

    if (badge) badge.textContent = `${items.length} Kategori`;

    if (items.length === 0) {
        const isSearch = searchQ.length > 0;
        container.innerHTML = `
            <div class="py-16 text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="${isSearch ? 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z' : 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'}"/>
                    </svg>
                </div>
                <p class="text-xs font-semibold text-slate-600">${isSearch ? 'Tidak ditemukan kategori yang cocok' : 'Belum ada kategori'}</p>
                <p class="text-[11px] text-slate-400 mt-1">${isSearch ? 'Coba cari dengan kata kunci lain.' : 'Tambahkan kategori kegiatan baru melalui form di sisi kiri.'}</p>
            </div>
        `;
        return;
    }

    let html = '';
    items.forEach(it => {
        const initial = getInitialAvatar(it.nama);
        const isEditing = (window._katEditingId !== null && String(window._katEditingId) === String(it.id));
        const isActive = !!it.is_active;
        const usageCount = it.kegiatan_harian_count || 0;
        const hasUsage = usageCount > 0;

        const cardClass = isEditing
            ? 'p-3.5 bg-emerald-50/70 rounded-xl border-2 border-emerald-500 shadow-sm transition-all duration-150 ring-2 ring-emerald-500/20'
            : (isActive
                ? 'p-3.5 bg-white rounded-xl border border-slate-200/80 hover:border-emerald-300 hover:-translate-y-0.5 hover:shadow-sm transition-all duration-150'
                : 'p-3.5 bg-slate-50/70 rounded-xl border border-slate-200/50 opacity-60 transition-all duration-150');

        const statusPill = isActive
            ? `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif</span>`
            : `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Nonaktif</span>`;

        const usageHtml = hasUsage
            ? `<span class="inline-flex items-center gap-1 text-[10px] text-slate-500">
                    <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Dipakai di <strong class="text-emerald-700 font-bold">${usageCount}</strong> kegiatan
               </span>`
            : `<span class="inline-flex items-center gap-1 text-[10px] text-slate-400">
                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    Belum dipakai
               </span>`;

        const deleteBtn = hasUsage
            ? `<button type="button" disabled class="w-7 h-7 rounded-full text-slate-300 bg-slate-100 cursor-not-allowed flex items-center justify-center shrink-0" title="Kategori sudah digunakan pada ${usageCount} data kegiatan harian dan tidak dapat dihapus. Nonaktifkan saja.">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
               </button>`
            : `<button type="button" onclick="deleteKat(${it.id}, '${escapeHtml(it.nama).replace(/'/g, "\\'")}')" class="w-7 h-7 rounded-full text-slate-400 hover:text-rose-700 hover:bg-rose-50 flex items-center justify-center transition-colors cursor-pointer shrink-0" title="Hapus Kategori">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
               </button>`;

        const toggleIcon = isActive
            ? `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>`
            : `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;

        html += `
            <div id="kat-card-${it.id}" class="${cardClass}">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
                        ${initial}
                    </div>
                    <div class="flex-1 min-w-0 space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-bold ${isActive ? 'text-slate-900' : 'text-slate-500'}">${escapeHtml(it.nama)}</span>
                            <code class="text-[10px] text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded font-mono">${escapeHtml(it.kode)}</code>
                            ${statusPill}
                        </div>
                        ${it.deskripsi ? `<p class="text-[11px] text-slate-500 leading-relaxed line-clamp-2">${escapeHtml(it.deskripsi)}</p>` : ''}
                        <div class="pt-0.5">${usageHtml}</div>
                    </div>
                    <div class="flex items-center gap-1 shrink-0 self-start">
                        <button type="button" onclick="editKat(${it.id})" class="w-7 h-7 rounded-full text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 flex items-center justify-center transition-colors cursor-pointer shrink-0" title="Edit Kategori">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button type="button" onclick="toggleStatusKat(${it.id})" class="w-7 h-7 rounded-full text-slate-400 hover:text-amber-700 hover:bg-amber-50 flex items-center justify-center transition-colors cursor-pointer shrink-0" title="${isActive ? 'Nonaktifkan Kategori' : 'Aktifkan Kategori'}">
                            ${toggleIcon}
                        </button>
                        ${deleteBtn}
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

function resetKatForm() {
    const form = document.getElementById('kat-form');
    if (form) form.reset();
    document.getElementById('kat-form-id').value = '';
    window._katEditingId = null;

    document.getElementById('kat-form-title').innerHTML = `
        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah Kategori Baru</span>
    `;
    document.getElementById('kat-btn-submit').textContent = 'Simpan Kategori';
    document.getElementById('kat-btn-cancel').classList.add('hidden');
    document.getElementById('kat-edit-indicator').classList.add('hidden');

    const errBox = document.getElementById('kat-form-error');
    if (errBox) {
        errBox.classList.add('hidden');
        errBox.innerHTML = '';
    }

    renderKatList();
}

function editKat(id) {
    const item = (window._katList || []).find(it => String(it.id) === String(id));
    if (!item) return;

    window._katEditingId = item.id;

    document.getElementById('kat-form-id').value = item.id;
    document.getElementById('kat-input-nama').value = item.nama;
    document.getElementById('kat-input-deskripsi').value = item.deskripsi || '';

    document.getElementById('kat-form-title').innerHTML = `
        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        <span>Edit Kategori</span>
    `;
    document.getElementById('kat-btn-submit').textContent = 'Perbarui Kategori';
    document.getElementById('kat-btn-cancel').classList.remove('hidden');
    document.getElementById('kat-edit-indicator').classList.remove('hidden');

    const errBox = document.getElementById('kat-form-error');
    if (errBox) errBox.classList.add('hidden');

    document.getElementById('kat-input-nama').focus();

    renderKatList();

    setTimeout(() => {
        const card = document.getElementById('kat-card-' + id);
        if (card) card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 50);
}

async function submitKategoriKegiatan(event) {
    event.preventDefault();

    const idInput = document.getElementById('kat-form-id');
    const namaInput = document.getElementById('kat-input-nama');
    const deskripsiInput = document.getElementById('kat-input-deskripsi');
    const btnSubmit = document.getElementById('kat-btn-submit');
    const errBox = document.getElementById('kat-form-error');

    const id = idInput ? idInput.value : '';
    const nama = namaInput ? namaInput.value.trim() : '';
    const deskripsi = deskripsiInput ? deskripsiInput.value.trim() : '';

    if (errBox) {
        errBox.classList.add('hidden');
        errBox.innerHTML = '';
    }

    if (!nama) {
        if (errBox) {
            errBox.innerHTML = '<p>Nama kategori kegiatan wajib diisi.</p>';
            errBox.classList.remove('hidden');
        }
        if (namaInput) namaInput.focus();
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const url = id ? (`{{ url('pusat/kategori-kegiatan') }}/` + id) : `{{ route('pusat.kategori-kegiatan.store') }}`;
    const method = id ? 'PUT' : 'POST';

    btnSubmit.disabled = true;
    btnSubmit.classList.add('opacity-50');

    try {
        const res = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ nama: nama, deskripsi: deskripsi })
        });

        const data = await res.json();

        if (res.ok && data.success) {
            showKatToast(data.message, true);
            resetKatForm();
            await loadKategoriKegiatanList();
        } else {
            let errorMsg = data.message || 'Terjadi kesalahan validasi.';
            if (data.errors) {
                const list = Object.values(data.errors).flat().map(e => `<li>• ${escapeHtml(e)}</li>`).join('');
                errorMsg = `<ul class="space-y-0.5">${list}</ul>`;
            }
            if (errBox) {
                errBox.innerHTML = errorMsg;
                errBox.classList.remove('hidden');
            } else {
                showKatToast(data.message || 'Gagal menyimpan kategori.', false);
            }
        }
    } catch (err) {
        console.error('Error submitting category:', err);
        showKatToast('Gagal menghubungi server.', false);
    } finally {
        btnSubmit.disabled = false;
        btnSubmit.classList.remove('opacity-50');
    }
}

async function toggleStatusKat(id) {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const res = await fetch(`{{ url('pusat/kategori-kegiatan') }}/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await res.json();
        if (res.ok && data.success) {
            showKatToast(data.message, true);
            await loadKategoriKegiatanList();
        } else {
            showKatToast(data.message || 'Gagal mengubah status kategori.', false);
        }
    } catch (err) {
        showKatToast('Gagal menghubungi server.', false);
    }
}

async function deleteKat(id, nama) {
    if (!confirm(`Hapus kategori kegiatan "${nama}"?\n\nKategori yang sudah terhubung dengan data kegiatan tidak dapat dihapus permanen.`)) {
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const res = await fetch(`{{ url('pusat/kategori-kegiatan') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await res.json();
        if (res.ok && data.success) {
            showKatToast(data.message, true);
            await loadKategoriKegiatanList();
        } else {
            showKatToast(data.message || 'Gagal menghapus kategori.', false);
        }
    } catch (err) {
        showKatToast('Gagal menghubungi server.', false);
    }
}

function syncFilterKategoriDropdown() {
    const select = document.getElementById('filter_kategori_kegiatan');
    if (!select || !window._katList) return;

    const currentVal = select.value;
    let html = '<option value="">Semua Kategori</option>';

    window._katList.forEach(it => {
        const isSelected = String(currentVal) === String(it.kode) ? 'selected' : '';
        const nonAktifText = !it.is_active ? ' (Nonaktif)' : '';
        html += `<option value="${escapeHtml(it.kode)}" ${isSelected}>${escapeHtml(it.nama)}${nonAktifText}</option>`;
    });

    select.innerHTML = html;
}
</script>
@endpush
