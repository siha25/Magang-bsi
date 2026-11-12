{{-- BAGIAN: PENGURUS CABANG - Input Kegiatan Harian --}}
@extends('cabang.layout.app')

@section('title', 'Catat Kegiatan Harian')
@section('header_title', 'Catat Kegiatan Harian')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
            <a href="{{ route('cabang.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('cabang.kegiatan-harian.index') }}" class="hover:text-emerald-700 transition-colors">Kegiatan Harian</a>
            <span>/</span>
            <span class="text-emerald-800 font-semibold">Tambah Kegiatan</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Catat Kegiatan Harian</h2>
        <p class="text-xs text-slate-500 mt-0.5">Input aktivitas harian santri untuk cabang <strong>{{ $cabang?->nama_asrama }}</strong>.</p>
    </div>

    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-5 sm:p-8">
        <form method="POST" action="{{ route('cabang.kegiatan-harian.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Tanggal Kegiatan --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal kegiatan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('tanggal_kegiatan') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    @error('tanggal_kegiatan')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Cabang Asrama (Terkunci Otomatis dari Sesi Login) --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Cabang Penyelenggara</label>
                    <div class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-emerald-50/70 border border-emerald-200 text-emerald-900 font-semibold rounded-xl flex items-center justify-between">
                        <span>{{ $cabang?->nama_asrama }} ({{ $cabang?->kode_asrama }})</span>
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md bg-white text-emerald-700 border border-emerald-200">Cabang Anda</span>
                    </div>
                    <p class="text-[11px] text-slate-400">Data otomatis terikat ke cabang Anda secara aman dari sesi server.</p>
                </div>

                {{-- Kategori Kegiatan --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Kategori kegiatan <span class="text-rose-500">*</span></label>
                    <select name="kategori" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('kategori') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        <option value="">Pilih Kategori</option>
                        <option value="belajar" {{ old('kategori') === 'belajar' ? 'selected' : '' }}>Belajar</option>
                        <option value="muhadhoroh" {{ old('kategori') === 'muhadhoroh' ? 'selected' : '' }}>Muhadhoroh</option>
                        <option value="olahraga" {{ old('kategori') === 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                        <option value="acara_donatur" {{ old('kategori') === 'acara_donatur' ? 'selected' : '' }}>Acara Donatur</option>
                        <option value="bersih_bersih" {{ old('kategori') === 'bersih_bersih' ? 'selected' : '' }}>Bersih-bersih</option>
                    </select>
                    @error('kategori')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Nama Kegiatan --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Nama kegiatan / judul <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" placeholder="Contoh: Belajar Tajwid Bersama" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('nama_kegiatan') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    @error('nama_kegiatan')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Jam Mulai --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Jam mulai <span class="text-rose-500">*</span></label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', '08:00') }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('jam_mulai') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    @error('jam_mulai')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Jam Selesai --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Jam selesai <span class="text-rose-500">*</span></label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', '09:30') }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('jam_selesai') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    @error('jam_selesai')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Jumlah Peserta --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Jumlah anak asuh yang mengikuti <span class="text-rose-500">*</span></label>
                    <input type="number" min="0" name="jumlah_peserta" value="{{ old('jumlah_peserta', 0) }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('jumlah_peserta') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    @error('jumlah_peserta')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Deskripsi --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Deskripsi kegiatan</label>
                    <textarea name="deskripsi" rows="3" placeholder="Uraian singkat mengenai jalannya kegiatan..." class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('deskripsi') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Catatan Tambahan --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Catatan tambahan</label>
                    <textarea name="catatan_tambahan" rows="2" placeholder="Catatan kendala atau evaluasi jika ada..." class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('catatan_tambahan') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">{{ old('catatan_tambahan') }}</textarea>
                    @error('catatan_tambahan')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Foto / Dokumentasi Kegiatan (Maksimal 5 Foto) --}}
                <div class="sm:col-span-2 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Foto Dokumentasi Kegiatan
                            <span class="text-[11px] font-normal lowercase text-slate-400 ml-1">(opsional, maks 5 foto, maks 2 MB/foto)</span>
                        </label>
                        <span id="counter-badge" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 transition-colors">
                            0 / 5 foto dipilih
                        </span>
                    </div>

                    {{-- Pesan Error Validasi Klien (JS) --}}
                    <div id="client-error-box" class="hidden p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium"></div>

                    <div id="drop-area" class="border-2 border-dashed @if($errors->has('foto_dokumentasi') || $errors->has('foto_dokumentasi.*')) border-rose-400 bg-rose-50/20 @else border-slate-200 hover:border-emerald-400 bg-slate-50/60 @endif rounded-2xl p-5 sm:p-6 transition-all text-center">
                        <input type="file" id="foto_dokumentasi" name="foto_dokumentasi[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">

                        <div id="upload-placeholder" class="cursor-pointer py-3" onclick="document.getElementById('foto_dokumentasi').click()">
                            <div class="w-12 h-12 mx-auto rounded-2xl bg-emerald-100/70 text-emerald-700 flex items-center justify-center mb-2.5 transition-transform hover:scale-105">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-slate-700">Klik untuk memilih atau seret foto kegiatan ke sini</p>
                            <p class="text-[11px] text-slate-400 mt-1">Format didukung: JPG, JPEG, PNG, atau WebP &bull; Maksimal 2 MB per foto &bull; Maksimal 5 foto</p>
                        </div>

                        {{-- Preview Grid Multi-Foto --}}
                        <div id="preview-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mt-3 hidden text-left"></div>

                        {{-- Tombol Tambah Foto Tambahan jika belum 5 --}}
                        <div id="add-more-container" class="mt-4 pt-3 border-t border-slate-200/60 hidden flex justify-center">
                            <button type="button" onclick="document.getElementById('foto_dokumentasi').click()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Tambah Foto Lain</span>
                            </button>
                        </div>
                    </div>

                    {{-- Pesan Error Server-side --}}
                    @error('foto_dokumentasi')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                    @error('foto_dokumentasi.*')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('cabang.kegiatan-harian.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs sm:text-sm font-semibold transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/25 transition-all">Simpan Kegiatan</button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    const fileInput = document.getElementById('foto_dokumentasi');
    const previewGrid = document.getElementById('preview-grid');
    const counterBadge = document.getElementById('counter-badge');
    const errorBox = document.getElementById('client-error-box');
    const placeholder = document.getElementById('upload-placeholder');
    const addMoreContainer = document.getElementById('add-more-container');
    const dropArea = document.getElementById('drop-area');

    const MAX_FILES = 5;
    const MAX_SIZE_BYTES = 2 * 1024 * 1024; // 2 MB
    const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

    // DataTransfer untuk mengelola kumpulan file yang aktif
    let dt = new DataTransfer();

    function showError(msg) {
        errorBox.textContent = msg;
        errorBox.classList.remove('hidden');
    }

    function clearError() {
        errorBox.textContent = '';
        errorBox.classList.add('hidden');
    }

    function formatFileSize(bytes) {
        if (bytes >= 1048576) {
            return (bytes / 1048576).toFixed(2) + ' MB';
        }
        return (bytes / 1024).toFixed(0) + ' KB';
    }

    function updateView() {
        previewGrid.innerHTML = '';
        const count = dt.items.length;

        // Perbarui badge jumlah foto
        counterBadge.textContent = count + ' / ' + MAX_FILES + ' foto dipilih';
        if (count > 0) {
            counterBadge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 transition-colors';
            previewGrid.classList.remove('hidden');
            if (count < MAX_FILES) {
                addMoreContainer.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                addMoreContainer.classList.add('hidden');
                placeholder.classList.add('hidden');
            }
        } else {
            counterBadge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 transition-colors';
            previewGrid.classList.add('hidden');
            addMoreContainer.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }

        // Sinkronisasi ke elemen input
        fileInput.files = dt.files;

        // Render setiap kartu pratinjau
        Array.from(dt.files).forEach((file, index) => {
            const card = document.createElement('div');
            card.className = 'relative rounded-xl border border-slate-200 bg-white shadow-xs overflow-hidden flex flex-col group';

            const imgContainer = document.createElement('div');
            imgContainer.className = 'relative w-full aspect-square bg-slate-100 overflow-hidden';

            const img = document.createElement('img');
            img.className = 'w-full h-full object-cover';
            img.src = URL.createObjectURL(file);

            // Tombol batalkan / hapus foto ini
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.title = 'Batalkan foto ini';
            removeBtn.className = 'absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center shadow-md transition-all cursor-pointer';
            removeBtn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>';
            removeBtn.onclick = function(e) {
                e.stopPropagation();
                removeFileAtIndex(index);
            };

            imgContainer.appendChild(img);
            imgContainer.appendChild(removeBtn);

            const meta = document.createElement('div');
            meta.className = 'p-2 text-[10px] bg-white';
            meta.innerHTML = '<p class="font-semibold text-slate-700 truncate" title="' + file.name + '">' + file.name + '</p>' +
                             '<p class="text-slate-400 mt-0.5">' + formatFileSize(file.size) + '</p>';

            card.appendChild(imgContainer);
            card.appendChild(meta);
            previewGrid.appendChild(card);
        });
    }

    function removeFileAtIndex(idxToRemove) {
        const newDt = new DataTransfer();
        Array.from(dt.files).forEach((file, index) => {
            if (index !== idxToRemove) {
                newDt.items.add(file);
            }
        });
        dt = newDt;
        clearError();
        updateView();
    }

    function addFiles(newFiles) {
        clearError();
        const incoming = Array.from(newFiles);

        for (let file of incoming) {
            // 1. Cek batas maksimal foto
            if (dt.items.length >= MAX_FILES) {
                showError('Maksimal ' + MAX_FILES + ' foto dokumentasi yang dapat dipilih.');
                break;
            }

            // 2. Cek format berkas
            if (!ALLOWED_TYPES.includes(file.type)) {
                showError('Format file "' + file.name + '" tidak didukung. Harap pilih gambar dengan format JPG, JPEG, PNG, atau WebP.');
                continue;
            }

            // 3. Cek ukuran berkas (maks 2 MB)
            if (file.size > MAX_SIZE_BYTES) {
                showError('Ukuran file "' + file.name + '" (' + formatFileSize(file.size) + ') melebihi batas maksimal 2 MB.');
                continue;
            }

            // Cegah duplikasi file yang sama persis
            const isDuplicate = Array.from(dt.files).some(f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified);
            if (!isDuplicate) {
                dt.items.add(file);
            }
        }

        updateView();
    }

    fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            addFiles(this.files);
        }
    });

    // Drag and drop support
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.add('border-emerald-500', 'bg-emerald-50/40');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.remove('border-emerald-500', 'bg-emerald-50/40');
        }, false);
    });

    dropArea.addEventListener('drop', function(e) {
        const droppedFiles = e.dataTransfer.files;
        if (droppedFiles && droppedFiles.length > 0) {
            addFiles(droppedFiles);
        }
    }, false);
})();
</script>
@endsection

