<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'asrama_id', 'status_aktif'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN_PUSAT = 'admin_pusat';

    public const ROLE_ADMIN_ASRAMA = 'admin_asrama';

    public const ROLE_PIMPINAN = 'pimpinan';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status_aktif' => 'boolean',
        ];
    }

    /**
     * Relasi ke data asrama tempat pengguna ditugaskan (khusus Admin Asrama).
     *
     * @return BelongsTo<Asrama, $this>
     */
    public function asrama(): BelongsTo
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }

    /**
     * Mengetahui apakah pengguna adalah Admin Pusat (akses penuh seluruh asrama).
     */
    public function isAdminPusat(): bool
    {
        return $this->role === self::ROLE_ADMIN_PUSAT;
    }

    /**
     * Mengetahui apakah pengguna adalah Admin Asrama (akses terbatas asrama tertentu).
     */
    public function isAdminAsrama(): bool
    {
        return $this->role === self::ROLE_ADMIN_ASRAMA;
    }

    /**
     * Mengetahui apakah pengguna adalah Koordinator / Pimpinan (akses baca eksekutif).
     */
    public function isPimpinan(): bool
    {
        return $this->role === self::ROLE_PIMPINAN;
    }

    /**
     * Mengecek apakah pengguna berhak mengelola data asrama tertentu.
     */
    public function canManageAsrama(int|Asrama $asrama): bool
    {
        if ($this->isAdminPusat()) {
            return true;
        }

        if ($this->isAdminAsrama()) {
            $asramaId = $asrama instanceof Asrama ? $asrama->id : $asrama;

            return ! empty($this->asrama_id) && (int) $this->asrama_id === (int) $asramaId;
        }

        return false;
    }
}
