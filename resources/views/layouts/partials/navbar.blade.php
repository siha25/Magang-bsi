@php
    $headerTitle = trim($__env->yieldContent('header_title'));
    if (empty($headerTitle)) {
        if (request()->routeIs('dashboard') || request()->is('/') || request()->is('dashboard')) {
            $headerTitle = 'Dashboard';
        } elseif (request()->routeIs('asrama.*') || request()->is('asrama*')) {
            $headerTitle = 'Data Asrama';
        } elseif (request()->routeIs('anak-asuh.*') || request()->is('anak-asuh*')) {
            $headerTitle = 'Data Anak Asuh';
        } elseif (request()->routeIs('cabang.*') || request()->is('cabang*')) {
            $headerTitle = 'Data Cabang';
        } else {
            $headerTitle = trim($__env->yieldContent('title', 'Dashboard'));
        }
    }

    $user = auth()->user();
    $userName = $user ? $user->name : 'Pengurus';
    $userRole = 'Pengurus';
    $notifications = [];
    if ($user) {
        if ($user->isAdminPusat()) {
            $userRole = 'Admin Pusat';
        } elseif ($user->isAdminCabang()) {
            $userRole = 'Admin Cabang';
            $notifications = [
                [
                    'id' => 'h1',
                    'title' => 'Hasil evaluasi kesehatan diterima',
                    'message' => 'Ada catatan pemeriksaan kesehatan anak asuh yang perlu ditindaklanjuti.',
                    'read' => false,
                    'time' => '5 menit lalu',
                ],
                [
                    'id' => 'k1',
                    'title' => 'Pengajuan hafalan telah diproses',
                    'message' => 'Status pengajuan hafalan telah disetujui oleh Admin Pusat.',
                    'read' => true,
                    'time' => 'Hari ini',
                ],
            ];
        } elseif ($user->isPimpinanPusat()) {
            $userRole = 'Pimpinan Pusat';
        } else {
            $userRole = ucwords(str_replace('_', ' ', (string) $user->role));
        }
    }
    $unreadNotifications = collect($notifications)->where('read', false)->count();
@endphp

{{-- Topbar / Header Minimalis Pendidikan Ansuh --}}
<header class="sticky top-0 z-30 h-16 sm:h-20 bg-white/95 backdrop-blur-md border-b border-emerald-100/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between transition-all">
    
    {{-- Sisi Kiri: Judul Halaman Dinamis (Tanpa elemen atau tombol yang tidak semestinya) --}}
    <div class="flex items-center min-w-0">

        <button type="button" onclick="toggleSidebar()" aria-label="Buka/tutup sidebar"
        style="display:flex; align-items:center; justify-content:center; width:42px; height:42px; margin-right:12px; border:1px solid #d1fae5; border-radius:12px; background:#ecfdf5; color:#047857; cursor:pointer; flex-shrink:0;">
    <svg style="width:20px; height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>
        <h1 class="text-base sm:text-lg lg:text-xl font-bold text-slate-900 tracking-tight truncate">
            {{ $headerTitle }}
        </h1>
    </div>

    {{-- Sisi Kanan: Identitas Pengguna Ringkas (Avatar, Nama Singkat, Peran) & Tombol Logout --}}
    <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
        @if($user && $user->isAdminCabang())
            <div id="notification-wrapper" class="relative">
                <button type="button" onclick="toggleDropdown('notif-menu')" class="relative flex items-center justify-center w-10 h-10 rounded-xl border border-emerald-100 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                    </svg>
                    @if($unreadNotifications > 0)
                        <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-[10px] font-bold text-white flex items-center justify-center">{{ $unreadNotifications }}</span>
                    @endif
                </button>

                <div id="notif-menu" class="hidden absolute right-0 mt-2 w-80 rounded-2xl border border-slate-200 bg-white shadow-lg z-40 overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-100 bg-emerald-50/80">
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-800">Notifikasi</p>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        @forelse($notifications as $notification)
                            <div class="px-4 py-3 border-b border-slate-100 {{ $notification['read'] ? 'bg-white' : 'bg-emerald-50/40' }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-semibold text-slate-800">{{ $notification['title'] }}</p>
                                        <p class="mt-1 text-[11px] text-slate-600">{{ $notification['message'] }}</p>
                                    </div>
                                    <span class="text-[10px] text-slate-400 whitespace-nowrap">{{ $notification['time'] }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-5 text-center text-[11px] text-slate-500">Belum ada notifikasi.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif

        {{-- Profil + Dropdown --}}
@auth
<div id="user-wrapper" class="relative">
    <button type="button" id="user-btn"
        onclick="document.getElementById('user-menu').classList.toggle('hidden'); document.getElementById('user-chevron').classList.toggle('rotate-180')"
        style="display:flex; align-items:center; gap:12px; padding:7px 20px 7px 7px; background-color:#ecfdf5; border:1px solid #a7f3d0; border-radius:9999px; cursor:pointer;">
        
        {{-- Avatar --}}
    {{-- Avatar --}}
    <span style="display:flex; align-items:center; justify-content:center; width:42px; height:42px; min-width:42px; border-radius:9999px; background:linear-gradient(135deg,#0f766e,#059669); color:#ffffff; font-size:15px; font-weight:700; box-shadow:0 6px 10px rgba(5,150,105,.6), 0 2px 4px rgba(0,0,0,.25);">
        {{ strtoupper(substr($userName, 0, 2)) }}
    </span>
       {{-- Nama --}}
<span style="font-size:16px; font-weight:700; color:#0f172a; white-space:nowrap;">
    {{ $userName }}
</span>

        {{-- Chevron --}}
        <svg id="user-chevron" class="w-4 h-4 text-slate-700 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Dropdown --}}
    <div id="user-menu" class="hidden absolute right-0 mt-2 w-64 rounded-2xl border border-slate-200 bg-white shadow-xl z-50 p-2">
        <a href="#" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm text-slate-800 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                <circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M4 21c0-4 4-7 8-7s8 3 8 7"/>
            </svg>
            Profil Saya
        </a>

        <a href="#" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm text-slate-800 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                <circle cx="12" cy="12" r="3"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.7 1.7 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.8-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1.1-1.5 1.7 1.7 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.8 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1a1.7 1.7 0 001.5-1.1 1.7 1.7 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.8.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.8V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z"/>
            </svg>
            Pengaturan Akun
        </a>

        <div class="my-1.5 border-t border-slate-200"></div>

        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-medium text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer text-left">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</div>
@endauth
</div>
</header>

    <script>
    document.addEventListener('click', function (e) {
        const wrapper = document.getElementById('user-wrapper');
        const menu = document.getElementById('user-menu');
        if (wrapper && menu && !wrapper.contains(e.target)) {
            menu.classList.add('hidden');
            document.getElementById('user-chevron')?.classList.remove('rotate-180');
        }
    });
</script>
