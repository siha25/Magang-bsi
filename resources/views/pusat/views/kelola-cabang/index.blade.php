{{-- BAGIAN: PENGURUS PUSAT - Daftar Kelola Akun & Data Cabang --}}
@extends('pusat.layout.app')

@section('title', 'Kelola Akun Cabang')
@section('header_title', 'Kelola Akun Cabang')

@section('content')
<div class="space-y-6">

    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Header Banner & Tombol Tambah --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                Manajemen Akun & Wilayah Cabang
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pusat membuat akun pengurus untuk setiap cabang. Akun cabang terisolasi dan hanya dapat mengelola data cabangnya sendiri.
            </p>
        </div>
        <a href="{{ route('pusat.kelola-cabang.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm shadow-md shadow-emerald-600/20 transition-all self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Cabang Baru</span>
        </a>
    </div>

    {{-- Kartu Ringkasan Cabang --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-emerald-100/90 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Cabang</span>
            <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['total']) }}</span>
            <span class="text-[11px] text-slate-500 block mt-1">Semua cabang terdaftar</span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-emerald-100/90 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Cabang Aktif</span>
            <span class="text-2xl font-bold text-emerald-600">{{ number_format($stats['aktif']) }}</span>
            <span class="text-[11px] text-slate-500 block mt-1">Dapat login & input data</span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-emerald-100/90 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Cabang Nonaktif</span>
            <span class="text-2xl font-bold text-slate-400">{{ number_format($stats['nonaktif']) }}</span>
            <span class="text-[11px] text-slate-500 block mt-1">Akses login ditangguhkan</span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-emerald-100/90 shadow-xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Cakupan Wilayah</span>
            <span class="text-2xl font-bold text-teal-600">{{ number_format($stats['total_daerah']) }}</span>
            <span class="text-[11px] text-slate-500 block mt-1">Kota / Kabupaten</span>
        </div>
    </div>

    {{-- Filter & Pencarian --}}
    <div class="bg-white rounded-3xl p-5 border border-emerald-100/90 shadow-xs">
        <form method="GET" action="{{ route('pusat.kelola-cabang.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label for="cari" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Cari Cabang / PJ / Email</label>
                <input type="text" name="cari" id="cari" value="{{ $cari }}" placeholder="Nama cabang, kode, email..."
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
            </div>

            <div>
                <label for="daerah" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Wilayah / Daerah</label>
                <select name="daerah" id="daerah" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    <option value="">Semua Wilayah</option>
                    @foreach($daerahOptions as $d)
                        <option value="{{ $d }}" {{ $daerah === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status Operasional</label>
                <select name="status" id="status" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors">
                    Filter
                </button>
                @if($cari !== '' || $daerah !== '' || $status !== '')
                    <a href="{{ route('pusat.kelola-cabang.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel Daftar Cabang --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">Kode & Cabang</th>
                        <th class="py-3.5 px-4">Wilayah / Daerah</th>
                        <th class="py-3.5 px-4">Pengurus & Akun Login</th>
                        <th class="py-3.5 px-4 text-center">Santri</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($cabangs as $cabang)
                        @php
                            $userAkun = $cabang->users->first();
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $cabang->nama_asrama }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $cabang->kode_asrama }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-medium">
                                    {{ $cabang->wilayah ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-800">{{ $cabang->penanggung_jawab ?? '-' }}</div>
                                <div class="text-[11px] text-emerald-700">{{ $userAkun?->email ?? 'Belum ada akun' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-bold text-slate-800">{{ $cabang->anakAsuh->count() }}</span>
                                <span class="text-[10px] text-slate-400 block">santri</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($cabang->status_aktif)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    {{-- Edit Cabang --}}
                                    <a href="{{ route('pusat.kelola-cabang.edit', $cabang->id) }}"
                                       class="p-1.5 rounded-lg text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 transition-colors"
                                       title="Edit Cabang & Akun">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>

                                    {{-- Tombol Reset Password --}}
                                    <button type="button"
                                            onclick="openResetModal({{ $cabang->id }}, '{{ addslashes($cabang->nama_asrama) }}', '{{ addslashes($userAkun?->email ?? '') }}')"
                                            class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                                            title="Reset Password Akun">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    </button>

                                    {{-- Toggle Status --}}
                                    <form action="{{ route('pusat.kelola-cabang.toggle-status', $cabang->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status cabang ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="p-1.5 rounded-lg {{ $cabang->status_aktif ? 'text-slate-400 hover:text-rose-600 hover:bg-rose-50' : 'text-slate-400 hover:text-emerald-600 hover:bg-emerald-50' }} transition-colors"
                                                title="{{ $cabang->status_aktif ? 'Nonaktifkan Cabang' : 'Aktifkan Cabang' }}">
                                            @if($cabang->status_aktif)
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                Tidak ada data cabang yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($cabangs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $cabangs->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Modal Reset Password --}}
<div id="resetPasswordModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <h3 class="text-base font-bold text-slate-900 mb-1">Reset Password Akun Cabang</h3>
        <p class="text-xs text-slate-500 mb-4" id="resetModalSubtitle">Atur password baru untuk akun cabang.</p>

        <form id="resetPasswordForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label for="new_password" class="block text-xs font-semibold text-slate-700 mb-1">Password Baru (min. 6 karakter)</label>
                <input type="password" name="password" id="new_password" required minlength="6"
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none"
                       placeholder="Masukkan password baru...">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeResetModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-xs transition-colors">
                    Simpan Password
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openResetModal(cabangId, namaCabang, email) {
    const modal = document.getElementById('resetPasswordModal');
    const form = document.getElementById('resetPasswordForm');
    const sub = document.getElementById('resetModalSubtitle');

    form.action = "{{ url('pusat/kelola-cabang') }}/" + cabangId + "/reset-password";
    sub.textContent = 'Atur ulang password untuk akun ' + email + ' (' + namaCabang + ').';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeResetModal() {
    const modal = document.getElementById('resetPasswordModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endpush
@endsection

