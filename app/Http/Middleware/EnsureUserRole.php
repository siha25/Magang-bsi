<?php

// BAGIAN: SHARED - Role Guard & Autorisasi Multi-Cabang

namespace App\Http\Middleware;

use App\Shared\Auth\CabangSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Memastikan user memiliki salah satu role yang diizinkan.
     * Jika role salah saat membuka halaman web role lain, arahkan otomatis ke dashboard role-nya.
     *
     * @param  array<int, string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Pastikan cabang_id tersimpan di session untuk pengurus cabang
        if ($user->isAdminCabang() && ! session()->has(CabangSession::SESSION_KEY)) {
            CabangSession::setId((int) $user->asrama_id);
        }

        if ($roles === []) {
            return $next($request);
        }

        $allowed = collect($roles)->contains(function (string $role) use ($user): bool {
            return match ($role) {
                'admin_pusat' => $user->isAdminPusat(),
                'admin_cabang', 'cabang' => $user->isAdminCabang(),
                'pimpinan_pusat', 'pimpinan' => $user->isPimpinanPusat(),
                'pusat' => $user->isAdminPusat() || $user->isPimpinanPusat(),
                default => $user->role === $role,
            };
        });

        if (! $allowed) {
            // Jika salah role membuka modul cabang atau pusat melalui navigasi GET, arahkan ke dashboard role-nya
            if ($request->isMethod('GET') && ($request->is('cabang*') || $request->is('pusat*'))) {
                if ($user->isAdminCabang()) {
                    return redirect()->route('cabang.dashboard')
                        ->with('error', 'Akses dialihkan: Anda tidak memiliki akses ke halaman Pengurus Pusat.');
                }

                return redirect()->route('pusat.dashboard')
                    ->with('error', 'Akses dialihkan: Anda tidak memiliki akses ke halaman Pengurus Cabang.');
            }

            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
