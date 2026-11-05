{{-- BAGIAN: PENGURUS CABANG - Halaman Profil Cabang --}}
@extends('cabang.layout.app')

@section('title', __('Profil Cabang') . ' - ' . ($cabang?->nama_asrama ?? ''))
@section('header_title', __('Profil Cabang'))

@section('content')
<div class="space-y-6">

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

    {{-- Header Banner Profil Cabang --}}
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 text-white shadow-xl"
         style="background: radial-gradient(ellipse 40% 70% at 85% 50%, rgba(217,119,6,.20) 0%, transparent 65%), linear-gradient(135deg, #064e3b 0%, #056a4c 55%, #044a3f 100%);">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            
            <div class="flex items-start sm:items-center gap-4 sm:gap-5">
                {{-- Logo Inisial Cabang --}}
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white font-extrabold text-2xl sm:text-3xl shadow-inner shrink-0">
                    {{ strtoupper(substr($cabang->nama_asrama ?? 'CB', 0, 2)) }}
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-white/20 text-emerald-100 border border-white/25">
                            {{ $cabang->kode_asrama ?? 'CBG' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $cabang->status_aktif ? 'bg-emerald-500/30 text-emerald-200 border border-emerald-400/40' : 'bg-rose-500/30 text-rose-200 border border-rose-400/40' }}">
                            {{ $cabang->status_aktif ? __('Aktif') : __('Nonaktif') }}
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight truncate">
                        {{ $cabang->nama_asrama }}
                    </h1>

                    <p class="text-xs sm:text-sm text-emerald-100/85 mt-1 flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="truncate">{{ $cabang->wilayah }} &bull; {{ __('Santri Terdaftar') }}: {{ $cabang->anakAsuh()->count() }}</span>
                    </p>
                </div>
            </div>

            {{-- Tombol Edit Profil --}}
            <div class="flex items-center gap-3 shrink-0">
                <button type="button" 
                        onclick="openEditModal()"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-orange-500 hover:bg-orange-600 active:scale-95 text-white font-semibold text-xs sm:text-sm shadow-lg shadow-orange-500/25 transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>{{ __('Edit Profil') }}</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Grid Rincian Informasi Profil Cabang --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri (2 Kolom): Detail Operasional & Lokasi --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Card 1: Informasi Utama & Penanggung Jawab --}}
            <div class="bg-white rounded-2xl p-6 border border-emerald-100/90 shadow-xs">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">{{ __('Identitas & Legalitas') }}</h2>
                            <p class="text-xs text-slate-400">{{ __('Data dan identitas operasional cabang Anda yang terdaftar pada sistem') }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-mono font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                        {{ $cabang->kode_asrama }}
                    </span>
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-xs">
                    <div>
                        <dt class="font-medium text-slate-400 mb-1">{{ __('Nama Cabang') }}</dt>
                        <dd class="font-bold text-slate-800 text-sm">{{ $cabang->nama_asrama }}</dd>
                    </div>

                    <div>
                        <dt class="font-medium text-slate-400 mb-1">{{ __('Kode Cabang') }}</dt>
                        <dd class="font-bold text-slate-800 text-sm font-mono">{{ $cabang->kode_asrama }}</dd>
                    </div>

                    <div>
                        <dt class="font-medium text-slate-400 mb-1">{{ __('Kota / Wilayah') }}</dt>
                        <dd class="font-bold text-slate-800 text-sm">{{ $cabang->wilayah }}</dd>
                    </div>

                    <div>
                        <dt class="font-medium text-slate-400 mb-1">{{ __('Status Operasional') }}</dt>
                        <dd>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $cabang->status_aktif ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $cabang->status_aktif ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ $cabang->status_aktif ? __('Aktif') : __('Nonaktif') }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="font-medium text-slate-400 mb-1">{{ __('Kepala Cabang') }} / {{ __('Penanggung Jawab') }}</dt>
                        <dd class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>{{ $cabang->penanggung_jawab }}</span>
                        </dd>
                    </div>

                    <div>
                        <dt class="font-medium text-slate-400 mb-1">{{ __('Jam Operasional') }}</dt>
                        <dd class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $cabang->jam_operasional ?? '08:00 - 17:00 WIB' }}</span>
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Card 2: Alamat & Lokasi Lengkap --}}
            <div class="bg-white rounded-2xl p-6 border border-emerald-100/90 shadow-xs">
                <div class="flex items-center gap-2.5 pb-4 mb-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">{{ __('Lokasi & Operasional') }}</h2>
                        <p class="text-xs text-slate-400">{{ __('Alamat fisik dan lokasi operasional asrama cabang') }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Alamat Lengkap') }}</p>
                    <p class="text-sm text-slate-800 leading-relaxed">
                        {{ $cabang->alamat ?: __('Belum diisi') }}
                    </p>
                </div>
            </div>

            {{-- Card 3: Akun Pengurus Terdaftar --}}
            <div class="bg-white rounded-2xl p-6 border border-emerald-100/90 shadow-xs">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">{{ __('Akun Pengurus Terdaftar') }}</h2>
                            <p class="text-xs text-slate-400">{{ __('Pengguna yang memiliki akses pengelolaan pada cabang ini') }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-500">{{ $cabang->users->count() }} {{ __('Akun') }}</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($cabang->users as $u)
                        <div class="py-3 first:pt-0 last:pb-0 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ $u->name }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">{{ $u->email }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 shrink-0">
                                {{ ucfirst(str_replace('_', ' ', $u->role)) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic py-2">{{ __('Belum ada akun pengurus tambahan.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan (1 Kolom): Kontak & Quick Info --}}
        <div class="space-y-6">

            {{-- Kontak Cabang --}}
            <div class="bg-white rounded-2xl p-6 border border-emerald-100/90 shadow-xs">
                <div class="flex items-center gap-2.5 pb-4 mb-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">{{ __('Kontak & Penanggung Jawab') }}</h2>
                        <p class="text-xs text-slate-400">{{ __('Saluran komunikasi resmi') }}</p>
                    </div>
                </div>

                <div class="space-y-3.5 text-xs">
                    <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-100">
                        <span class="text-slate-400 font-medium block text-[11px] mb-0.5">{{ __('Nomor Telepon') }}</span>
                        <div class="flex items-center gap-2 text-slate-900 font-bold text-sm">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>{{ $cabang->nomor_telepon ?: '-' }}</span>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-100">
                        <span class="text-slate-400 font-medium block text-[11px] mb-0.5">{{ __('Email Cabang') }}</span>
                        <div class="flex items-center gap-2 text-slate-900 font-bold text-sm truncate">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                            <span class="truncate">{{ $cabang->email ?: ($cabang->users->first()?->email ?: '-') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Links / Bantuan --}}
            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-md">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-white mb-1">{{ __('Bantuan & Koordinasi') }}</h3>
                <p class="text-xs text-emerald-100/90 leading-relaxed mb-4">
                    {{ __('Untuk perubahan nama legalitas atau kode cabang resmi, silakan hubungi Administrator Pusat.') }}
                </p>
                <a href="{{ route('cabang.pengaturan.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-emerald-100 hover:text-white transition-colors">
                    <span>{{ __('Buka Pengaturan Cabang') }}</span>
                    <span>&rarr;</span>
                </a>
            </div>

        </div>
    </div>
</div>

{{-- MODAL EDIT PROFIL CABANG --}}
<div id="modal-edit-profil" 
     class="hidden fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" 
         onclick="closeEditModal()"></div>

    {{-- Dialog Box --}}
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-emerald-100">
            
            {{-- Modal Header --}}
            <div class="px-6 py-5 bg-gradient-to-r from-emerald-50/80 to-white border-b border-emerald-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 leading-tight" id="modal-title">
                            {{ __('Edit Profil Cabang') }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ __('Perbarui data operasional dan kontak cabang') }}
                        </p>
                    </div>
                </div>

                <button type="button" 
                        onclick="closeEditModal()" 
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer"
                        aria-label="{{ __('Tutup') }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Form Edit Profil --}}
            <form action="{{ route('cabang.profil.update') }}" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Nama Cabang --}}
                    <div>
                        <label for="nama_asrama" class="block text-xs font-bold text-slate-700 mb-1">
                            {{ __('Nama Cabang') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="nama_asrama" 
                               id="nama_asrama" 
                               value="{{ old('nama_asrama', $cabang->nama_asrama) }}"
                               required
                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        @error('nama_asrama')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kota / Wilayah --}}
                    <div>
                        <label for="wilayah" class="block text-xs font-bold text-slate-700 mb-1">
                            {{ __('Kota / Wilayah') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="wilayah" 
                               id="wilayah" 
                               value="{{ old('wilayah', $cabang->wilayah) }}"
                               required
                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        @error('wilayah')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kepala Cabang / Penanggung Jawab --}}
                    <div>
                        <label for="penanggung_jawab" class="block text-xs font-bold text-slate-700 mb-1">
                            {{ __('Kepala Cabang') }} / {{ __('Penanggung Jawab') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="penanggung_jawab" 
                               id="penanggung_jawab" 
                               value="{{ old('penanggung_jawab', $cabang->penanggung_jawab) }}"
                               required
                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        @error('penanggung_jawab')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jam Operasional --}}
                    <div>
                        <label for="jam_operasional" class="block text-xs font-bold text-slate-700 mb-1">
                            {{ __('Jam Operasional') }}
                        </label>
                        <input type="text" 
                               name="jam_operasional" 
                               id="jam_operasional" 
                               value="{{ old('jam_operasional', $cabang->jam_operasional ?? '08:00 - 17:00 WIB') }}"
                               placeholder="Contoh: 08:00 - 17:00 WIB"
                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        @error('jam_operasional')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor Telepon --}}
                    <div>
                        <label for="nomor_telepon" class="block text-xs font-bold text-slate-700 mb-1">
                            {{ __('Nomor Telepon') }}
                        </label>
                        <input type="text" 
                               name="nomor_telepon" 
                               id="nomor_telepon" 
                               value="{{ old('nomor_telepon', $cabang->nomor_telepon) }}"
                               placeholder="Contoh: 08123456789"
                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        @error('nomor_telepon')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email Cabang --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
                            {{ __('Email Cabang') }}
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $cabang->email) }}"
                               placeholder="cabang@rumahharapan.org"
                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        @error('email')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Alamat Lengkap --}}
                <div>
                    <label for="alamat" class="block text-xs font-bold text-slate-700 mb-1">
                        {{ __('Alamat Lengkap') }}
                    </label>
                    <textarea name="alamat" 
                              id="alamat" 
                              rows="3" 
                              placeholder="Masukkan alamat lengkap asrama cabang..."
                              class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">{{ old('alamat', $cabang->alamat) }}</textarea>
                    @error('alamat')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" 
                            onclick="closeEditModal()" 
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer">
                        {{ __('Batal') }}
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-md shadow-emerald-600/20 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ __('Simpan Perubahan') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal() {
        const modal = document.getElementById('modal-edit-profil');
        if (!modal) return;
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => {
            document.getElementById('nama_asrama')?.focus();
        }, 50);
    }

    function closeEditModal() {
        const modal = document.getElementById('modal-edit-profil');
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Tangani penutupan modal via Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('modal-edit-profil');
            if (modal && !modal.classList.contains('hidden')) {
                closeEditModal();
            }
        }
    });

    @if ($errors->any())
        // Otomatis buka kembali modal jika terdapat error validasi
        window.addEventListener('DOMContentLoaded', () => {
            openEditModal();
        });
    @endif
</script>
@endpush
@endsection

