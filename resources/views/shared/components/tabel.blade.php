{{-- BAGIAN: SHARED - Komponen Tabel Reusable --}}
@props([
    'header' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-3xl border border-emerald-100/90 shadow-xs overflow-hidden']) }}>
    @if($header)
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/30 via-white to-white">
            {{ $header }}
        </div>
    @endif
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            {{ $slot }}
        </table>
    </div>
</div>

