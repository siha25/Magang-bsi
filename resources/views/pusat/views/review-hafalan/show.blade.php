{{-- BAGIAN: PENGURUS PUSAT - Detail & Form Review Setoran Hafalan --}}
@extends('pusat.layout.app')

@section('title', 'Review Setoran Hafalan')
@section('header_title', 'Review Setoran Hafalan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('pusat.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                @if($evaluasi->jenis_evaluasi === \App\Models\EvaluasiDiniyah::KATEGORI_HADIS)
                    <a href="{{ route('pusat.review-hafalan.hadis.index') }}" class="hover:text-emerald-700 transition-colors">Review Hafalan Hadis</a>
                @else
                    <a href="{{ route('pusat.review-hafalan.quran.index') }}" class="hover:text-emerald-700 transition-colors">Review Hafalan Al-Qur'an</a>
                @endif
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Detail</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $evaluasi->materi }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">{{ $evaluasi->jenis_evaluasi }}</p>
        </div>
        <a href="{{ $evaluasi->jenis_evaluasi === \App\Models\EvaluasiDiniyah::KATEGORI_HADIS ? route('pusat.review-hafalan.hadis.index') : route('pusat.review-hafalan.quran.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold">
            &larr; Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1 text-xs sm:text-sm font-medium">{{ session('success') }}</div>
        </div>
    @endif

    {{-- Detail Setoran --}}
    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-6 border-b border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Santri / Anak Asuh</span>
                <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $evaluasi->anakAsuh?->nama_lengkap }}</p>
                <p class="text-slate-500">NIS: {{ $evaluasi->anakAsuh?->nomor_induk }} &bull; Jenjang: {{ $evaluasi->anakAsuh?->jenjang_pendidikan }}</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Cabang Asrama</span>
                <p class="font-bold text-slate-900 mt-0.5">{{ $evaluasi->asrama?->nama_asrama }}</p>
                <p class="text-slate-500">{{ $evaluasi->asrama?->wilayah }}</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Tanggal Setoran</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $evaluasi->tanggal_evaluasi?->translatedFormat('l, d F Y') }}</p>
                <p class="text-slate-500">Diajukan: {{ $evaluasi->diajukan_pada?->translatedFormat('d M Y, H:i') }}</p>
            </div>
            <div>
                <span class="text-slate-400 block uppercase font-bold text-[10px]">Staff Cabang Pengaju</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $evaluasi->pengaju?->name ?? '-' }}</p>
            </div>
        </div>

        {{-- Predikat Awal --}}
        <div>
            <span class="text-slate-400 block uppercase font-bold text-[10px] mb-2">Predikat Awal (dari Staff Cabang)</span>
            <div class="flex items-center gap-3 p-4 rounded-2xl bg-blue-50 border border-blue-200">
                <span class="text-4xl font-extrabold text-blue-700">{{ $evaluasi->predikat ?? '-' }}</span>
                <div>
                    <p class="text-sm font-semibold text-blue-800">
                        @if($evaluasi->predikat === 'A') Istimewa
                        @elseif($evaluasi->predikat === 'B') Baik
                        @elseif($evaluasi->predikat === 'C') Cukup
                        @else Belum diisi
                        @endif
                    </p>
                    <p class="text-xs text-blue-600">Predikat ini tidak akan diubah saat Anda menyetujui dengan revisi.</p>
                </div>
            </div>
        </div>

        {{-- Catatan Penguji Cabang --}}
        <div>
            <span class="text-slate-400 block uppercase font-bold text-[10px] mb-1">Catatan Penguji (Staff Cabang)</span>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs sm:text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                {{ $evaluasi->catatan_pengaju ?: 'Tidak ada catatan dari Staff Cabang.' }}
            </div>
        </div>
    </div>

    {{-- Status / Sudah disetujui --}}
    @if($evaluasi->status !== \App\Models\EvaluasiDiniyah::STATUS_PENDING)
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
            ✓ Setoran ini sudah disetujui. Predikat akhir: <strong>{{ $evaluasi->predikat_akhir ?? '-' }}</strong>.
            @if($evaluasi->penilai)
                Direview oleh <strong>{{ $evaluasi->penilai->name }}</strong> pada {{ $evaluasi->dinilai_pada?->translatedFormat('d M Y') }}.
            @endif
        </div>
    @else
        {{-- Form Approval — hanya tampil jika masih pending --}}
        @php $isAdminPusat = auth()->user()?->isAdminPusat(); @endphp

        @if($isAdminPusat)
            <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-6 sm:p-8 space-y-6">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Keputusan Review Admin Pendik</h3>

                {{-- Opsi A: Setujui Tanpa Revisi --}}
                <div class="border border-emerald-200 rounded-2xl p-5 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-emerald-800 mb-1">Opsi 1: Setujui Tanpa Revisi</p>
                        <p class="text-xs text-slate-500">Predikat akhir = Predikat awal (<strong>{{ $evaluasi->predikat }}</strong>). Tidak ada perubahan predikat.</p>
                    </div>
                    <form method="POST" action="{{ route('pusat.review-hafalan.approve', $evaluasi->id) }}">
                        @csrf
                        <div class="space-y-2 mb-4">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Catatan (opsional)</label>
                            <textarea name="catatan_evaluasi_pusat" rows="2" placeholder="Catatan dari Admin Pendik..." class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20"></textarea>
                        </div>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-md shadow-emerald-600/25 transition-all">
                            ✓ Setujui Tanpa Revisi
                        </button>
                    </form>
                </div>

                {{-- Opsi B: Setujui Dengan Revisi Predikat Akhir --}}
                <div class="border border-blue-200 rounded-2xl p-5 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-blue-800 mb-1">Opsi 2: Setujui Dengan Revisi Predikat Akhir</p>
                        <p class="text-xs text-slate-500">Predikat awal dari Staff Cabang (<strong>{{ $evaluasi->predikat }}</strong>) tetap tersimpan. Anda hanya menetapkan predikat akhir yang berbeda.</p>
                    </div>
                    <form method="POST" action="{{ route('pusat.review-hafalan.approve-revisi', $evaluasi->id) }}">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Predikat Akhir <span class="text-rose-500">*</span></label>
                                <select name="predikat_akhir" required class="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                    <option value="">-- Pilih Predikat Akhir --</option>
                                    <option value="A">A &mdash; Istimewa</option>
                                    <option value="B">B &mdash; Baik</option>
                                    <option value="C">C &mdash; Cukup</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Catatan (opsional)</label>
                                <input type="text" name="catatan_evaluasi_pusat" placeholder="Alasan revisi predikat..." class="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            </div>
                        </div>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-md shadow-blue-600/25 transition-all">
                            ✎ Setujui dengan Revisi Predikat
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-slate-600 text-xs">
                Hanya <strong>Admin Pendik</strong> yang dapat menyetujui atau merevisi setoran hafalan.
            </div>
        @endif
    @endif
</div>
@endsection
