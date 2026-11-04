@extends('layouts.app')

@section('title', 'Edit Nilai Evaluasi Diniyah')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Breadcrumb & Title --}}
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
            <a href="{{ route('dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('evaluasi-diniyah.index') }}" class="hover:text-emerald-700 transition-colors">Evaluasi Diniyah</a>
            <span>/</span>
            <span class="text-emerald-800 font-semibold">Edit Nilai</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Nilai Evaluasi Diniyah</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Perbarui data nilai ulangan santri {{ $evaluasiDiniyah->anakAsuh?->nama_lengkap }}.
        </p>
    </div>

    {{-- Alert Error Global --}}
    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-xs">
            <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1 text-xs sm:text-sm">
                <div class="font-bold mb-1">Mohon periksa kembali formulir berikut:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Form Container --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('evaluasi-diniyah.update', $evaluasiDiniyah) }}" class="space-y-6" id="evaluasiEditForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- 1. Cabang Asrama --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Cabang Asrama <span class="text-rose-500">*</span>
                    </label>
                    @if(auth()->user()?->isAdminCabang())
                        <input type="hidden" name="asrama_id" id="asrama_id" value="{{ $evaluasiDiniyah->asrama_id }}">
                        <div class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-100 border border-slate-200 rounded-xl text-slate-700 font-medium">
                            {{ $evaluasiDiniyah->asrama?->nama_asrama }}
                        </div>
                    @else
                        <select name="asrama_id" id="asrama_id" required 
                                class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('asrama_id') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                            @foreach($asramas as $asrama)
                                <option value="{{ $asrama->id }}" {{ (old('asrama_id', $evaluasiDiniyah->asrama_id) == $asrama->id) ? 'selected' : '' }}>
                                    {{ $asrama->nama_asrama }} ({{ $asrama->wilayah ?? 'Pusat' }})
                                </option>
                            @endforeach
                        </select>
                    @endif
                    @error('asrama_id')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 2. Santri / Anak Asuh --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Santri / Anak Asuh <span class="text-rose-500">*</span>
                    </label>
                    <select name="anak_asuh_id" id="anak_asuh_id" required 
                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('anak_asuh_id') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        @foreach($anakAsuhList as $anak)
                            <option value="{{ $anak->id }}" {{ (old('anak_asuh_id', $evaluasiDiniyah->anak_asuh_id) == $anak->id) ? 'selected' : '' }}>
                                {{ $anak->nama_lengkap }} (NIS: {{ $anak->nomor_induk }} • {{ $anak->jenjang_pendidikan }} {{ $anak->kelas }})
                            </option>
                        @endforeach
                    </select>
                    @error('anak_asuh_id')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 3. Jenis / Kategori Evaluasi --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Kategori Diniyah <span class="text-rose-500">*</span>
                    </label>
                    <select name="jenis_evaluasi" id="jenis_evaluasi" required 
                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('jenis_evaluasi') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori }}" {{ (old('jenis_evaluasi', $evaluasiDiniyah->jenis_evaluasi) === $kategori) ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_evaluasi')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 4. Tanggal Evaluasi --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Tanggal Ulangan / Ujian <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tanggal_evaluasi" id="tanggal_evaluasi" 
                           value="{{ old('tanggal_evaluasi', $evaluasiDiniyah->tanggal_evaluasi ? $evaluasiDiniyah->tanggal_evaluasi->format('Y-m-d') : '') }}" required 
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('tanggal_evaluasi') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    @error('tanggal_evaluasi')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 5. Materi / Judul Ulangan --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Materi / Judul Ulangan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="materi" id="materi" value="{{ old('materi', $evaluasiDiniyah->materi) }}" required 
                           placeholder="Contoh: Ulangan Harian Bab Thaharah & Wudhu" 
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('materi') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    @error('materi')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 6. Nilai Angka --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Nilai Ulangan (0 - 100) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.5" min="0" max="100" name="nilai" id="nilaiInput" 
                           value="{{ old('nilai', $evaluasiDiniyah->nilai) }}" required 
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('nilai') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                    @error('nilai')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 7. Predikat Capaian --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Predikat Capaian
                    </label>
                    <div class="flex items-center gap-2">
                        <select name="predikat" id="predikatSelect" 
                                class="flex-1 px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('predikat') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                            <option value="">Otomatis dari Nilai</option>
                            <option value="Mumtaz (A)" {{ old('predikat', $evaluasiDiniyah->predikat) === 'Mumtaz (A)' ? 'selected' : '' }}>Mumtaz (A) - Sangat Baik</option>
                            <option value="Jayyid Jiddan (B)" {{ old('predikat', $evaluasiDiniyah->predikat) === 'Jayyid Jiddan (B)' ? 'selected' : '' }}>Jayyid Jiddan (B) - Baik</option>
                            <option value="Jayyid (C)" {{ old('predikat', $evaluasiDiniyah->predikat) === 'Jayyid (C)' ? 'selected' : '' }}>Jayyid (C) - Cukup</option>
                            <option value="Maqbul (D)" {{ old('predikat', $evaluasiDiniyah->predikat) === 'Maqbul (D)' ? 'selected' : '' }}>Maqbul (D) - Kurang</option>
                            <option value="Perlu Bimbingan (E)" {{ old('predikat', $evaluasiDiniyah->predikat) === 'Perlu Bimbingan (E)' ? 'selected' : '' }}>Perlu Bimbingan (E)</option>
                        </select>
                        <span id="predikatPreview" class="px-3 py-2 rounded-xl text-xs font-bold border border-emerald-200 bg-emerald-50 text-emerald-800 shrink-0">
                            {{ $evaluasiDiniyah->predikat ?? '-' }}
                        </span>
                    </div>
                    @error('predikat')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 8. Catatan Pengajar / Evaluasi --}}
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Catatan Evaluasi / Catatan Pengajar
                    </label>
                    <textarea name="catatan_pengaju" id="catatan_pengaju" rows="3" 
                              class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('catatan_pengaju') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">{{ old('catatan_pengaju', $evaluasiDiniyah->catatan_pengaju) }}</textarea>
                    @error('catatan_pengaju')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('evaluasi-diniyah.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold transition-all">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/25 transition-all">
                    Perbarui Nilai Evaluasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const nilaiInput = document.getElementById('nilaiInput');
    const predikatSelect = document.getElementById('predikatSelect');
    const predikatPreview = document.getElementById('predikatPreview');

    function updatePredikat() {
        if (predikatSelect && predikatSelect.value !== '') {
            predikatPreview.textContent = predikatSelect.value;
            return;
        }

        const val = parseFloat(nilaiInput.value);
        if (isNaN(val)) {
            predikatPreview.textContent = '-';
            predikatPreview.className = 'px-3 py-2 rounded-xl text-xs font-bold border border-slate-200 bg-slate-50 text-slate-600 shrink-0';
            return;
        }

        let predikat = '';
        let colorClass = '';

        if (val >= 90) {
            predikat = 'Mumtaz (A)';
            colorClass = 'border-emerald-300 bg-emerald-50 text-emerald-800';
        } else if (val >= 80) {
            predikat = 'Jayyid Jiddan (B)';
            colorClass = 'border-blue-300 bg-blue-50 text-blue-800';
        } else if (val >= 70) {
            predikat = 'Jayyid (C)';
            colorClass = 'border-amber-300 bg-amber-50 text-amber-800';
        } else if (val >= 60) {
            predikat = 'Maqbul (D)';
            colorClass = 'border-orange-300 bg-orange-50 text-orange-800';
        } else {
            predikat = 'Perlu Bimbingan (E)';
            colorClass = 'border-rose-300 bg-rose-50 text-rose-800';
        }

        predikatPreview.textContent = predikat;
        predikatPreview.className = `px-3 py-2 rounded-xl text-xs font-bold border ${colorClass} shrink-0`;
    }

    if (nilaiInput) {
        nilaiInput.addEventListener('input', updatePredikat);
    }
    if (predikatSelect) {
        predikatSelect.addEventListener('change', updatePredikat);
    }
    updatePredikat();
});
</script>
@endsection
