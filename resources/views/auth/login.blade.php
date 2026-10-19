<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Masuk ke Sistem - Pendidikan Ansuh | Yayasan Rumah Harapan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', var(--font-sans, system-ui, sans-serif);
        }
    </style>
</head>
<body class="min-h-full bg-[#f8faf9] flex items-center justify-center p-4 sm:p-6 text-slate-800 antialiased selection:bg-emerald-500 selection:text-white">

    <div class="w-full max-w-[460px] mx-auto py-6 sm:py-8">
        {{-- Satu Kartu Login Terpadu --}}
        <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-lg shadow-slate-200/50 p-6 sm:p-8">
            
            {{-- Header: Logo, Nama Aplikasi, dan Yayasan --}}
            <div class="text-center mb-6">
                <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-500 items-center justify-center text-white shadow-md shadow-emerald-600/20 mb-3">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Pendidikan Ansuh</h1>
                <p class="text-xs sm:text-sm font-semibold text-emerald-700 mt-0.5">Yayasan Rumah Harapan</p>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-700">Masuk Akun Pengurus</h2>
                </div>
            </div>

            {{-- Pesan Sukses Logout --}}
            @if (session('success'))
                <div class="mb-5 p-3 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Alert Kesalahan Login --}}
            @if ($errors->any())
                <div class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200/80 text-rose-800 text-xs">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 shrink-0 text-rose-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="space-y-0.5 leading-relaxed">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Formulir Login --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-4" novalidate>
                @csrf

                {{-- Input Email --}}
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold text-slate-700">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               value="{{ old('email') }}"
                               placeholder="nama@rumahharapan.org" 
                               class="w-full h-11 pl-10 pr-3.5 rounded-xl border text-sm text-slate-800 transition-colors focus:outline-hidden focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 {{ $errors->has('email') ? 'border-rose-300 bg-rose-50/20' : 'border-slate-200 bg-slate-50/50 hover:border-slate-300' }}">
                    </div>
                </div>

                {{-- Input Password --}}
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-semibold text-slate-700">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="current-password" 
                               required 
                               placeholder="••••••••" 
                               class="w-full h-11 pl-10 pr-3.5 rounded-xl border text-sm text-slate-800 transition-colors focus:outline-hidden focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 {{ $errors->has('password') ? 'border-rose-300 bg-rose-50/20' : 'border-slate-200 bg-slate-50/50 hover:border-slate-300' }}">
                    </div>
                </div>

                {{-- Checkbox Remember Me --}}
                <div class="pt-0.5">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" 
                               name="remember" 
                               id="remember"
                               class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/30">
                        <span class="text-xs text-slate-600">Ingat saya pada perangkat ini</span>
                    </label>
                </div>

                {{-- Tombol Submit --}}
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full h-11 flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 active:scale-[0.99] text-white font-semibold text-sm shadow-md shadow-emerald-600/25 transition-all cursor-pointer">
                        <span>Masuk ke Sistem</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>

            {{-- Informasi Footer Kartu --}}
            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <p class="text-[11px] text-slate-400 leading-relaxed">
                    Sistem Khusus Pengurus Internal Yayasan Rumah Harapan.<br>
                    Pendaftaran akun dikelola terpusat oleh Administrator.
                </p>
            </div>
        </div>

        {{-- Footer Hak Cipta Ringkas --}}
        <div class="mt-6 text-center text-xs text-slate-400">
            <p>© {{ date('Y') }} Yayasan Rumah Harapan • Divisi Pendidikan Anak Asuh</p>
        </div>
    </div>

</body>
</html>
