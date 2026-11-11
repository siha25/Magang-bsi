@extends('layouts.app')

@section('title', 'Tambah Kegiatan Harian')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
            <a href="{{ route('dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('kegiatan-harian.index') }}" class="hover:text-emerald-700 transition-colors">Kegiatan Harian</a>
            <span>/</span>
            <span class="text-emerald-800 font-semibold">Tambah Kegiatan</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Catat Kegiatan Harian</h2>
    </div>

    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-5 sm:p-8">
        <form method="POST" action="{{ route('kegiatan-harian.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal kegiatan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan') }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('tanggal_kegiatan') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    @error('tanggal_kegiatan')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Asrama <span class="text-rose-500">*</span></label>
                    <select name="asrama_id" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('asrama_id') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        <option value="">Pilih Asrama</option>
                        @foreach($asramas as $asrama)
                            <option value="{{ $asrama->id }}" {{ old('asrama_id') == $asrama->id ? 'selected' : '' }}>{{ $asrama->nama_asrama }}</option>
                        @endforeach
                    </select>
                    @error('asrama_id')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
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
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Nama kegiatan / judul <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('nama_kegiatan') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    @error('nama_kegiatan')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Jam mulai <span class="text-rose-500">*</span></label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('jam_mulai') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    @error('jam_mulai')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Jam selesai <span class="text-rose-500">*</span></label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('jam_selesai') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    @error('jam_selesai')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Deskripsi kegiatan</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('deskripsi') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Jumlah anak asuh yang mengikuti <span class="text-rose-500">*</span></label>
                    <input type="number" min="0" name="jumlah_peserta" value="{{ old('jumlah_peserta', 0) }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('jumlah_peserta') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    @error('jumlah_peserta')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-1 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Catatan tambahan</label>
                    <textarea name="catatan_tambahan" rows="3" class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('catatan_tambahan') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">{{ old('catatan_tambahan') }}</textarea>
                    @error('catatan_tambahan')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Foto / Dokumentasi Kegiatan --}}
                <div class="sm:col-span-2 space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Foto Dokumentasi Kegiatan
                        <span class="text-[11px] font-normal lowercase text-slate-400 ml-1">(opsional, max 5 MB: JPG, PNG, WEBP)</span>
                    </label>

                    <div class="border-2 border-dashed @error('foto_dokumentasi') border-rose-400 bg-rose-50/20 @else border-slate-200 hover:border-emerald-400 bg-slate-50/60 @enderror rounded-2xl p-5 sm:p-6 transition-all text-center">
                        <input type="file" id="foto_dokumentasi_shared" name="foto_dokumentasi" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewImageShared(this)">

                        <div id="upload-placeholder-shared" class="cursor-pointer py-3" onclick="document.getElementById('foto_dokumentasi_shared').click()">
                            <div class="w-12 h-12 mx-auto rounded-2xl bg-emerald-100/70 text-emerald-700 flex items-center justify-center mb-2.5 transition-transform hover:scale-105">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-slate-700">Klik untuk unggah foto kegiatan</p>
                            <p class="text-[11px] text-slate-400 mt-1">Format didukung: JPG, JPEG, PNG, atau WEBP (Maksimal 5 MB)</p>
                        </div>

                        {{-- Preview Box --}}
                        <div id="preview-wrapper-shared" class="hidden">
                            <div class="relative inline-block max-w-sm rounded-2xl overflow-hidden border border-slate-200 shadow-sm bg-white">
                                <img id="preview-img-shared" src="" alt="Pratinjau Foto Dokumentasi" class="max-h-64 w-auto object-cover rounded-xl mx-auto">
                                <button type="button" onclick="removeImageShared()" class="absolute top-2 right-2 p-1.5 rounded-full bg-rose-600 hover:bg-rose-700 text-white shadow-md transition-all cursor-pointer" title="Hapus Gambar">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <p id="file-name-info-shared" class="text-xs text-slate-500 mt-2 font-medium"></p>
                        </div>
                    </div>
                    @error('foto_dokumentasi')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('kegiatan-harian.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs sm:text-sm font-semibold">Batal</a>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold">Simpan Kegiatan</button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImageShared(input) {
    const file = input.files && input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img-shared').src = e.target.result;
            document.getElementById('upload-placeholder-shared').classList.add('hidden');
            document.getElementById('preview-wrapper-shared').classList.remove('hidden');
            const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
            document.getElementById('file-name-info-shared').textContent = file.name + ' (' + sizeMb + ' MB)';
        };
        reader.readAsDataURL(file);
    }
}

function removeImageShared() {
    const input = document.getElementById('foto_dokumentasi_shared');
    input.value = '';
    document.getElementById('preview-img-shared').src = '';
    document.getElementById('upload-placeholder-shared').classList.remove('hidden');
    document.getElementById('preview-wrapper-shared').classList.add('hidden');
    document.getElementById('file-name-info-shared').textContent = '';
}
</script>
@endsection
