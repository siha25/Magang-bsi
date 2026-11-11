@extends('layouts.app')

@section('title', 'Detail Kegiatan Harian')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div>
        <div class="mb-1 flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('dashboard') }}" class="transition-colors hover:text-emerald-700">Dashboard</a>
            <span>/</span>
            <a href="{{ route('kegiatan-harian.index') }}" class="transition-colors hover:text-emerald-700">Kegiatan Harian</a>
            <span>/</span>
            <span class="font-semibold text-emerald-800">Detail</span>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Detail Kegiatan Harian</h2>
        <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">Informasi lengkap aktivitas harian anak asuh.</p>
    </div>

    <section class="overflow-hidden rounded-3xl border border-emerald-100/90 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-gradient-to-r from-emerald-50/50 via-white to-white p-5 sm:p-7">
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">{{ $kegiatan->kategori_label }}</p>
            <h3 class="mt-2 text-xl font-bold text-slate-900">{{ $kegiatan->nama_kegiatan }}</h3>
        </div>

        <dl class="grid grid-cols-1 gap-x-8 gap-y-6 p-5 sm:grid-cols-2 sm:p-7">
            <div>
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal kegiatan</dt>
                <dd class="mt-1.5 text-sm font-medium text-slate-800">{{ $kegiatan->tanggal_kegiatan->format('d M Y') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Asrama</dt>
                <dd class="mt-1.5 text-sm font-medium text-slate-800">{{ $kegiatan->asrama->nama_asrama ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Jam mulai dan selesai</dt>
                <dd class="mt-1.5 text-sm font-medium text-slate-800">{{ $kegiatan->jam_mulai_format }} - {{ $kegiatan->jam_selesai_format }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Jumlah peserta</dt>
                <dd class="mt-1.5 text-sm font-medium text-slate-800">{{ $kegiatan->jumlah_peserta }} anak</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Deskripsi kegiatan</dt>
                <dd class="mt-1.5 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $kegiatan->deskripsi ?: 'Tidak ada deskripsi.' }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Catatan tambahan</dt>
                <dd class="mt-1.5 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $kegiatan->catatan_tambahan ?: 'Tidak ada catatan tambahan.' }}</dd>
            </div>
            @if($kegiatan->foto_url)
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Foto Dokumentasi Kegiatan</dt>
                    <dd class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50 p-2 inline-block">
                        <button type="button" onclick="openLightbox('{{ $kegiatan->foto_url }}', '{{ addslashes($kegiatan->nama_kegiatan) }}', '{{ $kegiatan->tanggal_kegiatan->format('d M Y') }}')" class="group relative block overflow-hidden rounded-xl cursor-pointer text-left">
                            <img src="{{ $kegiatan->foto_url }}" alt="Dokumentasi Kegiatan" class="max-h-80 w-auto object-cover rounded-xl transition-transform duration-300 group-hover:scale-[1.01]">
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 text-white text-xs font-semibold">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>Lihat Ukuran Penuh</span>
                            </div>
                        </button>
                    </dd>
                </div>
            @endif
        </dl>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/60 p-5 sm:flex-row sm:justify-end sm:p-6">
            <a href="{{ route('kegiatan-harian.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">Kembali ke Kegiatan Harian</a>
        </div>
    </section>
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
                <span>Kembali ke Detail Kegiatan</span>
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

<script>
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
    </section>
</div>
@endsection
