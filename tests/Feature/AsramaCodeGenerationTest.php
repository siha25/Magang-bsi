<?php

namespace Tests\Feature;

use App\Models\Asrama;
use App\Models\User;
use App\Services\AsramaCodeGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AsramaCodeGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pusat_receives_sequential_codes_without_overwriting_legacy_asrama(): void
    {
        DB::table('asrama')->insert([
            'kode_asrama' => 'RH-001',
            'nama_asrama' => 'Asrama lama',
            'status_aktif' => true,
        ]);

        $this->actingAs($this->makeAdminPusat())
            ->post(route('asrama.store'), [
                'kode_asrama' => 'ASR-900',
                'nama_asrama' => 'Asrama Baru 1',
            ])
            ->assertRedirect(route('asrama.index'));

        $this->assertDatabaseHas('asrama', [
            'kode_asrama' => 'ASR-001',
            'nama_asrama' => 'Asrama Baru 1',
        ]);
        $this->assertDatabaseHas('asrama', [
            'kode_asrama' => 'RH-001',
            'nama_asrama' => 'Asrama lama',
        ]);

        $this->post(route('asrama.store'), [
            'kode_asrama' => 'ASR-001',
            'nama_asrama' => 'Asrama Baru 2',
        ])->assertRedirect(route('asrama.index'));

        $this->assertDatabaseHas('asrama', [
            'kode_asrama' => 'ASR-002',
            'nama_asrama' => 'Asrama Baru 2',
        ]);
    }

    public function test_allocated_code_is_not_reused_after_asrama_is_deleted(): void
    {
        $this->actingAs($this->makeAdminPusat())
            ->post(route('asrama.store'), ['nama_asrama' => 'Asrama pertama'])
            ->assertRedirect(route('asrama.index'));

        $asramaPertama = Asrama::query()->where('nama_asrama', 'Asrama pertama')->firstOrFail();
        $asramaPertama->delete();

        $this->post(route('asrama.store'), ['nama_asrama' => 'Asrama berikutnya'])
            ->assertRedirect(route('asrama.index'));

        $this->assertDatabaseHas('asrama', [
            'kode_asrama' => 'ASR-002',
            'nama_asrama' => 'Asrama berikutnya',
        ]);
    }

    public function test_submitted_code_does_not_change_when_asrama_is_updated(): void
    {
        $asrama = new Asrama(['nama_asrama' => 'Nama lama']);
        $asrama->kode_asrama = 'ASR-003';
        $asrama->save();

        $this->actingAs($this->makeAdminPusat())
            ->put(route('asrama.update', $asrama), [
                'kode_asrama' => 'ASR-999',
                'nama_asrama' => 'Nama baru',
            ])
            ->assertRedirect(route('asrama.index'));

        $this->assertDatabaseHas('asrama', [
            'id' => $asrama->id,
            'kode_asrama' => 'ASR-003',
            'nama_asrama' => 'Nama baru',
        ]);
    }

    public function test_existing_asr_codes_initialize_the_counter_without_reusing_a_number(): void
    {
        DB::table('asrama')->insert([
            'kode_asrama' => 'ASR-007',
            'nama_asrama' => 'Asrama resmi lama',
            'status_aktif' => true,
        ]);

        Schema::dropIfExists('asrama_code_counters');

        $migration = require database_path('migrations/2026_10_21_045154_create_asrama_code_counters_table.php');
        $migration->up();

        $this->assertSame('ASR-008', app(AsramaCodeGenerator::class)->next());
    }

    public function test_only_admin_pusat_can_create_or_update_asrama(): void
    {
        Route::bind('asrama', static function (string $id): Asrama {
            $asrama = new Asrama;
            $asrama->setAttribute('id', (int) $id);

            return $asrama;
        });

        foreach ([User::ROLE_ADMIN_CABANG, User::ROLE_PIMPINAN_PUSAT] as $role) {
            $this->actingAs(new User([
                'role' => $role,
                'asrama_id' => 1,
                'status_aktif' => true,
            ]))
                ->post(route('asrama.store'), [])
                ->assertForbidden();

            $this->actingAs(new User([
                'role' => $role,
                'asrama_id' => 1,
                'status_aktif' => true,
            ]))
                ->put(route('asrama.update', 1), [])
                ->assertForbidden();
        }
    }

    public function test_asrama_forms_do_not_offer_an_editable_code_field(): void
    {
        $this->actingAs($this->makeAdminPusat());
        $asrama = new Asrama([
            'nama_asrama' => 'Asrama Resmi',
        ]);
        $asrama->kode_asrama = 'ASR-003';
        $asrama->save();

        $this->get(route('asrama.create'))
            ->assertOk()
            ->assertDontSee('name="kode_asrama"', false);

        $this->get(route('asrama.edit', $asrama))
            ->assertOk()
            ->assertSee('ASR-003')
            ->assertDontSee('name="kode_asrama"', false);
    }

    private function makeAdminPusat(): User
    {
        return new User([
            'role' => User::ROLE_ADMIN_PUSAT,
            'status_aktif' => true,
        ]);
    }
}
