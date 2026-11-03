<?php

// BAGIAN: PENGURUS CABANG - Daftar Santri / Anak Asuh Cabang

namespace App\Cabang\Controllers;

use App\Cabang\Services\CabangDataService;
use App\Http\Controllers\Controller;
use App\Models\AnakAsuh;
use App\Shared\Auth\CabangSession;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AnakAsuhController extends Controller
{
    public function __construct(
        protected CabangDataService $service
    ) {}

    public function index(Request $request): View
    {
        $cabangId = CabangSession::getId();
        if (! $cabangId) {
            abort(403, 'Akun cabang belum terhubung dengan asrama.');
        }

        $query = AnakAsuh::query()
            ->where('asrama_id', $cabangId);

        if ($request->filled('cari')) {
            $query->search($request->string('cari'));
        }

        if ($request->filled('jenjang')) {
            $query->where('jenjang_pendidikan', $request->input('jenjang'));
        }

        if ($request->filled('status')) {
            $query->where('status_aktif', $request->input('status') === 'aktif');
        }

        $anakAsuhs = $query->latest()->paginate(10)->withQueryString();
        $cabang = CabangSession::getCabang();

        $stats = [
            'total' => AnakAsuh::where('asrama_id', $cabangId)->count(),
            'aktif' => AnakAsuh::where('asrama_id', $cabangId)->where('status_aktif', true)->count(),
            'total_sd' => AnakAsuh::where('asrama_id', $cabangId)->where('jenjang_pendidikan', 'SD')->count(),
            'total_smp' => AnakAsuh::where('asrama_id', $cabangId)->where('jenjang_pendidikan', 'SMP')->count(),
            'total_sma' => AnakAsuh::where('asrama_id', $cabangId)->where('jenjang_pendidikan', 'SMA')->count(),
        ];

        return view('cabang.views.anak-asuh.index', compact('anakAsuhs', 'cabang', 'stats'));
    }
}
