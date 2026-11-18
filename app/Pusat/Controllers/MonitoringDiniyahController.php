<?php

// BAGIAN: PENGURUS PUSAT - Monitoring Pengetahuan Diniyah Seluruh Cabang

namespace App\Pusat\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asrama;
use App\Models\EvaluasiDiniyah;
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
            'jenjang' => $request->string('jenjang')->trim()->toString(),
            'tanggal_mulai' => $request->input('tanggal_mulai'),
            'tanggal_selesai' => $request->input('tanggal_selesai'),
            'materi' => $request->string('materi')->trim()->toString(),
            'cari' => $request->string('cari')->trim()->toString(),
        ];

        $data = $this->service->getMonitoringDiniyahData($filters);

        $cabangOptions = Asrama::where('status_aktif', true)->orderBy('nama_asrama')->get();
        $daerahOptions = Asrama::query()
            ->whereNotNull('wilayah')
            ->where('wilayah', '!=', '')
            ->distinct()
            ->orderBy('wilayah')
            ->pluck('wilayah');
        $jenjangOptions = ['SD', 'SMP', 'SMA'];

        // Gabungkan kategori Pengetahuan Diniyah standar dan kategori non-hafalan di DB
        $existingKategori = EvaluasiDiniyah::pengetahuanDiniyah()
            ->whereNotNull('jenis_evaluasi')
            ->where('jenis_evaluasi', '!=', '')
            ->distinct()
            ->pluck('jenis_evaluasi')
            ->all();

        $materiOptions = array_values(array_unique(array_merge(
            EvaluationStatus::KATEGORI_PENGETAHUAN_DINIYAH,
            $existingKategori
        )));

        return view('pusat.views.monitoring-diniyah.index', array_merge($data, [
            'filters' => $filters,
            'cabangOptions' => $cabangOptions,
            'daerahOptions' => $daerahOptions,
            'jenjangOptions' => $jenjangOptions,
            'materiOptions' => $materiOptions,
        ]));
    }
}
