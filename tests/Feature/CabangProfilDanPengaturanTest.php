<?php

namespace Tests\Feature;

use App\Models\Asrama;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CabangProfilDanPengaturanTest extends TestCase
{
    use RefreshDatabase;

    protected function makeCabangUser(): array
    {
        $asrama = new Asrama;
        $asrama->forceFill([
            'kode_asrama' => 'ASR-'.uniqid(),
            'nama_asrama' => 'Asrama Sukajadi Test',
            'wilayah' => 'Bandung Barat',
            'alamat' => 'Jl. Sukajadi No. 123',
            'nomor_telepon' => '081234567890',
            'email' => 'sukajadi@rumahharapan.org',
            'penanggung_jawab' => 'Ustadz Ahmad Fauzi',
            'jam_operasional' => '08:00 - 17:00 WIB',
            'status_aktif' => true,
        ])->save();

        $user = User::create([
            'name' => 'Pengurus Sukajadi',
            'email' => 'sukajadi_'.uniqid().'@example.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ADMIN_CABANG,
            'status_aktif' => true,
            'asrama_id' => $asrama->id,
            'locale' => 'id',
            'theme' => 'light',
            'timezone' => 'Asia/Jakarta',
            'date_format' => 'd/m/Y',
        ]);

        return [$asrama, $user];
    }

    public function test_cabang_can_view_profil_page(): void
    {
        [$asrama, $user] = $this->makeCabangUser();

        $response = $this->actingAs($user)
            ->withSession(['asrama_id' => $asrama->id])
            ->get(route('cabang.profil.index'));

        $response->assertOk();
        $response->assertSee('Asrama Sukajadi Test');
        $response->assertSee('Bandung Barat');
        $response->assertSee('Jl. Sukajadi No. 123');
        $response->assertSee('081234567890');
        $response->assertSee('sukajadi@rumahharapan.org');
        $response->assertSee('Ustadz Ahmad Fauzi');
        $response->assertSee('08:00 - 17:00 WIB');
        $response->assertSee('modal-edit-profil', false);
    }

    public function test_cabang_can_update_profil(): void
    {
        [$asrama, $user] = $this->makeCabangUser();

        $response = $this->actingAs($user)
            ->withSession(['asrama_id' => $asrama->id])
            ->put(route('cabang.profil.update'), [
                'nama_asrama' => 'Asrama Sukajadi Updated',
                'wilayah' => 'Kota Bandung',
                'alamat' => 'Jl. Setiabudi No. 45',
                'nomor_telepon' => '089876543210',
                'email' => 'sukajadi.baru@rumahharapan.org',
                'penanggung_jawab' => 'Ustadz Salman',
                'jam_operasional' => '07:30 - 16:30 WIB',
            ]);

        $response->assertRedirect(route('cabang.profil.index'));
        $response->assertSessionHas('success');

        $asrama->refresh();
        $this->assertEquals('Asrama Sukajadi Updated', $asrama->nama_asrama);
        $this->assertEquals('Kota Bandung', $asrama->wilayah);
        $this->assertEquals('Jl. Setiabudi No. 45', $asrama->alamat);
        $this->assertEquals('089876543210', $asrama->nomor_telepon);
        $this->assertEquals('sukajadi.baru@rumahharapan.org', $asrama->email);
        $this->assertEquals('Ustadz Salman', $asrama->penanggung_jawab);
        $this->assertEquals('07:30 - 16:30 WIB', $asrama->jam_operasional);
    }

    public function test_cabang_profil_update_validates_required_fields(): void
    {
        [$asrama, $user] = $this->makeCabangUser();

        $response = $this->actingAs($user)
            ->withSession(['asrama_id' => $asrama->id])
            ->put(route('cabang.profil.update'), [
                'nama_asrama' => '',
                'wilayah' => '',
                'penanggung_jawab' => '',
                'email' => 'bukan-email',
            ]);

        $response->assertSessionHasErrors(['nama_asrama', 'wilayah', 'penanggung_jawab', 'email']);
    }

    public function test_cabang_can_view_pengaturan_page(): void
    {
        [$asrama, $user] = $this->makeCabangUser();

        $response = $this->actingAs($user)
            ->withSession(['asrama_id' => $asrama->id])
            ->get(route('cabang.pengaturan.index'));

        $response->assertOk();
        $response->assertSee('Preferensi Bahasa');
        $response->assertSee('Tema Antarmuka');
        $response->assertSee('Zona Waktu & Tanggal');
        $response->assertSee('name="locale"', false);
        $response->assertSee('name="theme"', false);
        $response->assertSee('name="timezone"', false);
        $response->assertSee('name="date_format"', false);
    }

    public function test_cabang_can_update_pengaturan(): void
    {
        [$asrama, $user] = $this->makeCabangUser();

        $response = $this->actingAs($user)
            ->withSession(['asrama_id' => $asrama->id])
            ->put(route('cabang.pengaturan.update'), [
                'locale' => 'en',
                'theme' => 'dark',
                'timezone' => 'Asia/Makassar',
                'date_format' => 'Y-m-d',
            ]);

        $response->assertRedirect(route('cabang.pengaturan.index'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('locale', 'en');
        $response->assertSessionHas('theme', 'dark');
        $response->assertSessionHas('timezone', 'Asia/Makassar');
        $response->assertSessionHas('date_format', 'Y-m-d');

        $user->refresh();
        $this->assertEquals('en', $user->locale);
        $this->assertEquals('dark', $user->theme);
        $this->assertEquals('Asia/Makassar', $user->timezone);
        $this->assertEquals('Y-m-d', $user->date_format);
    }

    public function test_cabang_can_switch_locale_via_quick_shortcut(): void
    {
        [$asrama, $user] = $this->makeCabangUser();

        // Switch to 'en'
        $response = $this->actingAs($user)
            ->withSession(['asrama_id' => $asrama->id])
            ->post(route('cabang.pengaturan.ganti-bahasa'), [
                'locale' => 'en',
            ]);

        $response->assertSessionHas('locale', 'en');
        $user->refresh();
        $this->assertEquals('en', $user->locale);

        // Switch back to 'id'
        $response2 = $this->actingAs($user)
            ->withSession(['asrama_id' => $asrama->id])
            ->post(route('cabang.pengaturan.ganti-bahasa'), [
                'locale' => 'id',
            ]);

        $response2->assertSessionHas('locale', 'id');
        $user->refresh();
        $this->assertEquals('id', $user->locale);
    }

    public function test_navbar_contains_dropdown_menu_elements(): void
    {
        [$asrama, $user] = $this->makeCabangUser();

        $response = $this->actingAs($user)
            ->withSession(['asrama_id' => $asrama->id])
            ->get(route('cabang.dashboard'));

        $response->assertOk();
        // Check dropdown elements
        $response->assertSee('id="user-menu"', false);
        $response->assertSee('id="user-btn"', false);
        $response->assertSee('toggleUserMenu()', false);
        $response->assertSee(route('cabang.profil.index'));
        $response->assertSee(route('cabang.pengaturan.index'));
        $response->assertSee(route('cabang.pengaturan.ganti-bahasa'));
        $response->assertSee(route('logout'));
    }
}

