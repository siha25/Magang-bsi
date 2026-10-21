<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PrimaryAdminProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirmed_primary_admin_can_log_in(): void
    {
        $primaryAdmin = User::query()->where('is_primary_admin', true)->firstOrFail();

        $this->post('/login', [
            'email' => $primaryAdmin->email,
            'password' => 'isolated-testing-password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($primaryAdmin);
    }

    public function test_primary_admin_cannot_be_deleted(): void
    {
        $primaryAdmin = User::query()->where('is_primary_admin', true)->firstOrFail();

        try {
            $primaryAdmin->delete();
            $this->fail('The primary administrator must not be deleted.');
        } catch (AuthorizationException) {
            $this->assertModelExists($primaryAdmin);
        }
    }

    public function test_primary_admin_cannot_be_disabled(): void
    {
        $primaryAdmin = User::query()->where('is_primary_admin', true)->firstOrFail();
        $primaryAdmin->status_aktif = false;

        try {
            $primaryAdmin->save();
            $this->fail('The primary administrator must not be disabled.');
        } catch (AuthorizationException) {
            $this->assertDatabaseHas('users', [
                'id' => $primaryAdmin->id,
                'status_aktif' => true,
                'is_primary_admin' => true,
            ]);
        }
    }

    public function test_primary_admin_role_cannot_be_changed(): void
    {
        $primaryAdmin = User::query()->where('is_primary_admin', true)->firstOrFail();
        $primaryAdmin->role = User::ROLE_PIMPINAN_PUSAT;

        try {
            $primaryAdmin->save();
            $this->fail('The primary administrator role must not be changed.');
        } catch (AuthorizationException) {
            $this->assertDatabaseHas('users', [
                'id' => $primaryAdmin->id,
                'role' => User::ROLE_ADMIN_PUSAT,
                'is_primary_admin' => true,
            ]);
        }
    }

    public function test_primary_admin_marker_cannot_be_manipulated(): void
    {
        $primaryAdmin = User::query()->where('is_primary_admin', true)->firstOrFail();
        $primaryAdmin->is_primary_admin = false;

        try {
            $primaryAdmin->save();
            $this->fail('The primary administrator marker must not be changed.');
        } catch (AuthorizationException) {
            $this->assertDatabaseHas('users', [
                'id' => $primaryAdmin->id,
                'is_primary_admin' => true,
            ]);
        }
    }

    public function test_another_user_cannot_be_marked_as_primary_admin(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN_PUSAT,
            'status_aktif' => true,
        ]);
        $user->is_primary_admin = true;

        $this->expectException(AuthorizationException::class);

        $user->save();
    }

    public function test_database_rejects_a_second_primary_admin_marker(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN_PUSAT,
            'status_aktif' => true,
        ]);

        $this->expectException(QueryException::class);

        DB::table('users')
            ->where('id', $user->id)
            ->update(['is_primary_admin' => true]);
    }
}
