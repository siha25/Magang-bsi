{{-- BAGIAN: PENGURUS PUSAT - Form Tambah Cabang & Akun Baru --}}
@extends('pusat.layout.app')

@section('title', 'Tambah Cabang Baru')
@section('header_title', 'Tambah Cabang Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('pusat.kelola-cabang.index') }}" class="hover:text-emerald-700 transition-colors">Kelola Cabang</a>
        <span>&rsaquo;</span>
        <span class="text-slate-800 font-semibold">Tambah Cabang Baru</span>
    </div>

    {{-- Alert Error Validasi --}}
    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <div class="font-bold">Mohon perbaiki kesalahan pengisian berikut:</div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Tambah Cabang & Akun --}}
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-emerald-100/90 shadow-xs">
        <div class="mb-6 pb-4 border-b border-slate-100">
            <h1 class="text-lg font-bold text-slate-900">Form Pendaftaran Cabang & Akun Pengurus</h1>
            <p class="text-xs text-slate-500 mt-1">
                Data cabang (kode unik otomatis) dan akun login pengurus cabang akan langsung dibuat secara terintegrasi.
            </p>
        </div>

        <form action="{{ route('pusat.kelola-cabang.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Bagian 1: Data Identitas Cabang --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    1. Identitas Cabang Baru
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_cabang" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Cabang <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nama_cabang" id="nama_cabang" value="{{ old('nama_cabang') }}" required
                               placeholder="Contoh: Asrama Cibubur"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>

                    <div>
                        <label for="wilayah" class="block text-xs font-semibold text-slate-700 mb-1">
                            Daerah / Kota Penempatan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="wilayah" id="wilayah" value="{{ old('wilayah') }}" required
                               placeholder="Contoh: Jakarta Timur"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>

                    <div>
                        <label for="penanggung_jawab" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Penanggung Jawab (Pengurus) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="penanggung_jawab" id="penanggung_jawab" value="{{ old('penanggung_jawab') }}" required
                               placeholder="Contoh: Ustadz Ahmad Fauzi"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>

                    <div>
                        <label for="nomor_telepon" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nomor Kontak Telepon / WA
                        </label>
                        <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon') }}"
                               placeholder="Contoh: 081234567890"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="alamat" class="block text-xs font-semibold text-slate-700 mb-1">
                            Alamat Lengkap Cabang
                        </label>
                        <textarea name="alamat" id="alamat" rows="2"
                                  placeholder="Alamat domisili asrama / cabang..."
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Bagian 2: Akun Login Pengurus Cabang --}}
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    2. Akun Login Pengurus Cabang
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">
                            Email Login Cabang <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               placeholder="cabang.cibubur@rumahharapan.org"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                            Password Awal (min. 6 karakter) <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required minlength="6"
                               placeholder="Password login pertama kali..."
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                        <span class="text-[10px] text-slate-400 mt-1 block">Password akan langsung dienkripsi (Hash::make) oleh sistem.</span>
                    </div>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('pusat.kelola-cabang.index') }}"
                   class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-md shadow-emerald-600/20 transition-all">
                    Simpan & Buat Akun Cabang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

