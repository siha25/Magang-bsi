{{-- BAGIAN: PENGURUS CABANG - Detail Kegiatan Harian --}}
@extends('cabang.layout.app')

@section('title', 'Detail Kegiatan Harian')
@section('header_title', 'Detail Kegiatan Harian')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('cabang.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <a href="{{ route('cabang.kegiatan-harian.index') }}" class="hover:text-emerald-700 transition-colors">Kegiatan Harian</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Detail</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $kegiatan->nama_kegiatan }}</h2>
        </div>
        <a href="{{ route('cabang.kegiatan-harian.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-6 border-b border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Cabang Penyelenggara</span>
                <p class="font-bold text-slate-900 mt-0.5 text-sm">{{ $kegiatan->asrama?->nama_asrama }} ({{ $kegiatan->asrama?->wilayah }})</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Kategori</span>
                <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $kegiatan->kategori_label }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Tanggal & Waktu</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $kegiatan->tanggal_kegiatan?->translatedFormat('l, d F Y') }}</p>
                <p class="text-slate-500">{{ $kegiatan->jam_mulai_format }} - {{ $kegiatan->jam_selesai_format }} WIB</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Jumlah Peserta</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $kegiatan->jumlah_peserta }} anak asuh mengikuti</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Penginput Data</span>
                <p class="font-medium text-slate-700 mt-0.5">{{ $kegiatan->penginput?->name ?? 'Pengurus' }}</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Waktu Pencatatan</span>
                <p class="text-slate-500 mt-0.5">{{ $kegiatan->created_at?->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div>
            <span class="text-slate-400 block uppercase font-bold text-[10px] mb-1">Deskripsi Kegiatan</span>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs sm:text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                {{ $kegiatan->deskripsi ?: 'Tidak ada deskripsi tambahan.' }}
            </div>
        </div>

        @if($kegiatan->catatan_tambahan)
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px] mb-1">Catatan Tambahan</span>
                <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200/60 text-xs sm:text-sm text-amber-900 leading-relaxed whitespace-pre-line">
                    {{ $kegiatan->catatan_tambahan }}
                </div>
            </div>
        @endif

        {{-- Foto Dokumentasi Kegiatan (Maksimal 5 Foto) --}}
        <div>
            @php
                $daftarFoto = $kegiatan->semua_foto;
            @endphp

            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-slate-400 block uppercase font-bold text-[10px]">Foto Dokumentasi Kegiatan</span>
                    @if(count($daftarFoto) > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                            {{ count($daftarFoto) }} Foto
                        </span>
                    @endif
                </div>
                @if(count($daftarFoto) > 0)
                    <span class="text-[11px] text-slate-400">Klik foto untuk melihat ukuran penuh</span>
                @endif
            </div>

            @if(count($daftarFoto) > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3.5">
                    @foreach($daftarFoto as $idx => $foto)
                        <div class="group relative rounded-2xl overflow-hidden border border-slate-200/90 bg-white shadow-xs hover:shadow-md hover:border-emerald-400 transition-all flex flex-col">
                            <button type="button" 
                                    onclick="openGalleryLightbox({{ $idx }})" 
                                    class="relative w-full aspect-square bg-slate-100 overflow-hidden cursor-pointer block text-left">
                                <img src="{{ $foto['url'] }}" 
                                     alt="{{ $foto['nama'] }}" 
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1.5 text-white text-xs font-semibold backdrop-blur-2xs">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Lihat</span>
                                </div>
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-slate-900/70 text-white text-[10px] font-bold backdrop-blur-2xs">
                                    #{{ $idx + 1 }}
                                </span>
                            </button>
                            <div class="p-2 bg-white text-[11px] border-t border-slate-100">
                                <p class="font-medium text-slate-700 truncate" title="{{ $foto['nama'] }}">{{ $foto['nama'] }}</p>
                                @if($foto['ukuran'])
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $foto['ukuran'] }}</p>
                                @elseif($foto['is_legacy'])
                                    <p class="text-[10px] text-amber-600 mt-0.5 font-medium">Data Historis</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/70 text-center">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mb-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-600">Belum ada foto dokumentasi</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Tidak ada foto dokumentasi yang diunggah untuk kegiatan harian ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Lightbox Galeri Foto Dokumentasi --}}
