{{-- BAGIAN: PENGURUS PUSAT - Form Edit Cabang & Akun --}}
@extends('pusat.layout.app')

@section('title', 'Edit Cabang ' . $cabang->nama_asrama)
@section('header_title', 'Edit Cabang')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('pusat.kelola-cabang.index') }}" class="hover:text-emerald-700 transition-colors">Kelola Cabang</a>
        <span>&rsaquo;</span>
        <span class="text-slate-800 font-semibold">Edit Cabang {{ $cabang->nama_asrama }}</span>
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

    {{-- Form Edit Cabang --}}
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-emerald-100/90 shadow-xs">
        <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-slate-900">Perbarui Data Cabang & Akun</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Edit identitas cabang dan email pengurus penanggung jawab cabang ini.
                </p>
            </div>
            <span class="px-3 py-1 bg-slate-100 text-slate-700 font-mono text-xs font-bold rounded-xl">
                {{ $cabang->kode_asrama }}
            </span>
        </div>

        @php
            $userAkun = $cabang->users->first();
        @endphp

        <form action="{{ route('pusat.kelola-cabang.update', $cabang->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Bagian 1: Data Identitas Cabang --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    1. Identitas Cabang
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_cabang" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Cabang <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nama_cabang" id="nama_cabang" value="{{ old('nama_cabang', $cabang->nama_asrama) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>

                    <div>
                        <label for="wilayah" class="block text-xs font-semibold text-slate-700 mb-1">
                            Daerah / Kota Penempatan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="wilayah" id="wilayah" value="{{ old('wilayah', $cabang->wilayah) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>

                    <div>
                        <label for="penanggung_jawab" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Penanggung Jawab (Pengurus) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="penanggung_jawab" id="penanggung_jawab" value="{{ old('penanggung_jawab', $cabang->penanggung_jawab) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>

                    <div>
                        <label for="nomor_telepon" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nomor Kontak Telepon / WA
                        </label>
                        <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon', $cabang->nomor_telepon) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="alamat" class="block text-xs font-semibold text-slate-700 mb-1">
                            Alamat Lengkap Cabang
                        </label>
                        <textarea name="alamat" id="alamat" rows="2"
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">{{ old('alamat', $cabang->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Bagian 2: Akun Pengurus Cabang --}}
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    2. Akun Pengurus Cabang
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">
                            Email Login Cabang
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $userAkun?->email) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>

                    <div class="flex flex-col justify-end">
                        <span class="text-[11px] text-slate-500">
                            Untuk mengatur ulang password akun cabang, gunakan fitur <strong>Reset Password</strong> di halaman daftar cabang.
                        </span>
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

