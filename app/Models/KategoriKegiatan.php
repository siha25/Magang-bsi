<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class KategoriKegiatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_kegiatan';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function kegiatanHarian(): HasMany
    {
        return $this->hasMany(KegiatanHarian::class, 'kategori_kegiatan_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Kelas badge Tailwind seragam bertema hijau emerald.
     * Dipakai di semua tampilan (monitoring, laporan, tabel, daftar kategori).
     */
    public function getBadgeClassAttribute(): string
    {
        return 'bg-emerald-50 text-emerald-700 border-emerald-200';
    }

    /**
     * Generate kode slug unik untuk kategori.
     */
    public static function generateUniqueKode(string $nama, ?int $ignoreId = null): string
    {
        $base = Str::slug($nama, '_');
        if ($base === '') {
            $base = 'kategori';
        }

        $kode = $base;
        $counter = 1;

        while (static::withTrashed()->where('kode', $kode)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $kode = $base.'_'.$counter;
            $counter++;
        }

        return $kode;
    }
}
