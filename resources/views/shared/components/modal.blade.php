{{-- BAGIAN: SHARED - Komponen Modal Dialog --}}
@props([
    'id',
    'title' => 'Konfirmasi',
])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" onclick="document.getElementById('{{ $id }}').classList.add('hidden')"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-semibold leading-6 text-slate-900" id="modal-title">{{ $title }}</h3>
                <button type="button" onclick="document.getElementById('{{ $id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

