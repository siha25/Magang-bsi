{{-- BAGIAN: SHARED - Komponen Tombol Reusable --}}
@props([
    'variant' => 'emerald',
    'type' => 'button',
    'size' => 'md',
])

@php
    $baseClass = "inline-flex items-center justify-center gap-2 rounded-xl font-semibold transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1";
    
    $sizeClass = match($size) {
        'sm' => 'px-3 py-1.5 text-xs',
        'lg' => 'px-5 py-3 text-sm sm:text-base',
        default => 'px-4 py-2.5 text-xs sm:text-sm',
    };

    $variantClass = match($variant) {
        'orange' => 'bg-orange-500 hover:bg-orange-600 text-white shadow-md shadow-orange-500/20 focus:ring-orange-400',
        'secondary' => 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 focus:ring-slate-300',
        'danger' => 'bg-rose-600 hover:bg-rose-700 text-white shadow-md shadow-rose-600/20 focus:ring-rose-400',
        default => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-600/25 focus:ring-emerald-400',
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClass $sizeClass $variantClass"]) }}>
    {{ $slot }}
</button>

