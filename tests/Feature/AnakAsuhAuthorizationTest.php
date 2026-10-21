<?php

namespace Tests\Feature;

use App\Models\AnakAsuh;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AnakAsuhAuthorizationTest extends TestCase
{
    public function test_admin_cabang_can_view_only_anak_asuh_in_its_asrama(): void
    {
        $adminCabang = $this->makeAdminCabang();
        $anakAsuhDiAsramaSendiri = new AnakAsuh(['asrama_id' => 17]);
        $anakAsuhDiAsramaLain = new AnakAsuh(['asrama_id' => 18]);

        $this->assertTrue($adminCabang->can('viewAny', AnakAsuh::class));
        $this->assertTrue($adminCabang->can('view', $anakAsuhDiAsramaSendiri));
        $this->assertFalse($adminCabang->can('view', $anakAsuhDiAsramaLain));
        $this->assertFalse($adminCabang->can('create', AnakAsuh::class));
        $this->assertFalse($adminCabang->can('update', $anakAsuhDiAsramaSendiri));
        $this->assertFalse($adminCabang->can('delete', $anakAsuhDiAsramaSendiri));
    }

    public function test_admin_cabang_is_forbidden_from_posting_anak_asuh(): void
    {
        $this->actingAs($this->makeAdminCabang())
            ->get('/anak-asuh/tambah')
            ->assertForbidden();

        $this->actingAs($this->makeAdminCabang())
            ->post('/anak-asuh', [])
            ->assertForbidden();
    }

    public function test_admin_cabang_is_forbidden_from_putting_anak_asuh(): void
    {
        $this->bindAnakAsuhRouteForDatabaseFreeTest();

        $this->actingAs($this->makeAdminCabang())
            ->get('/anak-asuh/101/edit')
            ->assertForbidden();

        $this->actingAs($this->makeAdminCabang())
            ->put('/anak-asuh/101', [])
            ->assertForbidden();
    }

    public function test_admin_cabang_is_forbidden_from_patching_anak_asuh_status(): void
    {
        $this->bindAnakAsuhRouteForDatabaseFreeTest();

        $this->actingAs($this->makeAdminCabang())
            ->patch('/anak-asuh/101/toggle-status')
            ->assertForbidden();
    }

    public function test_admin_cabang_can_reach_daily_activity_validation(): void
    {
        $this->actingAs($this->makeAdminCabang())
            ->post('/kegiatan-harian', [])
            ->assertSessionHasErrors(['asrama_id']);
    }

    public function test_pimpinan_pusat_can_read_but_cannot_mutate_anak_asuh(): void
    {
        $pimpinanPusat = new User([
            'role' => User::ROLE_PIMPINAN_PUSAT,
            'status_aktif' => true,
        ]);

        $anakAsuh = new AnakAsuh(['asrama_id' => 17]);

        $this->assertTrue($pimpinanPusat->can('viewAny', AnakAsuh::class));
        $this->assertTrue($pimpinanPusat->can('view', $anakAsuh));
        $this->assertFalse($pimpinanPusat->can('create', AnakAsuh::class));
        $this->assertFalse($pimpinanPusat->can('update', $anakAsuh));
        $this->assertFalse($pimpinanPusat->can('delete', $anakAsuh));

        $this->actingAs($pimpinanPusat)
            ->post('/anak-asuh', [])
            ->assertForbidden();
    }

    private function makeAdminCabang(): User
    {
        return new User([
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => 17,
            'status_aktif' => true,
        ]);
    }

    private function bindAnakAsuhRouteForDatabaseFreeTest(): void
    {
        Route::bind('anakAsuh', static function (string $id): AnakAsuh {
            return new AnakAsuh([
                'id' => (int) $id,
                'asrama_id' => 17,
                'status_aktif' => true,
            ]);
        });
    }
}
