<?php

namespace Tests\Unit;

use App\Models\KegiatanHarian;
use App\Models\User;
use Tests\TestCase;

class KegiatanHarianTest extends TestCase
{
    public function test_formatted_times_do_not_change_the_stored_values(): void
    {
        $kegiatan = new KegiatanHarian([
            'jam_mulai' => '08:30:45',
            'jam_selesai' => '09:45:30',
        ]);

        $this->assertSame('08:30', $kegiatan->jam_mulai_format);
        $this->assertSame('09:45', $kegiatan->jam_selesai_format);
        $this->assertSame('08:30:45', $kegiatan->getAttributes()['jam_mulai']);
        $this->assertSame('09:45:30', $kegiatan->getAttributes()['jam_selesai']);
    }

    public function test_admin_cabang_query_is_scoped_to_its_asrama(): void
    {
        $this->actingAs(new User([
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => 17,
        ]));

        $query = KegiatanHarian::query()->byRole();

        $this->assertStringContainsString('asrama_id', $query->toSql());
        $this->assertSame([17], $query->getBindings());
    }

    public function test_admin_cabang_without_asrama_cannot_query_activities(): void
    {
        $this->actingAs(new User([
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => null,
        ]));

        $query = KegiatanHarian::query()->byRole();

        $this->assertStringContainsString('1 = 0', $query->toSql());
        $this->assertSame([], $query->getBindings());
    }

    public function test_pimpinan_pusat_can_query_activities_but_cannot_access_mutation_routes(): void
    {
        $this->actingAs(new User(['role' => User::ROLE_PIMPINAN_PUSAT]));

        $query = KegiatanHarian::query()->byRole();

        $this->assertSame([], $query->getBindings());

        $routes = app('router')->getRoutes();

        foreach (['kegiatan-harian.create', 'kegiatan-harian.store'] as $routeName) {
            $middleware = $routes->getByName($routeName)->gatherMiddleware();

            $this->assertContains('role:admin_pusat,admin_cabang', $middleware);
            $this->assertNotContains('role:admin_pusat,admin_cabang,pimpinan_pusat', $middleware);
        }

        $this->assertContains(
            'role:admin_pusat,admin_cabang,pimpinan_pusat',
            $routes->getByName('kegiatan-harian.show')->gatherMiddleware()
        );
    }
}
