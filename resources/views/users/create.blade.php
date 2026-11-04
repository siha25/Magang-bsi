@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 sm:space-y-8">

    {{-- Header Halaman & Breadcrumb --}}
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
            <a href="{{ route('dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('users.index') }}" class="hover:text-emerald-700 transition-colors">Pengaturan Akun</a>
            <span>/</span>
            <span class="text-emerald-800 font-semibold">Tambah Pengguna</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            Tambah Pengguna Baru
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Buat akun login pengurus baru (Admin Pusat, Admin Cabang/Asrama, atau Pimpinan).
        </p>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm shadow-xs">
        <div class="flex items-center gap-2 font-bold mb-1">
            <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Mohon periksa kembali formulir isian Anda:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 pl-1 text-rose-700 text-xs">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Card Form --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-8 border-b border-slate-100 bg-gradient-to-r from-emerald-50/40 via-white to-white">
            <h3 class="text-base font-bold text-slate-900">Informasi Kredensial & Hak Akses</h3>
            <p class="text-xs text-slate-500 mt-0.5">Password yang didaftarkan akan dapat diintip dan di-reset oleh Admin Pusat jika pengguna lupa.</p>
        </div>

        <form method="POST" action="{{ route('users.store') }}" class="p-5 sm:p-8 space-y-6">
            @csrf

            <div class="space-y-5">
                
                {{-- Nama Lengkap --}}
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Nama Lengkap Pengurus <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           required
                           placeholder="Contoh: Ustadz Ahmad Fauzan, S.Pd.I" 
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('name') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">
                    @error('name')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email Login --}}
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Alamat Email (Username Login) <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required
                           placeholder="nama.pengurus@rumahharapan.org" 
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('email') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all font-mono">
                    @error('email')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role / Peran --}}
                <div class="space-y-1.5">
                    <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Peran / Hak Akses Pengguna <span class="text-rose-500">*</span>
                    </label>
                    <select id="role" 
                            name="role" 
                            required
                            onchange="handleRoleChange(this.value)"
                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('role') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">
                        <option value="">-- Pilih Peran Akun --</option>
                        <option value="{{ \App\Models\User::ROLE_ADMIN_CABANG }}" {{ old('role') === \App\Models\User::ROLE_ADMIN_CABANG ? 'selected' : '' }}>
                            Admin Cabang / Asrama (Mengelola 1 Asrama Tertentu)
                        </option>
                        <option value="{{ \App\Models\User::ROLE_PIMPINAN_PUSAT }}" {{ old('role') === \App\Models\User::ROLE_PIMPINAN_PUSAT ? 'selected' : '' }}>
                            Pimpinan Pusat (Monitoring & Supervisi Nasional)
                        </option>
                        <option value="{{ \App\Models\User::ROLE_ADMIN_PUSAT }}" {{ old('role') === \App\Models\User::ROLE_ADMIN_PUSAT ? 'selected' : '' }}>
                            Admin Pusat (Akses Penuh Seluruh Sistem)
                        </option>
                    </select>
                    @error('role')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Asrama Penugasan (Khusus Admin Cabang) --}}
                <div id="asramaSelectWrapper" class="space-y-1.5 {{ old('role') === \App\Models\User::ROLE_ADMIN_CABANG ? '' : 'hidden' }}">
                    <label for="asrama_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Asrama yang Ditugaskan <span class="text-rose-500">*</span>
                    </label>
                    <select id="asrama_id" 
                            name="asrama_id"
                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('asrama_id') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">
                        <option value="">-- Pilih Asrama Binaan --</option>
                        @foreach($asramas as $asrama)
                            <option value="{{ $asrama->id }}" {{ old('asrama_id') == $asrama->id ? 'selected' : '' }}>
                                {{ $asrama->nama_asrama }} ({{ $asrama->kode_asrama }}) - {{ $asrama->wilayah ?: 'Pusat' }}
                            </option>
                        @endforeach
                    </select>
                    @error('asrama_id')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Masuk --}}
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Password Masuk Dashboard <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="password" 
                               name="password" 
                               value="{{ old('password') }}"
                               required
                               minlength="6"
                               placeholder="Masukkan password awal atau klik tombol Acak..." 
                               class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('password') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all font-mono">
                        <button type="button" 
                                onclick="generateFormPassword()"
                                class="absolute right-2 top-2 px-2.5 py-1 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                            Acak Password
                        </button>
                    </div>
                    <span class="text-[11px] text-slate-400 block mt-1">
                        Minimal 6 karakter. Password ini dapat dilihat Admin Pusat di tabel akun jika pengguna meminta bantuan.
                    </span>
                    @error('password')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Aktif Akun --}}
                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" 
                               name="status_aktif" 
                               value="1" 
                               {{ old('status_aktif', '1') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <span class="text-xs font-semibold text-slate-700">Akun Langsung Aktif (Dapat Login ke Dashboard)</span>
                    </label>
                </div>

            </div>

            {{-- Tombol Submit & Kembali --}}
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/25 transition-all">
                    Simpan Akun Pengguna
                </button>
            </div>

        </form>
    </div>

</div>

<script>
    function handleRoleChange(role) {
        const wrapper = document.getElementById('asramaSelectWrapper');
        const select = document.getElementById('asrama_id');

        if (role === '{{ \App\Models\User::ROLE_ADMIN_CABANG }}') {
            wrapper.classList.remove('hidden');
            select.setAttribute('required', 'required');
        } else {
            wrapper.classList.add('hidden');
            select.removeAttribute('required');
            select.value = '';
        }
    }

    function generateFormPassword() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#';
        let res = '';
        for (let i = 0; i < 8; i++) {
            res += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('password').value = res;
    }
</script>
@endsection
