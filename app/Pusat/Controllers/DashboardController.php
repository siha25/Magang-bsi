<?php

// BAGIAN: PENGURUS PUSAT - Controller Dashboard Analitik & Ringkasan Pusat

namespace App\Pusat\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asrama;
use App\Pusat\Services\MonitoringService;
use App\Shared\Types\EvaluationStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected MonitoringService $service
    ) {}

    public function index(Request $request): View
    {
        $kkm = (float) $request->input('kkm', EvaluationStatus::KKM_DEFAULT);
        $dashboardData = $this->service->getDashboardData(['kkm' => $kkm]);
        $asramas = Asrama::withCount('anakAsuh')->get();

        return view('pusat.views.dashboard.index', compact('dashboardData', 'asramas', 'kkm'));
    }
}
