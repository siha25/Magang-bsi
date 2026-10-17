@extends('layouts.app')

@section('title', 'Tambah Anak Asuh Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 sm:space-y-8">

    {{-- Header Halaman & Breadcrumb --}}
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
            <a href="{{ route('dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('anak-asuh.index') }}" class="hover:text-emerald-700 transition-colors">Data Anak Asuh</a>
            <span>/</span>
            <span class="text-emerald-800 font-semibold">Tambah Anak Asuh</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            Tambah Anak Asuh Baru
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Daftarkan santri anak asuh baru untuk dibina di asrama Yayasan Rumah Harapan.
        </p>
    </div>

    {{-- Error Alert Ringkasan --}}
    @if(isset($errors) && $errors->any())
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm shadow-xs">
        <div class="flex items-center gap-2 font-bold mb-1">
            <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Terdapat beberapa kesalahan pengisian formulir:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 pl-1 text-rose-700 text-xs">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Formulir Tambah Anak Asuh --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-8 border-b border-slate-100 bg-gradient-to-r from-emerald-50/40 via-white to-white">
            <h3 class="text-base font-bold text-slate-900">Biodata Pokok Santri / Anak Asuh</h3>
            <p class="text-xs text-slate-500 mt-0.5">Pastikan nomor induk unik dan asrama yang dipilih valid.</p>
        </div>

        <form method="POST" action="{{ route('anak-asuh.store') }}" class="p-5 sm:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Nomor Induk --}}
                <div class="space-y-1.5">
                    <label for="nomor_induk" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Nomor Induk <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="nomor_induk" 
                           name="nomor_induk" 
                           value="{{ old('nomor_induk') }}"
                           required
                           placeholder="Contoh: AA-2026-001" 
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('nomor_induk') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all font-mono">
                    <p class="text-[11px] text-slate-400">Nomor induk unik identitas anak asuh.</p>
                    @error('nomor_induk')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Lengkap --}}
                <div class="space-y-1.5">
                    <label for="nama_lengkap" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama_lengkap" 
                           name="nama_lengkap" 
                           value="{{ old('nama_lengkap') }}"
                           required
                           placeholder="Contoh: Muhammad Fatih Ridwan" 
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('nama_lengkap') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">
                    @error('nama_lengkap')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Jenis Kelamin <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white cursor-pointer transition-all">
                            <input type="radio" 
                                   name="jenis_kelamin" 
                                   value="L" 
                                   {{ old('jenis_kelamin', 'L') === 'L' ? 'checked' : '' }}
                                   class="text-emerald-600 focus:ring-emerald-500">
                            <span class="text-xs sm:text-sm font-semibold text-slate-800">Laki-laki (L)</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white cursor-pointer transition-all">
                            <input type="radio" 
                                   name="jenis_kelamin" 
                                   value="P" 
                                   {{ old('jenis_kelamin') === 'P' ? 'checked' : '' }}
                                   class="text-emerald-600 focus:ring-emerald-500">
                            <span class="text-xs sm:text-sm font-semibold text-slate-800">Perempuan (P)</span>
                        </label>
                    </div>
                    @error('jenis_kelamin')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Asrama Pilihan (Hanya Asrama Aktif untuk Baru) --}}
                <div class="space-y-1.5">
                    <label for="asrama_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Asrama Binaan <span class="text-rose-500">*</span>
                    </label>
                    <select id="asrama_id" 
                            name="asrama_id" 
                            required
                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('asrama_id') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">
                        <option value="">-- Pilih Asrama Aktif --</option>
                        @foreach($asramas as $asrama)
                            <option value="{{ $asrama->id }}" {{ old('asrama_id') == $asrama->id ? 'selected' : '' }}>
                                {{ $asrama->nama_asrama }} ({{ $asrama->kode_asrama }}) {{ $asrama->wilayah ? '— ' . $asrama->wilayah : '' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400">Hanya asrama yang berstatus aktif yang ditampilkan.</p>
                    @error('asrama_id')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tempat Lahir --}}
                <div class="space-y-1.5">
                    <label for="tempat_lahir" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Tempat Lahir
                    </label>
                    <input type="text" 
                           id="tempat_lahir" 
                           name="tempat_lahir" 
                           value="{{ old('tempat_lahir') }}"
                           placeholder="Contoh: Bandung" 
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('tempat_lahir') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">
                    @error('tempat_lahir')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Lahir --}}
                <div class="space-y-1.5">
                    <label for="tanggal_lahir" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Tanggal Lahir
                    </label>
                    <input type="date" 
                           id="tanggal_lahir" 
                           name="tanggal_lahir" 
                           value="{{ old('tanggal_lahir') }}"
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('tanggal_lahir') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">
                    @error('tanggal_lahir')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenjang Pendidikan --}}
                <div class="space-y-1.5">
                    <label for="jenjang_pendidikan" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Jenjang Pendidikan <span class="text-rose-500">*</span>
                    </label>
                    <select id="jenjang_pendidikan" 
                            name="jenjang_pendidikan" 
                            required
                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('jenjang_pendidikan') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">
                        <option value="">-- Pilih Jenjang --</option>
                        <option value="SD" {{ old('jenjang_pendidikan') === 'SD' ? 'selected' : '' }}>SD / MI Sederajat</option>
                        <option value="SMP" {{ old('jenjang_pendidikan') === 'SMP' ? 'selected' : '' }}>SMP / MTs Sederajat</option>
                        <option value="SMA" {{ old('jenjang_pendidikan') === 'SMA' ? 'selected' : '' }}>SMA / SMK / MA Sederajat</option>
                    </select>
                    @error('jenjang_pendidikan')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kelas --}}
                <div class="space-y-1.5">
                    <label for="kelas" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Kelas
                    </label>
                    <input type="text" 
                           id="kelas" 
                           name="kelas" 
                           value="{{ old('kelas') }}"
                           placeholder="Contoh: Kelas 4, Kelas 7, Kelas 10" 
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('kelas') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">
                    @error('kelas')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Masuk --}}
                <div class="space-y-1.5">
                    <label for="tanggal_masuk" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Tanggal Masuk Asrama
                    </label>
                    <input type="date" 
                           id="tanggal_masuk" 
                           name="tanggal_masuk" 
                           value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('tanggal_masuk') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">
                    <p class="text-[11px] text-slate-400">Tanggal anak asuh resmi mulai bernaung di asrama.</p>
                    @error('tanggal_masuk')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Aktif --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Status Keaktifan
                    </label>
                    <div class="pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="status_aktif" 
                                   value="1" 
                                   class="sr-only peer" 
                                   {{ old('status_aktif', '1') == '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ml-3 text-xs sm:text-sm font-medium text-slate-700">
                                Aktif sebagai anak asuh binaan
                            </span>
                        </label>
                    </div>
                    @error('status_aktif')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Catatan --}}
            <div class="space-y-1.5">
                <label for="catatan" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                    Catatan Tambahan
                </label>
                <textarea id="catatan" 
                          name="catatan" 
                          rows="3" 
                          placeholder="Catatan riwayat khusus, kondisi kesehatan, atau latar belakang santri..." 
                          class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('catatan') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('anak-asuh.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 active:scale-95 text-white text-xs sm:text-sm font-semibold shadow-md shadow-orange-500/25 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Data Anak Asuh</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
