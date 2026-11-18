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
            $userRole = 'Admin Pendik';
        } elseif ($user->isAdminCabang()) {
            $userRole = 'Staff Cabang';
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
                    'message' => 'Status pengajuan hafalan telah disetujui oleh Admin Pendik.',
                    'read' => true,
                    'time' => 'Hari ini',
                ],
            ];
        } elseif ($user->isPimpinanPusat()) {
            $userRole = 'Manager';
        } else {
            $userRole = $user->role_label ?? ucwords(str_replace('_', ' ', (string) $user->role));
        }
    }
    $unreadNotifications = collect($notifications)->where('read', false)->count();
@endphp

{{-- Topbar / Header Minimalis Pendidikan Ansuh --}}
<header class="sticky top-0 z-30 h-16 sm:h-20 bg-white/95 backdrop-blur-md border-b border-emerald-100/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between transition-all">
    
    {{-- Sisi Kiri: Judul Halaman Dinamis (Tanpa elemen atau tombol yang tidak semestinya) --}}
    <div class="flex items-center min-w-0">

        <button type="button" onclick="toggleSidebar()" aria-label="Buka/tutup sidebar"
            class="flex items-center justify-center w-[42px] h-[42px] mr-3 border border-emerald-200 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors cursor-pointer shrink-0 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
        class="flex items-center gap-3 py-[7px] pr-5 pl-[7px] bg-emerald-50/80 hover:bg-emerald-100/70 border border-emerald-200 rounded-full transition-colors cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
        
        {{-- Avatar --}}
    <span style="display:flex; align-items:center; justify-content:center; width:42px; height:42px; min-width:42px; border-radius:9999px; background:linear-gradient(135deg,#0f766e,#059669); color:#ffffff; font-size:15px; font-weight:700; box-shadow:0 6px 10px rgba(5,150,105,.6), 0 2px 4px rgba(0,0,0,.25);">
        {{ strtoupper(substr($userName, 0, 2)) }}
    </span>
       {{-- Nama --}}
<span class="text-base font-bold text-slate-900 whitespace-nowrap">
    {{ $userName }}
</span>

        {{-- Chevron --}}
        <svg id="user-chevron" class="w-4 h-4 text-slate-700 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Dropdown --}}
    @php
        $isPusatUser = $user && ($user->isAdminPusat() || $user->isPimpinanPusat());
        $isCabangUser = $user && $user->isAdminCabang();
        $profilUrl = $isPusatUser ? route('pusat.profil.index') : ($isCabangUser ? route('cabang.profil.index') : '#');
        $pengaturanUrl = $isPusatUser ? route('pusat.pengaturan.index') : ($isCabangUser ? route('cabang.pengaturan.index') : '#');
        $localeRoute = $isPusatUser ? route('pusat.ganti-bahasa') : ($isCabangUser ? route('cabang.pengaturan.ganti-bahasa') : null);
        $currentLocale = app()->getLocale();
    @endphp
    <div id="user-menu" class="hidden absolute right-0 mt-2 w-72 rounded-2xl border border-emerald-100 bg-white shadow-xl shadow-slate-900/10 z-50 p-2">
        <div class="p-3 bg-gradient-to-br from-emerald-50/70 to-teal-50/40 rounded-xl border border-emerald-100/70 mb-2">
            <div class="flex items-center justify-between gap-2 mb-1">
                <span class="text-xs font-bold text-slate-900 truncate">{{ $userName }}</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-white text-emerald-800 border border-emerald-200 shrink-0">
                    {{ $userRole }}
                </span>
            </div>
            <p class="text-[11px] text-emerald-700 font-medium truncate">
                {{ $user?->email ?? '' }}
            </p>
        </div>

        <div class="space-y-1">
            <a href="{{ $profilUrl }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('pusat.profil.*') || request()->routeIs('cabang.profil.*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('pusat.profil.*') || request()->routeIs('cabang.profil.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>{{ __('Profil Akun') }}</span>
            </a>

            <a href="{{ $pengaturanUrl }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('pusat.pengaturan.*') || request()->routeIs('cabang.pengaturan.*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('pusat.pengaturan.*') || request()->routeIs('cabang.pengaturan.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ __('Pengaturan') }}</span>
            </a>
        </div>

        @if($localeRoute)
        <div class="my-2 pt-2 border-t border-slate-100 px-3 py-1 flex items-center justify-between">
            <span class="text-[11px] font-semibold text-slate-500 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                </svg>
                {{ __('Bahasa') }}
            </span>

            <div class="inline-flex rounded-lg bg-slate-100 p-0.5 text-[10px] font-bold">
                <form action="{{ $localeRoute }}" method="POST" class="inline m-0">
                    @csrf
                    <input type="hidden" name="locale" value="id">
                    <button type="submit" class="px-2 py-0.5 rounded-md transition-all cursor-pointer {{ $currentLocale === 'id' ? 'bg-white text-emerald-800 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-800' }}">
                        ID
                    </button>
                </form>
                <form action="{{ $localeRoute }}" method="POST" class="inline m-0">
                    @csrf
                    <input type="hidden" name="locale" value="en">
                    <button type="submit" class="px-2 py-0.5 rounded-md transition-all cursor-pointer {{ $currentLocale === 'en' ? 'bg-white text-emerald-800 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-800' }}">
                        EN
                    </button>
                </form>
            </div>
        </div>
        @endif

        <div class="pt-1 border-t border-slate-100">
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors cursor-pointer text-left">
                    <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>{{ __('Keluar') }}</span>
                </button>
            </form>
        </div>
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
