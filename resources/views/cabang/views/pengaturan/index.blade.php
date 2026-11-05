{{-- BAGIAN: PENGURUS CABANG - Halaman Pengaturan Akun, Sistem & Bahasa --}}
@extends('cabang.layout.app')

@section('title', __('Pengaturan') . ' - ' . __('Cabang'))
@section('header_title', __('Pengaturan'))

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 shadow-xs animate-fade-in" role="alert">
            <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-emerald-900 leading-tight">{{ __('Perubahan berhasil disimpan!') }}</p>
                <p class="text-xs text-emerald-700 leading-tight mt-0.5">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 p-1 rounded-lg hover:bg-emerald-100 transition-colors cursor-pointer" aria-label="{{ __('Tutup') }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Notifikasi Error Validasi --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 shadow-xs" role="alert">
            <div class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-xs mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-rose-900 leading-tight">{{ __('Terjadi kesalahan saat memperbarui profil.') }}</p>
                <ul class="text-xs text-rose-700 list-disc list-inside mt-1 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 p-1 rounded-lg hover:bg-rose-100 transition-colors cursor-pointer" aria-label="{{ __('Tutup') }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Header Banner Pengaturan --}}
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 text-white shadow-xl"
         style="background: radial-gradient(ellipse 40% 70% at 85% 50%, rgba(217,119,6,.20) 0%, transparent 65%), linear-gradient(135deg, #064e3b 0%, #056a4c 55%, #044a3f 100%);">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-semibold text-emerald-100 mb-2">
                    <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                    <span>{{ __('Pengaturan & Akun') }}</span>
                </div>
                <h1 class="text-xl sm:text-3xl font-extrabold tracking-tight text-white mb-2">
                    {{ __('Pengaturan Akun & Sistem') }}
                </h1>
                <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed max-w-2xl">
                    {{ __('Sesuaikan preferensi bahasa, tampilan antarmuka, dan zona waktu cabang Anda') }}
                </p>
            </div>
            <div class="hidden sm:flex items-center justify-center w-14 h-14 rounded-2xl bg-white/10 border border-white/20 text-white shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Form Pengaturan Utama --}}
    <form action="{{ route('cabang.pengaturan.update') }}" method="POST" id="settings-form" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- 1. PREFERENSI BAHASA (i18n) --}}
        <div class="bg-white rounded-2xl p-6 border border-emerald-100/90 shadow-xs">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">{{ __('Preferensi Bahasa') }}</h2>
                    <p class="text-xs text-slate-400">{{ __('Pilih bahasa tampilan untuk portal cabang') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Opsi Bahasa Indonesia --}}
                <label class="relative flex items-start p-4 rounded-2xl border-2 transition-all cursor-pointer group {{ $currentLocale === 'id' ? 'border-emerald-600 bg-emerald-50/50 shadow-xs' : 'border-slate-200 hover:border-emerald-200 hover:bg-slate-50/60' }}">
                    <div class="flex items-center h-5 mr-3 mt-0.5">
                        <input type="radio" 
                               name="locale" 
                               value="id" 
                               {{ $currentLocale === 'id' ? 'checked' : '' }}
                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">🇮🇩</span>
                            <span class="text-xs font-bold text-slate-900">{{ __('Bahasa Indonesia') }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 shrink-0">Default</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                            {{ __('Bahasa resmi dan utama dalam seluruh operasional sistem.') }}
                        </p>
                    </div>
                </label>

                {{-- Opsi English --}}
                <label class="relative flex items-start p-4 rounded-2xl border-2 transition-all cursor-pointer group {{ $currentLocale === 'en' ? 'border-emerald-600 bg-emerald-50/50 shadow-xs' : 'border-slate-200 hover:border-emerald-200 hover:bg-slate-50/60' }}">
                    <div class="flex items-center h-5 mr-3 mt-0.5">
                        <input type="radio" 
                               name="locale" 
                               value="en" 
                               {{ $currentLocale === 'en' ? 'checked' : '' }}
                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">🇬🇧</span>
                            <span class="text-xs font-bold text-slate-900">{{ __('English') }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                            {{ __('English interface support for international users.') }}
                        </p>
                    </div>
                </label>
            </div>
        </div>

        {{-- 2. TEMA ANTARMUKA (Theme) --}}
        <div class="bg-white rounded-2xl p-6 border border-emerald-100/90 shadow-xs">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">{{ __('Tema Antarmuka') }}</h2>
                    <p class="text-xs text-slate-400">{{ __('Sesuaikan preferensi pencahayaan tampilan layar') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Mode Terang --}}
                <label class="relative flex flex-col p-4 rounded-2xl border-2 transition-all cursor-pointer group {{ $currentTheme === 'light' ? 'border-emerald-600 bg-emerald-50/50 shadow-xs' : 'border-slate-200 hover:border-emerald-200 hover:bg-slate-50/60' }}">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <input type="radio" 
                               name="theme" 
                               value="light" 
                               {{ $currentTheme === 'light' ? 'checked' : '' }}
                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                    </div>
                    <span class="text-xs font-bold text-slate-900">{{ __('Mode Terang') }}</span>
                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                        {{ __('Tampilan cerah dengan kontras bersih untuk kenyamanan kerja di siang hari.') }}
                    </p>
                </label>

                {{-- Mode Gelap --}}
                <label class="relative flex flex-col p-4 rounded-2xl border-2 transition-all cursor-pointer group {{ $currentTheme === 'dark' ? 'border-emerald-600 bg-emerald-50/50 shadow-xs' : 'border-slate-200 hover:border-emerald-200 hover:bg-slate-50/60' }}">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-xl bg-slate-800 text-slate-200 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </div>
                        <input type="radio" 
                               name="theme" 
                               value="dark" 
                               {{ $currentTheme === 'dark' ? 'checked' : '' }}
                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                    </div>
                    <span class="text-xs font-bold text-slate-900">{{ __('Mode Gelap') }}</span>
                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                        {{ __('Tampilan redup untuk kenyamanan mata di malam hari.') }}
                    </p>
                </label>

                {{-- Ikuti Sistem --}}
                <label class="relative flex flex-col p-4 rounded-2xl border-2 transition-all cursor-pointer group {{ $currentTheme === 'system' ? 'border-emerald-600 bg-emerald-50/50 shadow-xs' : 'border-slate-200 hover:border-emerald-200 hover:bg-slate-50/60' }}">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="radio" 
                               name="theme" 
                               value="system" 
                               {{ $currentTheme === 'system' ? 'checked' : '' }}
                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                    </div>
                    <span class="text-xs font-bold text-slate-900">{{ __('Ikuti Sistem') }}</span>
                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                        {{ __('Menyesuaikan otomatis dengan preferensi tema perangkat Anda.') }}
                    </p>
                </label>
            </div>
        </div>

        {{-- 3. ZONA WAKTU & FORMAT TANGGAL --}}
        <div class="bg-white rounded-2xl p-6 border border-emerald-100/90 shadow-xs">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">{{ __('Zona Waktu & Tanggal') }}</h2>
                    <p class="text-xs text-slate-400">{{ __('Konfigurasi penanggalan dan wilayah waktu operasional') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Zona Waktu --}}
                <div>
                    <label for="timezone" class="block text-xs font-bold text-slate-700 mb-1.5">
                        {{ __('Zona Waktu') }} <span class="text-rose-500">*</span>
                    </label>
                    <select name="timezone" 
                            id="timezone" 
                            required
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all cursor-pointer">
                        @foreach ($timezones as $tzKey => $tzLabel)
                            <option value="{{ $tzKey }}" {{ $currentTimezone === $tzKey ? 'selected' : '' }}>
                                {{ $tzLabel }} ({{ $tzKey }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1.5">
                        {{ __('Pilih zona waktu operasional cabang untuk pencatatan kegiatan') }}
                    </p>
                </div>

                {{-- Format Tanggal --}}
                <div>
                    <label for="date_format" class="block text-xs font-bold text-slate-700 mb-1.5">
                        {{ __('Format Tanggal') }} <span class="text-rose-500">*</span>
                    </label>
                    <select name="date_format" 
                            id="date_format" 
                            required
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all cursor-pointer">
                        @foreach ($dateFormats as $dfKey => $dfLabel)
                            <option value="{{ $dfKey }}" {{ $currentDateFormat === $dfKey ? 'selected' : '' }}>
                                {{ $dfLabel }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1.5">
                        {{ __('Pilih format tampilan penanggalan yang diinginkan') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Bar Tombol Simpan --}}
        <div class="flex items-center justify-between p-4 bg-emerald-50/80 rounded-2xl border border-emerald-200/80">
            <span class="text-xs text-slate-600 font-medium">
                {{ __('Perubahan bahasa dan preferensi tampilan akan langsung disimpan pada akun Anda.') }}
            </span>

            <button type="submit" 
                    id="btn-save-settings"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/25 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                <span>{{ __('Simpan Pengaturan') }}</span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Interaktivitas pemilihan radio kartu (tampilan visual dinamis)
        const localeInputs = document.querySelectorAll('input[name="locale"]');
        localeInputs.forEach(input => {
            input.addEventListener('change', function() {
                try {
                    localStorage.setItem('app_locale', this.value);
                } catch(e) {}
            });
        });

        const themeInputs = document.querySelectorAll('input[name="theme"]');
        themeInputs.forEach(input => {
            input.addEventListener('change', function() {
                try {
                    localStorage.setItem('app_theme', this.value);
                    if (this.value === 'dark' || (this.value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                } catch(e) {}
            });
        });

        // Sinkronisasi saat form disubmit
        document.getElementById('settings-form')?.addEventListener('submit', function() {
            const selectedLocale = document.querySelector('input[name="locale"]:checked')?.value;
            const selectedTheme = document.querySelector('input[name="theme"]:checked')?.value;
            if (selectedLocale) localStorage.setItem('app_locale', selectedLocale);
            if (selectedTheme) localStorage.setItem('app_theme', selectedTheme);
        });
    });
</script>
@endpush
@endsection

