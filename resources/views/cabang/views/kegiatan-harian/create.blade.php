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
        <form method="POST" action="{{ route('cabang.kegiatan-harian.store') }}" class="space-y-6">
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
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('cabang.kegiatan-harian.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs sm:text-sm font-semibold transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/25 transition-all">Simpan Kegiatan</button>
            </div>
        </form>
    </div>
</div>
@endsection

