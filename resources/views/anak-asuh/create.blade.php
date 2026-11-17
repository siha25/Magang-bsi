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
            Daftarkan anak asuh baru untuk dibina di asrama Yayasan Rumah Harapan.
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
            <h3 class="text-base font-bold text-slate-900">Biodata Pokok Anak Asuh</h3>
            <p class="text-xs text-slate-500 mt-0.5">Pastikan nomor induk unik dan asrama yang dipilih valid.</p>
        </div>

        <form method="POST" action="{{ route('anak-asuh.store') }}" class="p-5 sm:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Nomor Induk --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="nomor_induk" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Nomor Induk <span class="text-xs font-normal text-slate-400 lowercase">(otomatis)</span>
                        </label>
                        <span id="nomor_induk_badge" class="hidden text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Pratinjau Otomatis
                        </span>
                    </div>
                    <div class="relative">
                        <input type="text" 
                               id="nomor_induk" 
                               name="nomor_induk" 
                               value="{{ old('nomor_induk') }}"
                               readonly
                               placeholder="Terisi otomatis setelah nama &amp; asrama dipilih" 
                               class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-100 text-slate-700 border @error('nomor_induk') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none cursor-not-allowed font-mono tracking-wide transition-all shadow-inner">
                        <div id="nomor_induk_spinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                            <svg class="animate-spin h-4 w-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                        </div>
                    </div>
                    <p id="nomor_induk_hint" class="text-[11px] text-slate-400">
                        Format: <code class="font-mono bg-slate-100 px-1 py-0.5 rounded text-slate-600">YYAAANN</code> (contoh: <span class="font-mono text-emerald-700">2600101</span>). Dihitung otomatis tanpa tanda setrip.
                    </p>
                    <p id="nomor_induk_error" class="hidden text-xs text-rose-600 font-medium"></p>
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
                          placeholder="Catatan riwayat khusus, kondisi kesehatan, atau latar belakang anak asuh..." 
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

{{-- Script Penomoran Induk Otomatis --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const namaInput = document.getElementById('nama_lengkap');
    const asramaSelect = document.getElementById('asrama_id');
    const tanggalMasukInput = document.getElementById('tanggal_masuk');
    const nomorIndukInput = document.getElementById('nomor_induk');
    const nomorIndukSpinner = document.getElementById('nomor_induk_spinner');
    const nomorIndukBadge = document.getElementById('nomor_induk_badge');
    const nomorIndukHint = document.getElementById('nomor_induk_hint');
    const nomorIndukError = document.getElementById('nomor_induk_error');

    let debounceTimer = null;
    let abortController = null;

    const defaultHintHtml = 'Format: <code class="font-mono bg-slate-100 px-1 py-0.5 rounded text-slate-600">YYAAANN</code> (contoh: <span class="font-mono text-emerald-700">2600101</span>). Dihitung otomatis tanpa tanda setrip.';

    function setUiState(options) {
        const loading = options.loading ?? false;
        const error = options.error ?? null;
        const preview = options.preview ?? null;

        if (loading) {
            nomorIndukSpinner?.classList.remove('hidden');
            nomorIndukError?.classList.add('hidden');
        } else {
            nomorIndukSpinner?.classList.add('hidden');
        }

        if (error) {
            nomorIndukBadge?.classList.add('hidden');
            if (nomorIndukError) {
                nomorIndukError.textContent = error;
                nomorIndukError.classList.remove('hidden');
            }
        } else {
            nomorIndukError?.classList.add('hidden');
        }

        if (preview) {
            if (nomorIndukInput) {
                nomorIndukInput.value = preview.nomor_induk;
            }
            nomorIndukBadge?.classList.remove('hidden');
            if (nomorIndukHint) {
                nomorIndukHint.innerHTML = `Pratinjau aktif: <strong class="font-mono text-emerald-700">${preview.nomor_induk}</strong> (Tahun 20${preview.yy}, Asrama #${preview.kode_asrama_digits}, Urutan ke-${preview.nomor_urut})`;
            }
        } else if (!loading && !error) {
            nomorIndukBadge?.classList.add('hidden');
            if (nomorIndukHint) {
                nomorIndukHint.innerHTML = defaultHintHtml;
            }
        }
    }

    async function fetchPreview() {
        const nama = namaInput ? namaInput.value.trim() : '';
        const asramaId = asramaSelect ? asramaSelect.value : '';
        const tanggalMasuk = tanggalMasukInput ? tanggalMasukInput.value : '';

        // Syarat: Nama Lengkap sudah terisi DAN Asrama Binaan sudah dipilih DAN Tanggal Masuk Asrama ada
        if (!nama || !asramaId || !tanggalMasuk) {
            if (abortController) {
                abortController.abort();
                abortController = null;
            }
            if (nomorIndukInput) {
                nomorIndukInput.value = '';
            }
            setUiState({ loading: false });
            return;
        }

        if (abortController) {
            abortController.abort();
        }
        abortController = new AbortController();

        setUiState({ loading: true });

        try {
            const url = new URL("{{ route('anak-asuh.preview-nomor-induk') }}", window.location.origin);
            url.searchParams.set('asrama_id', asramaId);
            url.searchParams.set('tanggal_masuk', tanggalMasuk);

            const res = await fetch(url.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                signal: abortController.signal,
            });

            const data = await res.json();

            if (!res.ok) {
                const errMsg = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Gagal memuat pratinjau nomor induk.');
                setUiState({ loading: false, error: errMsg });
                return;
            }

            setUiState({ loading: false, preview: data });
        } catch (err) {
            if (err.name === 'AbortError') {
                return;
            }
            setUiState({ loading: false, error: 'Koneksi bermasalah saat memuat pratinjau nomor induk.' });
        }
    }

    function schedulePreview() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchPreview, 300);
    }

    if (namaInput) {
        namaInput.addEventListener('input', schedulePreview);
    }
    if (asramaSelect) {
        asramaSelect.addEventListener('change', schedulePreview);
    }
    if (tanggalMasukInput) {
        tanggalMasukInput.addEventListener('change', schedulePreview);
        tanggalMasukInput.addEventListener('input', schedulePreview);
    }

    // Eksekusi pratinjau pada pemuatan awal jika input sudah terisi
    if (namaInput?.value.trim() && asramaSelect?.value && tanggalMasukInput?.value) {
        fetchPreview();
    }
});
</script>
@endsection
