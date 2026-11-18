{{-- BAGIAN: PENGURUS PUSAT - Halaman Profil Akun --}}
@extends('pusat.layout.app')

@section('title', __('Profil Akun'))
@section('header_title', __('Profil Akun'))

@section('content')
@php
    $currentLocale = session('locale', $user?->locale ?? app()->getLocale());
    $currentTheme = session('theme', $user?->theme ?? 'light');
    $currentTimezone = session('timezone', $user?->timezone ?? 'Asia/Jakarta');
    $currentDateFormat = session('date_format', $user?->date_format ?? 'd/m/Y');

    $themeLabel = match ($currentTheme) {
        'dark' => __('Mode Gelap'),
        'system' => __('Ikuti Sistem'),
        default => __('Mode Terang'),
    };

    $timezoneLabel = match ($currentTimezone) {
        'Asia/Makassar' => 'WITA (Asia/Makassar)',
        'Asia/Jayapura' => 'WIT (Asia/Jayapura)',
        default => 'WIB (Asia/Jakarta)',
    };
@endphp

<div class="space-y-6 max-w-5xl mx-auto">

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 shadow-xs" role="alert">
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

    {{-- Header Banner Profil Akun --}}
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 text-white shadow-xl"
         style="background: radial-gradient(ellipse 40% 70% at 85% 50%, rgba(217,119,6,.20) 0%, transparent 65%), linear-gradient(135deg, #064e3b 0%, #056a4c 55%, #044a3f 100%);">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/15 backdrop-blur-md border border-white/25 flex items-center justify-center text-white font-extrabold text-2xl sm:text-3xl shadow-inner shrink-0">
                    {{ strtoupper(substr($user->name ?? 'AD', 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/20 text-emerald-50 border border-white/25">
                            {{ $user->role_label }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->status_aktif ? 'bg-emerald-500/30 text-emerald-100 border border-emerald-300/40' : 'bg-rose-500/30 text-rose-100 border border-rose-300/40' }}">
                            {{ $user->status_aktif ? __('Aktif') : __('Nonaktif') }}
                        </span>
                        @if ($user->isPrimaryAdmin())
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/30 text-amber-100 border border-amber-300/40">
                                {{ __('Akun Utama') }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-xl sm:text-3xl font-extrabold tracking-tight text-white truncate">
                        {{ $user->name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-emerald-100/90 mt-1 truncate">
                        {{ $user->email }}
                    </p>
                </div>
            </div>

            <a href="{{ route('pusat.pengaturan.index') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/25 text-white text-xs font-semibold transition-all shrink-0 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ __('Pengaturan') }}</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri (2 Kolom): Form Profil & Ubah Password --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Form 1: Informasi Profil Akun --}}
            <div class="bg-white rounded-2xl p-6 border border-emerald-100/90 shadow-xs">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">{{ __('Informasi Profil Akun') }}</h2>
                        <p class="text-xs text-slate-400">{{ __('Perbarui nama tampilan dan alamat email akun Anda') }}</p>
                    </div>
                </div>

                <form action="{{ route('pusat.profil.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                                {{ __('Nama Lengkap') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   maxlength="255"
                                   class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                            @error('name')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                                {{ __('Alamat Email') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   maxlength="255"
                                   class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                            @error('email')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">{{ __('Peran / Hak Akses') }}</label>
                        <input type="text"
                               value="{{ $user->role_label }}"
                               readonly
                               disabled
                               class="w-full sm:w-1/2 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs font-semibold text-slate-500 cursor-not-allowed">
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm shadow-emerald-600/20 transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ __('Simpan Perubahan') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Form 2: Keamanan & Ubah Password --}}
            <div class="bg-white rounded-2xl p-6 border border-emerald-100/90 shadow-xs">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">{{ __('Keamanan & Ubah Password') }}</h2>
                        <p class="text-xs text-slate-400">{{ __('Gunakan kombinasi password minimal 8 karakter untuk menjaga keamanan akun') }}</p>
                    </div>
                </div>

                <form action="{{ route('pusat.profil.update-password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                            {{ __('Password Saat Ini') }} <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password"
                                   name="current_password"
                                   id="current_password"
                                   required
                                   autocomplete="current-password"
                                   class="w-full rounded-xl border border-slate-200 pl-3.5 pr-10 py-2.5 text-xs text-slate-800 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                            <button type="button" onclick="togglePwdField('current_password')" aria-label="{{ __('Tampilkan atau sembunyikan kata sandi') }}" class="absolute inset-y-0 right-0 px-3 text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">
                                {{ __('Password Baru') }} <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password"
                                       name="password"
                                       id="password"
                                       required
                                       minlength="8"
                                       autocomplete="new-password"
                                       class="w-full rounded-xl border border-slate-200 pl-3.5 pr-10 py-2.5 text-xs text-slate-800 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                                <button type="button" onclick="togglePwdField('password')" aria-label="{{ __('Tampilkan atau sembunyikan kata sandi') }}" class="absolute inset-y-0 right-0 px-3 text-slate-400 hover:text-slate-600 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">
                                {{ __('Konfirmasi Password Baru') }} <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       required
                                       minlength="8"
                                       autocomplete="new-password"
                                       class="w-full rounded-xl border border-slate-200 pl-3.5 pr-10 py-2.5 text-xs text-slate-800 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                                <button type="button" onclick="togglePwdField('password_confirmation')" aria-label="{{ __('Tampilkan atau sembunyikan kata sandi') }}" class="absolute inset-y-0 right-0 px-3 text-slate-400 hover:text-slate-600 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs shadow-sm shadow-orange-500/20 transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <span>{{ __('Perbarui Password') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Kolom Kanan: Ringkasan Akun & Preferensi --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-emerald-100/90 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100">{{ __('Ringkasan Akun') }}</h3>

                <dl class="space-y-3 text-xs">
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-slate-500">{{ __('Status Akun') }}</dt>
                        <dd class="font-bold {{ $user->status_aktif ? 'text-emerald-700' : 'text-rose-600' }}">
                            {{ $user->status_aktif ? __('Aktif') : __('Nonaktif') }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-slate-500">{{ __('Terdaftar Sejak') }}</dt>
                        <dd class="font-semibold text-slate-800">
                            {{ $user->created_at?->timezone($currentTimezone)->translatedFormat($currentDateFormat) ?? '-' }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-slate-500">{{ __('Bahasa') }}</dt>
                        <dd class="font-semibold text-slate-800 uppercase">{{ $currentLocale }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-slate-500">{{ __('Tema Antarmuka') }}</dt>
                        <dd class="font-semibold text-slate-800">{{ $themeLabel }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-slate-500">{{ __('Zona Waktu') }}</dt>
                        <dd class="font-semibold text-slate-800">{{ $timezoneLabel }}</dd>
                    </div>
                </dl>

                <div class="pt-3 border-t border-slate-100">
                    <a href="{{ route('pusat.pengaturan.index') }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-semibold transition-colors">
                        <span>{{ __('Ubah Preferensi Sistem') }}</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function togglePwdField(id) {
        const input = document.getElementById(id);
        if (!input) return;
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
@endpush
@endsection
