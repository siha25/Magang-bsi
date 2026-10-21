<?php

namespace Tests\Unit;

use App\Models\Asrama;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    /**
     * Memastikan helper role pada User model bekerja sesuai peran.
     */
    public function test_user_role_helpers(): void
    {
        $adminPusat = new User(['role' => User::ROLE_ADMIN_PUSAT]);
        $this->assertTrue($adminPusat->isAdminPusat());
        $this->assertFalse($adminPusat->isAdminAsrama());
        $this->assertFalse($adminPusat->isPimpinan());

        $adminAsrama = new User(['role' => User::ROLE_ADMIN_ASRAMA, 'asrama_id' => 5]);
        $this->assertFalse($adminAsrama->isAdminPusat());
        $this->assertTrue($adminAsrama->isAdminAsrama());
        $this->assertFalse($adminAsrama->isPimpinan());

        $pimpinan = new User(['role' => User::ROLE_PIMPINAN]);
        $this->assertFalse($pimpinan->isAdminPusat());
        $this->assertFalse($pimpinan->isAdminAsrama());
        $this->assertTrue($pimpinan->isPimpinan());
    }

    /**
     * Memastikan helper canManageAsrama bekerja dengan benar.
     */
    public function test_can_manage_asrama_logic(): void
    {
        $adminPusat = new User(['role' => User::ROLE_ADMIN_PUSAT]);
        $this->assertTrue($adminPusat->canManageAsrama(1));
        $this->assertTrue($adminPusat->canManageAsrama(999));

        $adminAsrama = new User(['role' => User::ROLE_ADMIN_ASRAMA, 'asrama_id' => 10]);
        $this->assertTrue($adminAsrama->canManageAsrama(10));
        $this->assertFalse($adminAsrama->canManageAsrama(11));

        $pimpinan = new User(['role' => User::ROLE_PIMPINAN]);
        $this->assertFalse($pimpinan->canManageAsrama(10));
    }

    /**
     * Memastikan relasi didefinisikan dengan benar pada model.
     */
    public function test_user_and_asrama_relationship_methods(): void
    {
        $user = new User;
        $this->assertInstanceOf(BelongsTo::class, $user->asrama());

        $asrama = new Asrama;
        $this->assertInstanceOf(HasMany::class, $asrama->users());
    }
}
