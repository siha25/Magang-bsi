{{-- BAGIAN: PENGURUS CABANG - Input Pengetahuan Diniyah --}}
@extends('cabang.layout.app')

@section('title', 'Input Nilai Pengetahuan Diniyah')
@section('header_title', 'Input Nilai Diniyah')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
            <a href="{{ route('cabang.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('cabang.diniyah.index') }}" class="hover:text-emerald-700 transition-colors">Pengetahuan Diniyah</a>
            <span>/</span>
            <span class="text-emerald-800 font-semibold">Input Nilai</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Input Nilai Pengetahuan Diniyah</h2>
        <p class="text-xs text-slate-500 mt-0.5">Penilaian ulangan hadis, fiqih, aqidah, tajwid cabang <strong>{{ $cabang?->nama_asrama }}</strong>.</p>
    </div>

    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-5 sm:p-8">
        <form method="POST" action="{{ route('cabang.diniyah.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Pilih Santri --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Nama Santri / Anak Asuh <span class="text-rose-500">*</span></label>
                    <select name="anak_asuh_id" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('anak_asuh_id') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        <option value="">-- Pilih Santri Cabang {{ $cabang?->nama_asrama }} --</option>
                        @foreach($santriList as $santri)
                            <option value="{{ $santri->id }}" {{ (old('anak_asuh_id', $selectedSantriId) == $santri->id) ? 'selected' : '' }}>
                                {{ $santri->nama_lengkap }} (NIS: {{ $santri->nomor_induk }} &bull; {{ $santri->jenjang_pendidikan }})
                            </option>
                        @endforeach
                    </select>
                    @error('anak_asuh_id')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Kategori Materi --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Kategori Diniyah <span class="text-rose-500">*</span></label>
                    <select name="jenis_evaluasi" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('jenis_evaluasi') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat }}" {{ old('jenis_evaluasi') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                    @error('jenis_evaluasi')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Tanggal Evaluasi --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal Ujian / Evaluasi <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_evaluasi" value="{{ old('tanggal_evaluasi', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('tanggal_evaluasi') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    @error('tanggal_evaluasi')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Materi / Judul Ulangan --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Materi / Bab Ujian <span class="text-rose-500">*</span></label>
                    <input type="text" name="materi" value="{{ old('materi') }}" placeholder="Contoh: Bab Tata Cara Wudhu & Shalat Sunnah" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('materi') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    @error('materi')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Nilai Angka --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Nilai (Skala 0 - 100) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.5" min="0" max="100" name="nilai" value="{{ old('nilai') }}" placeholder="Contoh: 85" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('nilai') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    @error('nilai')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Catatan Penguji --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Catatan Evaluasi Guru / Penguji</label>
                    <textarea name="catatan_pengaju" rows="3" placeholder="Catatan pemahaman materi santri..." class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('catatan_pengaju') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">{{ old('catatan_pengaju') }}</textarea>
                    @error('catatan_pengaju')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Dokumen Bukti Hasil Ujian --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Dokumen Bukti Hasil Ujian</label>
                    <div class="relative">
                        <input type="file" name="dokumen_bukti" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('dokumen_bukti') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                    </div>
                    <p class="text-xs text-slate-500">Format: PDF, JPG, JPEG, PNG (Maksimal 5MB). Contoh: foto lembar jawaban, hasil scan ujian, bukti hasil evaluasi.</p>
                    @error('dokumen_bukti')<p class="text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('cabang.diniyah.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs sm:text-sm font-semibold transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/25 transition-all">Simpan Nilai Diniyah</button>
            </div>
        </form>
    </div>
</div>
@endsection

