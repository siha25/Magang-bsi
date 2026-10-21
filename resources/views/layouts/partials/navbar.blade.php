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

        <div class="flex items-center gap-2.5 sm:gap-3 px-2.5 sm:px-3.5 py-1.5 rounded-2xl bg-emerald-50/60 border border-emerald-100/80">
            {{-- Avatar Pengguna --}}
            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-xs shadow-emerald-600/20 shrink-0">
                @if($user && !empty($user->name))
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @else
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
            </div>

            {{-- Nama Pengguna & Peran --}}
            <div class="text-left leading-tight hidden min-[360px]:block">
                <span class="block text-xs sm:text-sm font-bold text-slate-800 truncate max-w-[110px] sm:max-w-[150px]">
                    {{ $userName }}
                </span>
                <span class="block text-[10px] sm:text-[11px] font-medium text-emerald-700 truncate max-w-[110px] sm:max-w-[150px]">
                    {{ $userRole }}
                </span>
            </div>
        </div>

        {{-- Tombol Logout --}}
        @auth
            <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                @csrf
                <button type="submit" 
                        title="Keluar"
                        aria-label="Logout"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:text-white bg-rose-50 hover:bg-rose-600 border border-rose-200/80 hover:border-rose-600 transition-all cursor-pointer shadow-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </form>
        @endauth
    </div>

</header>
