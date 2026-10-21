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
        <form method="POST" action="{{ route('kegiatan-harian.store') }}" class="space-y-6">
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
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('kegiatan-harian.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs sm:text-sm font-semibold">Batal</a>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold">Simpan Kegiatan</button>
            </div>
        </form>
    </div>
</div>
@endsection
