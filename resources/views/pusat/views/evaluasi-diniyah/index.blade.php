{{-- BAGIAN: PENGURUS PUSAT - Evaluasi Diniyah Ringan Santri Nilai Kurang --}}
@extends('pusat.layout.app')

@section('title', 'Evaluasi Diniyah (Ringan)')
@section('header_title', 'Evaluasi Diniyah (Ringan)')

@section('content')
<div class="space-y-6">

    {{-- Alert Flash Message --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <div class="font-bold">Gagal menyimpan catatan evaluasi:</div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header Banner & Pengaturan KKM --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                Evaluasi Khusus Santri Bawah KKM
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Fokus supervisi Admin Pendik untuk santri yang membutuhkan remedial atau bimbingan khusus dari cabang.
            </p>
        </div>

        {{-- Form Ganti Batas KKM --}}
        <form method="GET" action="{{ route('pusat.evaluasi-diniyah.index') }}" class="flex items-center gap-2 bg-white p-2 rounded-2xl border border-emerald-100 shadow-xs self-start sm:self-auto">
            <label for="kkm" class="text-xs font-semibold text-slate-600 whitespace-nowrap pl-2">Standar KKM:</label>
            <input type="number" step="0.5" min="0" max="100" name="kkm" id="kkm" value="{{ $kkm }}"
                   class="w-16 px-2.5 py-1.5 bg-slate-50 text-slate-800 font-bold text-xs rounded-xl border border-slate-200 focus:bg-white focus:border-emerald-500 outline-none text-center">
            <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors">
                Ubah
            </button>
        </form>
    </div>

    {{-- 2 Kartu Metrik Evaluasi --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-emerald-100/90 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Santri di Bawah KKM ({{ $kkm }})</span>
                <span class="text-3xl font-extrabold text-slate-900">{{ number_format($total_kurang) }}</span>
                <span class="text-[11px] text-slate-500 block mt-1">Perlu supervisi kurikulum</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-emerald-100/90 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Menunggu Tindak Lanjut</span>
                <span class="text-3xl font-extrabold {{ $total_belum_ditindak > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                    {{ number_format($total_belum_ditindak) }}
                </span>
                <span class="text-[11px] text-slate-500 block mt-1">Belum diberikan catatan / rekomendasi pusat</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-3xl p-5 border border-emerald-100/90 shadow-xs">
        <form method="GET" action="{{ route('pusat.evaluasi-diniyah.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <input type="hidden" name="kkm" value="{{ $kkm }}">

            <div>
                <label for="cabang_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Cabang</label>
                <select name="cabang_id" id="cabang_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 outline-none">
                    <option value="">Semua Cabang</option>
                    @foreach($cabangOptions as $c)
                        <option value="{{ $c->id }}" {{ (string)$filters['cabang_id'] === (string)$c->id ? 'selected' : '' }}>
                            {{ $c->nama_asrama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="daerah" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Wilayah / Daerah</label>
                <select name="daerah" id="daerah" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 outline-none">
                    <option value="">Semua Wilayah</option>
                    @foreach($daerahOptions as $d)
                        <option value="{{ $d }}" {{ $filters['daerah'] === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status_tindak_lanjut" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status Tindak Lanjut</label>
                <select name="status_tindak_lanjut" id="status_tindak_lanjut" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 outline-none">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $val => $lbl)
                        <option value="{{ $val }}" {{ $filters['status_tindak_lanjut'] === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors">
                    Filter
                </button>
                @if(array_filter($filters))
                    <a href="{{ route('pusat.evaluasi-diniyah.index', ['kkm' => $kkm]) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Daftar Evaluasi Santri di Bawah KKM --}}
    <div class="space-y-4">
        @forelse($daftar_kurang as $ev)
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-emerald-100/90 shadow-xs hover:border-emerald-200 transition-colors">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex flex-col items-center justify-center shrink-0 border border-rose-100">
                            <span class="text-base font-extrabold leading-none">{{ $ev->nilai }}</span>
                            <span class="text-[9px] uppercase tracking-wider font-bold text-rose-400 mt-0.5">Nilai</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-bold text-slate-900">{{ $ev->anakAsuh?->nama_lengkap ?? 'Santri #' . $ev->anak_asuh_id }}</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                                    NIS: {{ $ev->anakAsuh?->nis ?? '-' }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $ev->asrama?->nama_asrama }} ({{ $ev->asrama?->wilayah ?? '-' }})
                                </span>
                            </div>
                            <div class="text-xs text-slate-600 mt-1.5 flex items-center gap-2 flex-wrap">
                                <span>Materi: <strong>{{ $ev->materi }}</strong></span>
                                <span>&bull;</span>
                                <span>Kategori: <strong class="capitalize">{{ $ev->jenis_evaluasi }}</strong></span>
                                <span>&bull;</span>
                                <span>Tanggal: {{ $ev->tanggal_evaluasi?->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Badge Status Tindak Lanjut --}}
                    <div>
                        @php
                            $statusBadge = match($ev->status_tindak_lanjut) {
                                \App\Shared\Types\EvaluationStatus::TINDAK_LANJUT_TUNTAS => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                \App\Shared\Types\EvaluationStatus::TINDAK_LANJUT_BIMBINGAN => 'bg-blue-50 text-blue-700 border-blue-200',
                                \App\Shared\Types\EvaluationStatus::TINDAK_LANJUT_PERHATIAN => 'bg-amber-50 text-amber-700 border-amber-200',
                                default => 'bg-rose-50 text-rose-700 border-rose-200',
                            };
                            $statusLabel = match($ev->status_tindak_lanjut) {
                                \App\Shared\Types\EvaluationStatus::TINDAK_LANJUT_TUNTAS => 'Tuntas / Selesai',
                                \App\Shared\Types\EvaluationStatus::TINDAK_LANJUT_BIMBINGAN => 'Dalam Bimbingan',
                                \App\Shared\Types\EvaluationStatus::TINDAK_LANJUT_PERHATIAN => 'Perhatian Khusus',
                                default => 'Belum Ditindaklanjuti',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $statusBadge }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ str_contains($statusBadge, 'emerald') ? 'bg-emerald-500' : (str_contains($statusBadge, 'blue') ? 'bg-blue-500' : 'bg-rose-500') }}"></span>
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                {{-- Form Catatan & Tindak Lanjut Pusat --}}
                <div class="mt-4 pt-2">
                    <form action="{{ route('pusat.evaluasi-diniyah.simpan-catatan', $ev->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                            <div class="lg:col-span-2">
                                <label for="catatan_{{ $ev->id }}" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                    Catatan Evaluasi / Rekomendasi Pusat
                                </label>
                                <textarea name="catatan_evaluasi_pusat" id="catatan_{{ $ev->id }}" rows="2" required
                                          placeholder="Tuliskan arahan bimbingan kepada staff cabang untuk santri ini..."
                                          class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">{{ old('catatan_evaluasi_pusat', $ev->catatan_evaluasi_pusat) }}</textarea>
                            </div>

                            <div class="flex flex-col justify-between gap-2">
                                <div>
                                    <label for="status_{{ $ev->id }}" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                        Update Status Tindak Lanjut
                                    </label>
                                    <select name="status_tindak_lanjut" id="status_{{ $ev->id }}" required
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none">
                                        @foreach($statusOptions as $val => $lbl)
                                            <option value="{{ $val }}" {{ old('status_tindak_lanjut', $ev->status_tindak_lanjut ?? \App\Shared\Types\EvaluationStatus::TINDAK_LANJUT_BELUM) === $val ? 'selected' : '' }}>
                                                {{ $lbl }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors self-end">
                                    Simpan Evaluasi Pusat
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-12 text-center border border-emerald-100/90 shadow-xs">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Alhamdulillah, Tidak Ada Santri di Bawah KKM</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                    Seluruh santri memenuhi batas KKM {{ $kkm }} pada kriteria filter yang dipilih.
                </p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($daftar_kurang->hasPages())
        <div class="bg-white rounded-2xl p-4 border border-emerald-100/90 shadow-xs">
            {{ $daftar_kurang->links() }}
        </div>
    @endif

</div>
@endsection

