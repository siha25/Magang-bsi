{{-- BAGIAN: PENGURUS PUSAT - Monitoring & Evaluasi Hafalan Hadist Pusat --}}
@extends('pusat.layout.app')

@section('title', 'Hafalan Hadis - Pusat')
@section('header_title', 'Hafalan Hadis')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('pusat.dashboard') }}" class="hover:text-emerald-700 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-emerald-800 font-semibold">Hafalan Hadis</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Monitoring Hafalan Hadis Santri</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pemantauan capaian hafalan hadis santri seluruh cabang Yayasan Rumah Harapan.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-emerald-100/90 shadow-sm p-6 sm:p-8">
        <div class="text-center py-12">
            <div class="w-16 h-16 mx-auto rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-1">Modul Hafalan Hadis (Pusat)</h3>
            <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                Navigasi Hafalan Hadis telah terhubung. Tampilan dan data monitoring akan diselaraskan dengan modul cabang pada tahap implementasi selanjutnya.
            </p>
        </div>
    </div>
</div>
@endsection

