@extends('layouts.app')

@section('title', 'Tambah Asrama Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 sm:space-y-8">

    {{-- Header Halaman & Breadcrumb --}}
    <div>
        <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
            <a href="{{ route('dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('asrama.index') }}" class="hover:text-emerald-700 transition-colors">Data Asrama</a>
            <span>/</span>
            <span class="text-emerald-800 font-semibold">Tambah Asrama</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            Tambah Asrama Baru
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Daftarkan asrama cabang baru untuk dihubungkan dengan modul evaluasi diniyah dan santri anak asuh.
        </p>
    </div>

    {{-- Error Alert Ringkasan --}}
    @if($errors->any())
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

    {{-- Formulir Tambah Cabang --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-8 border-b border-slate-100 bg-gradient-to-r from-emerald-50/40 via-white to-white">
            <h3 class="text-base font-bold text-slate-900">Informasi Pokok Asrama Cabang</h3>
            <p class="text-xs text-slate-500 mt-0.5">Semua data cabang akan disimpan ke database MySQL.</p>
        </div>

        <form method="POST" action="{{ route('asrama.store') }}" class="p-5 sm:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2 rounded-xl border border-emerald-100 bg-emerald-50/60 px-4 py-3">
                    <p class="text-xs font-semibold text-emerald-800">Kode Asrama dibuat otomatis</p>
                    <p class="mt-1 text-xs text-emerald-700">Kode berurutan akan ditetapkan setelah asrama disimpan dan tidak dapat diubah.</p>
                </div>

                {{-- Nama Cabang --}}
                <div class="space-y-1.5">
                    <label for="nama_asrama" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Nama Asrama <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama_asrama" 
                           name="nama_asrama" 
                           value="{{ old('nama_asrama') }}"
                           required
                           placeholder="Contoh: Asrama Putera Bandung" 
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('nama_asrama') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">
                    @error('nama_asrama')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Wilayah --}}
                <div class="space-y-1.5">
                    <label for="wilayah" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Wilayah / Kota
                    </label>
                    <input type="text" 
                           id="wilayah" 
                           name="wilayah" 
                           value="{{ old('wilayah') }}"
                           placeholder="Contoh: Bandung, Jawa Barat" 
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('wilayah') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">
                    @error('wilayah')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Penanggung Jawab / Kepala Asrama --}}
                <div class="space-y-1.5">
                    <label for="penanggung_jawab" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Penanggung Jawab / Kepala Asrama
                    </label>
                    <input type="text" 
                           id="penanggung_jawab" 
                           name="penanggung_jawab" 
                           value="{{ old('penanggung_jawab') }}"
                           placeholder="Contoh: Ust. H. Ridwan Kamil, S.Sos.I" 
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('penanggung_jawab') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">
                    @error('penanggung_jawab')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div class="space-y-1.5">
                    <label for="nomor_telepon" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Nomor Telepon / WhatsApp
                    </label>
                    <input type="text" 
                           id="nomor_telepon" 
                           name="nomor_telepon" 
                           value="{{ old('nomor_telepon') }}"
                           placeholder="Contoh: 0812-2345-6789" 
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('nomor_telepon') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all font-mono">
                    @error('nomor_telepon')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Aktif --}}
                <div class="space-y-1.5 flex flex-col justify-center">
                    <span class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Status Operasional
                    </span>
                    <label class="relative inline-flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" 
                               name="status_aktif" 
                               value="1" 
                               class="sr-only peer"
                               {{ old('status_aktif', '1') == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span class="text-xs sm:text-sm font-semibold text-slate-800">Asrama Berstatus Aktif</span>
                    </label>
                    <p class="text-[11px] text-slate-400">Cabang aktif dapat didata santri dan diinput evaluasi diniyahnya.</p>
                </div>
            </div>

            {{-- Alamat Lengkap --}}
            <div class="space-y-1.5">
                <label for="alamat" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                    Alamat Lengkap Asrama
                </label>
                <textarea id="alamat" 
                          name="alamat" 
                          rows="3" 
                          placeholder="Masukkan alamat lengkap asrama, nomor jalan, kelurahan, kecamatan..." 
                          class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 border @error('alamat') border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 @enderror rounded-xl focus:outline-none focus:ring-2 transition-all">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Batal & Simpan --}}
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('asrama.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs sm:text-sm font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 active:scale-95 text-white text-xs sm:text-sm font-bold shadow-md shadow-orange-500/25 transition-all">
                    Simpan Data Asrama
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
