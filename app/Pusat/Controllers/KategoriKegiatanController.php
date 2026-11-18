<?php

// BAGIAN: PENGURUS PUSAT - Pengelolaan Master Kategori Kegiatan Multi-Cabang

namespace App\Pusat\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KategoriKegiatan;
use App\Models\KegiatanHarian;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriKegiatanController extends Controller
{
    /**
     * Dapatkan daftar seluruh kategori kegiatan (JSON untuk modal & drawer AJAX).
     */
    public function index(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson() || $request->ajax()) {
            if (! KategoriKegiatan::hasTable()) {
                $countsByKode = KegiatanHarian::query()
                    ->selectRaw('kategori, count(*) as total')
                    ->groupBy('kategori')
                    ->pluck('total', 'kategori');

                $kategoris = KategoriKegiatan::defaultCollection()
                    ->map(function (KategoriKegiatan $item) use ($countsByKode) {
                        return [
                            'id' => $item->id,
                            'nama' => $item->nama,
                            'kode' => $item->kode,
                            'deskripsi' => $item->deskripsi,
                            'is_active' => (bool) $item->is_active,
                            'kegiatan_harian_count' => (int) ($countsByKode[$item->kode] ?? 0),
                            'created_at' => null,
                        ];
                    })
                    ->values();

                return response()->json([
                    'success' => true,
                    'data' => $kategoris,
                ]);
            }

            $kategoris = KategoriKegiatan::query()
                ->withCount('kegiatanHarian')
                ->orderBy('nama')
                ->get()
                ->map(function (KategoriKegiatan $item) {
                    return [
                        'id' => $item->id,
                        'nama' => $item->nama,
                        'kode' => $item->kode,
                        'deskripsi' => $item->deskripsi,
                        'is_active' => (bool) $item->is_active,
                        'kegiatan_harian_count' => $item->kegiatan_harian_count,
                        'created_at' => $item->created_at?->format('d M Y H:i'),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $kategoris,
            ]);
        }

        return redirect()->route('pusat.monitoring-kegiatan.index');
    }

    /**
     * Simpan kategori kegiatan baru oleh Admin Pusat.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori_kegiatan', 'nama')->whereNull('deleted_at'),
            ],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama kategori kegiatan wajib diisi.',
            'nama.unique' => 'Nama kategori kegiatan sudah terdaftar.',
            'nama.max' => 'Nama kategori kegiatan maksimal 100 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.',
        ]);

        $namaInput = trim($validated['nama']);

        // Jika nama kategori pernah dihapus (soft-deleted), pulihkan kembali
        $trashed = KategoriKegiatan::onlyTrashed()->where('nama', $namaInput)->first();
        if ($trashed) {
            $trashed->restore();
            $trashed->update([
                'deskripsi' => ! empty($validated['deskripsi']) ? trim($validated['deskripsi']) : null,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori kegiatan "'.$trashed->nama.'" berhasil dipulihkan kembali.',
                'data' => [
                    'id' => $trashed->id,
                    'nama' => $trashed->nama,
                    'kode' => $trashed->kode,
                    'deskripsi' => $trashed->deskripsi,
                    'is_active' => (bool) $trashed->is_active,
                    'kegiatan_harian_count' => $trashed->kegiatanHarian()->count(),
                ],
            ], 200);
        }

        $kode = KategoriKegiatan::generateUniqueKode($namaInput);

        $kategori = KategoriKegiatan::create([
            'nama' => $namaInput,
            'kode' => $kode,
            'deskripsi' => ! empty($validated['deskripsi']) ? trim($validated['deskripsi']) : null,
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori kegiatan "'.$kategori->nama.'" berhasil ditambahkan.',
            'data' => [
                'id' => $kategori->id,
                'nama' => $kategori->nama,
                'kode' => $kategori->kode,
                'deskripsi' => $kategori->deskripsi,
                'is_active' => (bool) $kategori->is_active,
                'kegiatan_harian_count' => 0,
            ],
        ], 201);
    }

    /**
     * Perbarui data kategori kegiatan.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $kategori = KategoriKegiatan::findOrFail($id);

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori_kegiatan', 'nama')->ignore($id)->whereNull('deleted_at'),
            ],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama kategori kegiatan wajib diisi.',
            'nama.unique' => 'Nama kategori kegiatan sudah terdaftar.',
            'nama.max' => 'Nama kategori kegiatan maksimal 100 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.',
        ]);

        $kategori->update([
            'nama' => trim($validated['nama']),
            'deskripsi' => ! empty($validated['deskripsi']) ? trim($validated['deskripsi']) : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori kegiatan "'.$kategori->nama.'" berhasil diperbarui.',
            'data' => [
                'id' => $kategori->id,
                'nama' => $kategori->nama,
                'kode' => $kategori->kode,
                'deskripsi' => $kategori->deskripsi,
                'is_active' => (bool) $kategori->is_active,
                'kegiatan_harian_count' => $kategori->kegiatanHarian()->count(),
            ],
        ]);
    }

    /**
     * Toggle status aktif/nonaktif kategori kegiatan.
     */
    public function toggleStatus(int $id): JsonResponse
    {
        $kategori = KategoriKegiatan::findOrFail($id);
        $kategori->is_active = ! $kategori->is_active;
        $kategori->save();

        $statusText = $kategori->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return response()->json([
            'success' => true,
            'message' => "Kategori kegiatan \"{$kategori->nama}\" berhasil {$statusText}.",
            'is_active' => (bool) $kategori->is_active,
        ]);
    }

    /**
     * Hapus kategori kegiatan.
     * Jika sudah digunakan pada data kegiatan harian, dilarang hapus permanen & tawarkan penonaktifan.
     */
    public function destroy(int $id): JsonResponse
    {
        $kategori = KategoriKegiatan::findOrFail($id);

        $usageCount = KegiatanHarian::query()
            ->where('kategori_kegiatan_id', $id)
            ->orWhere('kategori', $kategori->kode)
            ->count();

        if ($usageCount > 0) {
            return response()->json([
                'success' => false,
                'can_deactivate' => (bool) $kategori->is_active,
                'message' => "Kategori kegiatan \"{$kategori->nama}\" sudah digunakan pada {$usageCount} data kegiatan harian dan tidak dapat dihapus permanen. Anda dapat menonaktifkannya agar tidak muncul lagi pada pilihan form cabang.",
            ], 422);
        }

        $nama = $kategori->nama;
        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => "Kategori kegiatan \"{$nama}\" berhasil dihapus.",
        ]);
    }
}
