<?php

// BAGIAN: PENGURUS PUSAT - Pengelolaan Akun & Data Multi-Cabang

namespace App\Pusat\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asrama;
use App\Pusat\Services\AkunCabangService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelolaCabangController extends Controller
{
    public function __construct(
        protected AkunCabangService $service
    ) {}

    public function index(Request $request): View
    {
        $cari = $request->string('cari')->trim()->toString();
        $daerah = $request->string('daerah')->trim()->toString();
        $status = $request->string('status')->trim()->toString();

        $query = Asrama::query()->with(['users', 'anakAsuh']);

        if ($cari !== '') {
            $query->where(function ($q) use ($cari): void {
                $q->where('nama_asrama', 'like', "%{$cari}%")
                    ->orWhere('kode_asrama', 'like', "%{$cari}%")
                    ->orWhere('penanggung_jawab', 'like', "%{$cari}%")
                    ->orWhereHas('users', fn ($sq) => $sq->where('email', 'like', "%{$cari}%"));
            });
        }

        if ($daerah !== '') {
            $query->where('wilayah', $daerah);
        }

        if ($status === 'aktif') {
            $query->where('status_aktif', true);
        } elseif ($status === 'nonaktif') {
            $query->where('status_aktif', false);
        }

        $cabangs = $query->latest('id')->paginate(10)->withQueryString();

        $stats = [
            'total' => Asrama::count(),
            'aktif' => Asrama::where('status_aktif', true)->count(),
            'nonaktif' => Asrama::where('status_aktif', false)->count(),
            'total_daerah' => Asrama::whereNotNull('wilayah')->where('wilayah', '!=', '')->distinct()->count('wilayah'),
        ];

        $daerahOptions = Asrama::query()
            ->whereNotNull('wilayah')
            ->where('wilayah', '!=', '')
            ->distinct()
            ->orderBy('wilayah')
            ->pluck('wilayah');

        return view('pusat.views.kelola-cabang.index', compact(
            'cabangs',
            'stats',
            'daerahOptions',
            'cari',
            'daerah',
            'status'
        ));
    }

    public function create(): View
    {
        return view('pusat.views.kelola-cabang.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_cabang' => ['required', 'string', 'max:255'],
            'wilayah' => ['required', 'string', 'max:100'],
            'penanggung_jawab' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string', 'max:1000'],
        ], [
            'nama_cabang.required' => 'Nama cabang wajib diisi.',
            'wilayah.required' => 'Daerah / Kota penempatan cabang wajib diisi.',
            'penanggung_jawab.required' => 'Nama pengurus penanggung jawab cabang wajib diisi.',
            'email.required' => 'Email login untuk akun cabang wajib diisi.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'password.required' => 'Password awal akun cabang wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $result = $this->service->createCabangDanAkun($validated);

        return redirect()->route('pusat.kelola-cabang.index')
            ->with('success', "Cabang '{$result['cabang']->nama_asrama}' ({$result['cabang']->kode_asrama}) beserta akun login '{$result['user']->email}' berhasil dibuat.");
    }

    public function edit(int $id): View
    {
        $cabang = Asrama::with('users')->findOrFail($id);

        return view('pusat.views.kelola-cabang.edit', compact('cabang'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $asrama = Asrama::with('users')->findOrFail($id);
        $user = $asrama->users()->first();

        $validated = $request->validate([
            'nama_cabang' => ['required', 'string', 'max:255'],
            'wilayah' => ['required', 'string', 'max:100'],
            'penanggung_jawab' => ['required', 'string', 'max:150'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                $user ? Rule::unique('users')->ignore($user->id) : 'unique:users,email',
            ],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->service->updateCabangDanAkun($asrama, $validated);

        return redirect()->route('pusat.kelola-cabang.index')
            ->with('success', "Data cabang '{$asrama->nama_asrama}' berhasil diperbarui.");
    }

    public function resetPassword(Request $request, int $id): RedirectResponse
    {
        $asrama = Asrama::findOrFail($id);

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $this->service->resetPasswordCabang($asrama, $validated['password']);

        return back()->with('success', "Password akun pengurus cabang '{$asrama->nama_asrama}' berhasil direset.");
    }

    public function toggleStatus(int $id): RedirectResponse
    {
        $asrama = Asrama::findOrFail($id);
        $newStatus = $this->service->toggleStatusCabang($asrama);

        $statusTeks = $newStatus ? 'diaktifkan kembali' : 'dinonaktifkan';

        return back()->with('success', "Status cabang '{$asrama->nama_asrama}' dan akun loginnya berhasil {$statusTeks}.");
    }
}
