{{-- BAGIAN: PENGURUS CABANG - Edit Hafalan Qur'an --}}
@extends('cabang.layout.app')

@section('title', "Edit Setoran Hafalan Al-Qur'an")
@section('header_title', "Edit Setoran Hafalan")

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
            <a href="{{ route('cabang.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('cabang.hafalan-quran.index') }}" class="hover:text-emerald-700 transition-colors">Hafalan Al-Qur'an</a>
            <span>/</span>
            <span class="text-emerald-800 font-semibold">Edit</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Perbarui Nilai Hafalan</h2>
    </div>

    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-5 sm:p-8">
        <form method="POST" action="{{ route('cabang.hafalan-quran.update', $evaluasi->id) }}" class="space-y-6">
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

                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Materi Hafalan <span class="text-rose-500">*</span></label>
                    <input type="text" name="materi" value="{{ old('materi', $evaluasi->materi) }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal Setoran <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_evaluasi" value="{{ old('tanggal_evaluasi', $evaluasi->tanggal_evaluasi?->format('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Nilai (0 - 100) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.5" min="0" max="100" name="nilai" value="{{ old('nilai', $evaluasi->nilai) }}" required class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                </div>

                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Catatan Tajwid / Penguji</label>
                    <textarea name="catatan_pengaju" rows="3" class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">{{ old('catatan_pengaju', $evaluasi->catatan_pengaju) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('cabang.hafalan-quran.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs sm:text-sm font-semibold transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/25 transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

