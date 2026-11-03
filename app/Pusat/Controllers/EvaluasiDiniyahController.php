<?php

// BAGIAN: PENGURUS PUSAT - Evaluasi Diniyah Ringan Santri Bawah KKM

namespace App\Pusat\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asrama;
use App\Models\EvaluasiDiniyah;
use App\Pusat\Services\MonitoringService;
use App\Shared\Types\EvaluationStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluasiDiniyahController extends Controller
{
    public function __construct(
        protected MonitoringService $service
    ) {}

    public function index(Request $request): View
    {
        $kkm = (float) $request->input('kkm', EvaluationStatus::KKM_DEFAULT);
        $filters = [
            'cabang_id' => $request->input('cabang_id'),
            'daerah' => $request->string('daerah')->trim()->toString(),
            'status_tindak_lanjut' => $request->string('status_tindak_lanjut')->trim()->toString(),
        ];

        $data = $this->service->getEvaluasiRinganData($filters, $kkm);

        $cabangOptions = Asrama::where('status_aktif', true)->orderBy('nama_asrama')->get();
        $daerahOptions = Asrama::query()
            ->whereNotNull('wilayah')
            ->where('wilayah', '!=', '')
            ->distinct()
            ->orderBy('wilayah')
            ->pluck('wilayah');

        $statusOptions = [
            EvaluationStatus::TINDAK_LANJUT_BELUM => 'Belum Ditindaklanjuti',
            EvaluationStatus::TINDAK_LANJUT_PERHATIAN => 'Perlu Perhatian Khusus',
            EvaluationStatus::TINDAK_LANJUT_BIMBINGAN => 'Sedang Dalam Bimbingan',
            EvaluationStatus::TINDAK_LANJUT_TUNTAS => 'Tuntas / Remedial Berhasil',
        ];

        return view('pusat.views.evaluasi-diniyah.index', array_merge($data, [
            'filters' => $filters,
            'cabangOptions' => $cabangOptions,
            'daerahOptions' => $daerahOptions,
            'statusOptions' => $statusOptions,
        ]));
    }

    /**
     * Menyimpan catatan evaluasi dan status tindak lanjut untuk santri dengan nilai kurang.
     */
    public function simpanCatatan(Request $request, int $id): RedirectResponse
    {
        $evaluasi = EvaluasiDiniyah::findOrFail($id);

        $validated = $request->validate([
            'catatan_evaluasi_pusat' => ['required', 'string', 'max:2000'],
            'status_tindak_lanjut' => ['required', 'string', 'max:50'],
        ], [
            'catatan_evaluasi_pusat.required' => 'Catatan evaluasi bimbingan wajib diisi.',
            'status_tindak_lanjut.required' => 'Status tindak lanjut wajib dipilih.',
        ]);

        $this->service->simpanCatatanEvaluasi(
            $evaluasi,
            $validated['catatan_evaluasi_pusat'],
            $validated['status_tindak_lanjut'],
            (int) Auth::id()
        );

        return back()->with('success', "Catatan evaluasi dan status tindak lanjut untuk santri '{$evaluasi->anakAsuh?->nama_lengkap}' berhasil disimpan.");
    }
}
