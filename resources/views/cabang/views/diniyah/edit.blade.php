{{-- BAGIAN: PENGURUS CABANG - Edit Pengetahuan Diniyah --}}
@extends('cabang.layout.app')

@section('title', 'Edit Nilai Pengetahuan Diniyah')
@section('header_title', 'Edit Nilai Diniyah')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
            <a href="{{ route('cabang.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('cabang.diniyah.index') }}" class="hover:text-emerald-700 transition-colors">Pengetahuan Diniyah</a>
            <span>/</span>
            <span class="text-emerald-800 font-semibold">Edit Nilai</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Perbarui Nilai Diniyah</h2>
    </div>

    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-5 sm:p-8">
        <form method="POST" action="{{ route('cabang.diniyah.update', $evaluasi->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Nama Santri <span class="text-rose-500">*</span></label>
                    <select name="anak_asuh_id" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        @foreach($santriList as $santri)
                            <option value="{{ $santri->id }}" {{ old('anak_asuh_id', $evaluasi->anak_asuh_id) == $santri->id ? 'selected' : '' }}>
                                {{ $santri->nama_lengkap }} (NIS: {{ $santri->nomor_induk }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Kategori Diniyah <span class="text-rose-500">*</span></label>
                    <select name="jenis_evaluasi" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat }}" {{ old('jenis_evaluasi', $evaluasi->jenis_evaluasi) === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal Ujian <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_evaluasi" value="{{ old('tanggal_evaluasi', $evaluasi->tanggal_evaluasi?->format('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                </div>

                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Materi / Bab Ujian <span class="text-rose-500">*</span></label>
                    <input type="text" name="materi" value="{{ old('materi', $evaluasi->materi) }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Nilai (0 - 100) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.5" min="0" max="100" name="nilai" value="{{ old('nilai', $evaluasi->nilai) }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                </div>

                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Catatan Evaluasi Guru / Penguji</label>
                    <textarea name="catatan_pengaju" rows="3" class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">{{ old('catatan_pengaju', $evaluasi->catatan_pengaju) }}</textarea>
                </div>

                {{-- Dokumen Bukti Hasil Ujian --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Dokumen Bukti Hasil Ujian</label>
                    @if($evaluasi->dokumen_bukti)
                        <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center gap-3 mb-2">
                            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="flex-1 text-xs">
                                <p class="font-semibold text-emerald-800">File sudah diunggah</p>
                                <p class="text-emerald-700">{{ basename($evaluasi->dokumen_bukti) }}</p>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="dokumen_bukti" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                    <p class="text-xs text-slate-500">Format: PDF, JPG, JPEG, PNG (Maksimal 5MB). Biarkan kosong jika tidak ingin mengubah file.</p>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('cabang.diniyah.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs sm:text-sm font-semibold transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/25 transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