<div id="lightbox-modal" class="fixed inset-0 z-50 hidden bg-slate-950/85 backdrop-blur-xs p-4 flex items-center justify-center transition-all opacity-0" onclick="if(event.target === this) closeLightbox()">
    <div class="bg-white rounded-3xl max-w-3xl w-full overflow-hidden shadow-2xl border border-slate-100 transform transition-all scale-95" id="lightbox-card">
        {{-- Header Modal --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
            <div>
                <h3 id="lightbox-title" class="text-sm font-bold text-slate-900">{{ $kegiatan->nama_kegiatan }}</h3>
                <p id="lightbox-subtitle" class="text-xs text-slate-500 mt-0.5"></p>
            </div>
            <div class="flex items-center gap-2">
                <span id="lightbox-counter" class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-200/70 text-slate-700"></span>
                <button type="button" onclick="closeLightbox()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors cursor-pointer" title="Tutup">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Tutup</span>
                </button>
            </div>
        </div>

        {{-- Preview Gambar dengan Navigasi Sebelumnya / Selanjutnya --}}
        <div class="relative p-4 sm:p-6 bg-slate-950/5 flex items-center justify-center min-h-[350px] max-h-[70vh] overflow-hidden">
            {{-- Tombol Prev --}}
            <button type="button" id="lightbox-prev-btn" onclick="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-slate-700 shadow-md flex items-center justify-center transition-all cursor-pointer z-10 disabled:opacity-30 disabled:cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <img id="lightbox-img" src="" alt="Dokumentasi" class="max-h-[60vh] w-auto max-w-full rounded-2xl object-contain shadow-lg transition-opacity duration-200">

            {{-- Tombol Next --}}
            <button type="button" id="lightbox-next-btn" onclick="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-slate-700 shadow-md flex items-center justify-center transition-all cursor-pointer z-10 disabled:opacity-30 disabled:cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        {{-- Footer Modal --}}
        <div class="px-6 py-3.5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
            <button type="button" onclick="closeLightbox()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-semibold shadow-2xs transition-colors cursor-pointer">
                <span>Kembali ke Detail Kegiatan</span>
            </button>
            <a id="lightbox-download" href="" target="_blank" download class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Buka / Unduh Foto Asli</span>
            </a>
        </div>
    </div>
</div>

<script>
    const galleryItems = @json($daftarFoto);
    let currentImageIndex = 0;

    function openGalleryLightbox(index) {
        if (!galleryItems || galleryItems.length === 0) return;
        currentImageIndex = index;
        updateLightboxContent();

        const modal = document.getElementById('lightbox-modal');
        const card = document.getElementById('lightbox-card');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            if (card) {
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }
        }, 10);
    }

    function updateLightboxContent() {
        if (currentImageIndex < 0) currentImageIndex = 0;
        if (currentImageIndex >= galleryItems.length) currentImageIndex = galleryItems.length - 1;

        const current = galleryItems[currentImageIndex];
        const img = document.getElementById('lightbox-img');
        const subtitle = document.getElementById('lightbox-subtitle');
        const counter = document.getElementById('lightbox-counter');
        const download = document.getElementById('lightbox-download');
        const prevBtn = document.getElementById('lightbox-prev-btn');
        const nextBtn = document.getElementById('lightbox-next-btn');

        img.src = current.url;
        img.alt = current.nama;
        subtitle.textContent = current.nama + (current.ukuran ? ' (' + current.ukuran + ')' : '');
        counter.textContent = (currentImageIndex + 1) + ' / ' + galleryItems.length;
        download.href = current.url;

        if (galleryItems.length <= 1) {
            prevBtn.classList.add('hidden');
            nextBtn.classList.add('hidden');
        } else {
            prevBtn.classList.remove('hidden');
            nextBtn.classList.remove('hidden');
            prevBtn.disabled = (currentImageIndex === 0);
            nextBtn.disabled = (currentImageIndex === galleryItems.length - 1);
        }
    }

    function prevImage() {
        if (currentImageIndex > 0) {
            currentImageIndex--;
            updateLightboxContent();
        }
    }

    function nextImage() {
        if (currentImageIndex < galleryItems.length - 1) {
            currentImageIndex++;
            updateLightboxContent();
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
        const modal = document.getElementById('lightbox-modal');
        if (modal && !modal.classList.contains('hidden')) {
            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowLeft') {
                prevImage();
            } else if (e.key === 'ArrowRight') {
                nextImage();
            }
        }
    });
</script>
@endsection

