<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KegiatanHarian extends Model
{
    protected $table = 'kegiatan_harian';

    protected $fillable = [
        'asrama_id',
        'tanggal_kegiatan',
        'kategori',
        'nama_kegiatan',
        'jam_mulai',
        'jam_selesai',
        'deskripsi',
        'jumlah_peserta',
        'catatan_tambahan',
        'penginput_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kegiatan' => 'date',
            'jumlah_peserta' => 'integer',
        ];
    }

    public function asrama(): BelongsTo
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }

    public function penginput(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penginput_id');
    }

    public function scopeByRole(Builder $query): Builder
    {
        $user = auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->isAdminPusat()) {
            return $query;
        }

        if ($user->isPimpinanPusat()) {
            return $query;
        }

        if ($user->isAdminCabang()) {
            if (empty($user->asrama_id)) {
                return $query->whereRaw('1 = 0');
            }

            return $query->where('asrama_id', $user->asrama_id);
        }

        return $query->whereRaw('1 = 0');
    }

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori) {
            'belajar' => 'Belajar',
            'muhadhoroh' => 'Muhadhoroh',
            'olahraga' => 'Olahraga',
            'acara_donatur' => 'Acara Donatur',
            'bersih_bersih' => 'Bersih-bersih',
            default => ucfirst(str_replace('_', ' ', (string) $this->kategori)),
        };
    }

    public function getJamMulaiFormatAttribute(): string
    {
        return substr((string) $this->jam_mulai, 0, 5);
    }

    public function getJamSelesaiFormatAttribute(): string
    {
        return substr((string) $this->jam_selesai, 0, 5);
    }
}
