@extends('layouts.app')

@section('title', 'Manajemen Akun Pengguna')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- Header Modul & Tombol Tambah --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Pengaturan Akun</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                Manajemen Akun Pengguna
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Kelola hak akses pengurus, admin cabang, pimpinan, serta intip & pemulihan password akun.
            </p>
        </div>

        <div>
            <a href="{{ route('users.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 active:scale-95 text-white text-xs sm:text-sm font-semibold shadow-md shadow-orange-500/25 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Pengguna Baru</span>
            </a>
        </div>
    </div>

    {{-- Alert Flash Message --}}
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-xs">
        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="flex-1 text-xs sm:text-sm font-medium">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-xs">
        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="flex-1 text-xs sm:text-sm font-medium">
            {{ session('error') }}
        </div>
    </div>
    @endif

    {{-- Banner Bantuan Pemulihan Password --}}
    <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50 to-white border border-emerald-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <div>
                <h4 class="text-xs sm:text-sm font-bold text-slate-800">Bantuan Lupa Password Pengguna</h4>
                <p class="text-xs text-slate-600 mt-0.5">
                    Admin Pusat dapat melihat email dan password masuk masing-masing pengguna dengan menekan ikon <strong>Mata</strong> (Intip Password) atau mereset password baru secara langsung.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 self-start md:self-auto shrink-0">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white text-emerald-800 text-xs font-semibold border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Akses Eksklusif Admin Pusat
            </span>
        </div>
    </div>

    {{-- Kartu Statistik Ringkas Pengguna --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        {{-- Total Pengguna --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Pengguna</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $stats['total'] }}</span>
                <span class="text-xs text-slate-500">Akun</span>
            </div>
            <span class="text-[11px] text-slate-400 block mt-2">Seluruh akun terdaftar</span>
        </div>

        {{-- Admin Pusat --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100/90 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 block mb-1">Admin Pusat</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-700">{{ $stats['admin_pusat'] }}</span>
                <span class="text-xs text-emerald-600 font-medium">Akun</span>
            </div>
            <span class="text-[11px] text-emerald-600/80 block mt-2">Akses penuh sistem</span>
        </div>

        {{-- Admin Cabang / Asrama --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-teal-100/90 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-teal-700 block mb-1">Admin Cabang / Asrama</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-teal-700">{{ $stats['admin_cabang'] }}</span>
                <span class="text-xs text-slate-500">Akun</span>
            </div>
            <span class="text-[11px] text-teal-600/80 block mt-2">Petugas pembina asrama</span>
        </div>

        {{-- Pimpinan --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-amber-100/90 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700 block mb-1">Pimpinan Pusat</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-amber-700">{{ $stats['pimpinan'] }}</span>
                <span class="text-xs text-slate-500">Akun</span>
            </div>
            <span class="text-[11px] text-amber-600/80 block mt-2">Akses pantau & monitoring</span>
        </div>
    </div>

    {{-- Panel Pencarian, Filter & Tabel Pengguna --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        
        {{-- Formulir Filter & Pencarian --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/30 via-white to-white">
            <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                
                {{-- Input Pencarian Nama & Email --}}
                <div class="sm:col-span-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           name="cari" 
                           value="{{ request('cari') }}" 
                           placeholder="Cari nama atau email pengguna..." 
                           class="w-full text-xs sm:text-sm pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                </div>

                {{-- Filter Role --}}
                <div class="sm:col-span-3">
                    <select name="role" 
                            class="w-full text-xs sm:text-sm px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        <option value="">Semua Peran / Role</option>
                        <option value="{{ \App\Models\User::ROLE_ADMIN_PUSAT }}" {{ request('role') === \App\Models\User::ROLE_ADMIN_PUSAT ? 'selected' : '' }}>Admin Pusat</option>
                        <option value="{{ \App\Models\User::ROLE_ADMIN_CABANG }}" {{ request('role') === \App\Models\User::ROLE_ADMIN_CABANG ? 'selected' : '' }}>Admin Cabang</option>
                        <option value="{{ \App\Models\User::ROLE_PIMPINAN_PUSAT }}" {{ request('role') === \App\Models\User::ROLE_PIMPINAN_PUSAT ? 'selected' : '' }}>Pimpinan Pusat</option>
                    </select>
                </div>

                {{-- Filter Status Akun --}}
                <div class="sm:col-span-2">
                    <select name="status" 
                            class="w-full text-xs sm:text-sm px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                {{-- Tombol Terapkan --}}
                <div class="sm:col-span-1">
                    <button type="submit" 
                            class="w-full py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-colors flex items-center justify-center gap-1 shadow-xs">
                        <span>Filter</span>
                    </button>
                </div>

            </form>
        </div>

        {{-- Tabel Pengguna --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3.5 px-4 sm:px-6">Nama & Info Akun</th>
                        <th class="py-3.5 px-4">Role / Peran</th>
                        <th class="py-3.5 px-4">Asrama Ditugaskan</th>
                        <th class="py-3.5 px-4">Password Masuk (Admin Info)</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Aksi & Reset</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($users as $item)
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        
                        {{-- Nama & Email Pengguna --}}
                        <td class="py-4 px-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                    {{ strtoupper(substr($item->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900 block truncate text-xs sm:text-sm">
                                            {{ $item->name }}
                                        </span>
                                        @if($item->is_primary_admin)
                                            <span class="px-1.5 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-800 rounded border border-amber-200">
                                                Admin Utama
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[11px] text-slate-500 block truncate font-mono">
                                        {{ $item->email }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- Peran Pengguna --}}
                        <td class="py-4 px-4 whitespace-nowrap">
                            @if($item->role === \App\Models\User::ROLE_ADMIN_PUSAT)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Admin Pusat
                                </span>
                            @elseif($item->role === \App\Models\User::ROLE_ADMIN_CABANG)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-teal-100 text-teal-800 border border-teal-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                                    Admin Cabang
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Pimpinan Pusat
                                </span>
                            @endif
                        </td>

                        {{-- Penugasan Asrama --}}
                        <td class="py-4 px-4">
                            @if($item->asrama)
                                <span class="font-semibold text-slate-800 block text-xs">{{ $item->asrama->nama_asrama }}</span>
                                <span class="text-[11px] text-slate-400 block">{{ $item->asrama->kode_asrama }}</span>
                            @elseif($item->role === \App\Models\User::ROLE_ADMIN_PUSAT || $item->role === \App\Models\User::ROLE_PIMPINAN_PUSAT)
                                <span class="text-xs text-slate-500 font-medium italic">Seluruh Asrama (Nasional)</span>
                            @else
                                <span class="text-xs text-orange-500 font-medium">Belum Dipetakan</span>
                            @endif
                        </td>

                        {{-- Password Recovery (Intip & Salin) --}}
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <div class="px-2.5 py-1.5 rounded-lg bg-slate-100 border border-slate-200 flex items-center gap-2 font-mono text-xs text-slate-700 min-w-[130px] justify-between">
                                    <span id="pass-text-{{ $item->id }}" class="tracking-widest">••••••••</span>
                                    
                                    {{-- Tombol Toggle Intip Password --}}
                                    <button type="button" 
                                            onclick="togglePasswordVisibility({{ $item->id }}, '{{ addslashes($item->visible_password ?? 'Tidak Tersedia (Reset Ulang)') }}')"
                                            class="text-slate-400 hover:text-emerald-700 p-0.5 rounded transition-colors"
                                            title="Tampilkan / Sembunyikan Password">
                                        <svg id="pass-icon-{{ $item->id }}" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Tombol Copy Kredensial --}}
                                <button type="button" 
                                        onclick="copyCredentials('{{ $item->email }}', '{{ addslashes($item->visible_password ?? '') }}')"
                                        class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-colors"
                                        title="Salin Email & Password">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                </button>
                            </div>
                        </td>

                        {{-- Status Akun --}}
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            @if($item->status_aktif)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        {{-- Aksi Kelola, Reset Password & Hapus --}}
                        <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                
                                {{-- Tombol Modal Reset Password --}}
                                <button type="button" 
                                        onclick="openResetModal({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ $item->email }}')"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-amber-700 hover:text-amber-800 hover:bg-amber-100/70 border border-amber-200 transition-colors"
                                        title="Ubah / Reset Password">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                    <span>Reset</span>
                                </button>

                                {{-- Tombol Edit --}}
                                <a href="{{ route('users.edit', $item) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:bg-emerald-100/70 border border-emerald-200 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                {{-- Tombol Toggle Status (Jika Bukan Akun Sendiri / Admin Utama) --}}
                                @if(! $item->is_primary_admin && $item->id !== auth()->id())
                                <form action="{{ route('users.toggle-status', $item) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            onclick="return confirm('Apakah Anda yakin ingin mengubah status aktif akun {{ $item->name }}?')"
                                            class="inline-flex items-center p-1.5 rounded-lg text-xs font-semibold {{ $item->status_aktif ? 'text-slate-500 hover:text-slate-700 hover:bg-slate-100 border border-slate-200' : 'text-emerald-700 hover:bg-emerald-50 border border-emerald-200' }}"
                                            title="{{ $item->status_aktif ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                        @if($item->status_aktif)
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <p class="font-semibold text-slate-600">Tidak ada data pengguna ditemukan.</p>
                            <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter status Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="p-4 px-6 border-t border-slate-100 bg-slate-50/50">
            {{ $users->links() }}
        </div>
        @endif

    </div>

</div>

{{-- MODAL RESET PASSWORD --}}
<div id="resetPasswordModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
        <form id="resetPasswordForm" method="POST" action="">
            @csrf
            
            <div class="p-6 bg-gradient-to-r from-emerald-50 via-teal-50 to-white border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shadow-md shadow-amber-500/20 font-bold">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Reset Password Pengguna</h3>
                            <p id="modalUserEmail" class="text-xs text-slate-500 font-mono mt-0.5"></p>
                        </div>
                    </div>
                    <button type="button" onclick="closeResetModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="modalInputPassword"
                               name="password" 
                               required 
                               minlength="6"
                               placeholder="Masukkan password baru minimal 6 karakter..." 
                               class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-mono">
                        <button type="button" 
                                onclick="generateRandomPassword()"
                                class="absolute right-2 top-2 px-2 py-1 bg-slate-100 hover:bg-slate-200 text-[11px] font-semibold text-slate-700 rounded-lg transition-colors">
                            Acak
                        </button>
                    </div>
                    <span class="text-[11px] text-slate-400 block mt-1">
                        Password ini akan langsung aktif dan tersimpan terenkripsi di sistem.
                    </span>
                </div>
            </div>

            <div class="p-4 px-6 bg-slate-50/70 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" 
                        onclick="closeResetModal()"
                        class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-semibold transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-md shadow-emerald-600/25 transition-all">
                    Simpan Password Baru
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JAVASCRIPT LOGIC UNTUK INTIP & SALIN PASSWORD --}}
<script>
    const passStates = {};

    function togglePasswordVisibility(userId, plainPassword) {
        const span = document.getElementById('pass-text-' + userId);
        const isShown = passStates[userId] || false;

        if (isShown) {
            span.innerText = '••••••••';
            span.classList.add('tracking-widest');
            passStates[userId] = false;
        } else {
            span.innerText = plainPassword;
            span.classList.remove('tracking-widest');
            passStates[userId] = true;
        }
    }

    function copyCredentials(email, password) {
        if (!password) {
            alert('Password belum tersimpan dalam format terdekripsi. Silakan gunakan tombol Reset untuk membuat password baru.');
            return;
        }

        const textToCopy = `Kredensial Akses SIMPENA (Rumah Harapan)\nEmail: ${email}\nPassword: ${password}\nURL Login: ${window.location.origin}/login`;
        navigator.clipboard.writeText(textToCopy).then(() => {
            alert(`Kredensial login untuk ${email} berhasil disalin ke clipboard!`);
        }).catch(() => {
            prompt('Salin kredensial berikut:', textToCopy);
        });
    }

    function openResetModal(userId, userName, userEmail) {
        const modal = document.getElementById('resetPasswordModal');
        const form = document.getElementById('resetPasswordForm');
        const emailLabel = document.getElementById('modalUserEmail');
        const passInput = document.getElementById('modalInputPassword');

        form.action = `/pengguna/${userId}/reset-password`;
        emailLabel.innerText = `${userName} (${userEmail})`;
        passInput.value = '';
        modal.classList.remove('hidden');
    }

    function closeResetModal() {
        document.getElementById('resetPasswordModal').classList.add('hidden');
    }

    function generateRandomPassword() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#';
        let res = '';
        for (let i = 0; i < 8; i++) {
            res += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('modalInputPassword').value = res;
    }
</script>
@endsection
