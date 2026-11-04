<?php

// BAGIAN: PENGURUS PUSAT - Monitoring Pengetahuan Diniyah Seluruh Cabang

namespace App\Pusat\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asrama;
use App\Pusat\Services\MonitoringService;
use App\Shared\Types\EvaluationStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MonitoringDiniyahController extends Controller
{
    public function __construct(
        protected MonitoringService $service
    ) {}

    public function index(Request $request): View
    {
        $filters = [
            'cabang_id' => $request->input('cabang_id'),
            'daerah' => $request->string('daerah')->trim()->toString(),
            'tanggal_mulai' => $request->input('tanggal_mulai'),
            'tanggal_selesai' => $request->input('tanggal_selesai'),
            'materi' => $request->string('materi')->trim()->toString(),
        ];

        $data = $this->service->getMonitoringDiniyahData($filters);

        $cabangOptions = Asrama::where('status_aktif', true)->orderBy('nama_asrama')->get();
        $daerahOptions = Asrama::query()
            ->whereNotNull('wilayah')
            ->where('wilayah', '!=', '')
            ->distinct()
            ->orderBy('wilayah')
            ->pluck('wilayah');

        $materiOptions = EvaluationStatus::DAFTAR_KATEGORI;

        return view('pusat.views.monitoring-diniyah.index', array_merge($data, [
            'filters' => $filters,
            'cabangOptions' => $cabangOptions,
            'daerahOptions' => $daerahOptions,
            'materiOptions' => $materiOptions,
        ]));
    }
}
