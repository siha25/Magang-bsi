<?php

// BAGIAN: PENGURUS PUSAT - Monitoring Kegiatan Harian Seluruh Cabang

namespace App\Pusat\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asrama;
use App\Pusat\Services\MonitoringService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MonitoringKegiatanController extends Controller
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
            'kategori' => $request->string('kategori')->trim()->toString(),
        ];

        $data = $this->service->getMonitoringKegiatanData($filters);

        $cabangOptions = Asrama::where('status_aktif', true)->orderBy('nama_asrama')->get();
        $daerahOptions = Asrama::query()
            ->whereNotNull('wilayah')
            ->where('wilayah', '!=', '')
            ->distinct()
            ->orderBy('wilayah')
            ->pluck('wilayah');

        $kategoriOptions = [
            'belajar' => 'Belajar',
            'muhadhoroh' => 'Muhadhoroh',
            'olahraga' => 'Olahraga',
            'acara_donatur' => 'Acara Donatur',
            'bersih_bersih' => 'Bersih-bersih',
        ];

        return view('pusat.views.monitoring-kegiatan.index', array_merge($data, [
            'filters' => $filters,
            'cabangOptions' => $cabangOptions,
            'daerahOptions' => $daerahOptions,
            'kategoriOptions' => $kategoriOptions,
        ]));
    }
}
