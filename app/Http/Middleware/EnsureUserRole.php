<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Memastikan user memiliki salah satu role yang diizinkan.
     *
     * @param  array<int, string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if ($roles === []) {
            return $next($request);
        }

        $allowed = collect($roles)->contains(function (string $role) use ($request): bool {
            return match ($role) {
                'admin_pusat' => $request->user()->isAdminPusat(),
                'admin_cabang' => $request->user()->isAdminCabang(),
                'pimpinan_pusat' => $request->user()->isPimpinanPusat(),
                default => $request->user()->role === $role,
            };
        });

        if (! $allowed) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
