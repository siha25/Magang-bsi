<?php

// BAGIAN: PENGURUS CABANG - Dashboard Operasional Cabang

namespace App\Cabang\Controllers;

use App\Cabang\Services\CabangDataService;
use App\Http\Controllers\Controller;
use App\Shared\Auth\CabangSession;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected CabangDataService $service
    ) {}

    public function index(): View
    {
        $cabangId = CabangSession::getId();
        if (! $cabangId) {
            abort(403, 'Akun Anda belum terikat ke cabang mana pun. Silakan hubungi Administrator Pusat.');
        }

        $cabang = CabangSession::getCabang();
        $stats = $this->service->getDashboardStats($cabangId);

        return view('cabang.views.dashboard.index', compact('cabang', 'stats'));
    }
}
