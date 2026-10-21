<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Auth\Access\AuthorizationException;
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

    public const ROLE_ADMIN_CABANG = 'admin_cabang';

    public const ROLE_PIMPINAN_PUSAT = 'pimpinan_pusat';

    /**
     * Backward compatibility alias untuk role lama.
     */
    public const ROLE_ADMIN_ASRAMA = self::ROLE_ADMIN_CABANG;

    /**
     * Backward compatibility alias untuk role lama.
     */
    public const ROLE_PIMPINAN = self::ROLE_PIMPINAN_PUSAT;

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
            'is_primary_admin' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (User $user): void {
            if ($user->isDirty('is_primary_admin')) {
                throw new AuthorizationException('The primary administrator marker cannot be changed through the application.');
            }

            if (! (bool) $user->getRawOriginal('is_primary_admin')) {
                return;
            }

            if ($user->isDirty('role') || ($user->isDirty('status_aktif') && ! $user->status_aktif)) {
                throw new AuthorizationException('The primary administrator role and active status are protected.');
            }
        });

        static::deleting(function (User $user): void {
            if ((bool) $user->getRawOriginal('is_primary_admin')) {
                throw new AuthorizationException('The primary administrator account cannot be deleted.');
            }
        });
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
     * Mengetahui apakah pengguna adalah Admin Cabang (akses terbatas asrama tertentu).
     */
    public function isAdminCabang(): bool
    {
        return $this->role === self::ROLE_ADMIN_CABANG;
    }

    /**
     * Mengetahui apakah pengguna adalah Koordinator / Pimpinan Pusat (akses baca eksekutif).
     */
    public function isPimpinanPusat(): bool
    {
        return $this->role === self::ROLE_PIMPINAN_PUSAT;
    }

    public function isPrimaryAdmin(): bool
    {
        return (bool) $this->is_primary_admin;
    }

    /**
     * Alias backward compatibility.
     */
    public function isAdminAsrama(): bool
    {
        return $this->isAdminCabang();
    }

    /**
     * Alias backward compatibility.
     */
    public function isPimpinan(): bool
    {
        return $this->isPimpinanPusat();
    }

    /**
     * Mengecek apakah pengguna berhak mengelola data asrama tertentu.
     */
    public function canManageAsrama(int|Asrama $asrama): bool
    {
        if ($this->isAdminPusat() || $this->isAdminCabang()) {
            if ($this->isAdminPusat()) {
                return true;
            }

            $asramaId = $asrama instanceof Asrama ? $asrama->id : $asrama;

            return ! empty($this->asrama_id) && (int) $this->asrama_id === (int) $asramaId;
        }

        return false;
    }
}
