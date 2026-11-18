<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

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

    /**
     * Periksa apakah tabel kategori_kegiatan sudah tersedia di database.
     */
    public static function hasTable(): bool
    {
        try {
            return Schema::hasTable('kategori_kegiatan');
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Daftar kategori kegiatan bawaan (fallback jika tabel belum dimigrasi).
     *
     * @return array<int, array{id: int, nama: string, kode: string, deskripsi: string, is_active: bool}>
     */
    public static function defaultItems(): array
    {
        return [
            [
                'id' => 1,
                'nama' => 'Acara Donatur',
                'kode' => 'acara_donatur',
                'deskripsi' => 'Silaturahmi, santunan, buka bersama, dan ramah tamah bersama mitra yayasan.',
                'is_active' => true,
            ],
            [
                'id' => 2,
                'nama' => 'Belajar',
                'kode' => 'belajar',
                'deskripsi' => 'Kegiatan akademik, bimbingan belajar, tahsin, dan pengkajian ilmu santri.',
                'is_active' => true,
            ],
            [
                'id' => 3,
                'nama' => 'Bersih-bersih',
                'kode' => 'bersih_bersih',
                'deskripsi' => 'Gotong royong, kerja bakti asrama, piket kamar, dan kebersihan lingkungan.',
                'is_active' => true,
            ],
            [
                'id' => 4,
                'nama' => 'Muhadhoroh',
                'kode' => 'muhadhoroh',
                'deskripsi' => 'Latihan pidato, public speaking, kultum, dan pengembangan mental santri.',
                'is_active' => true,
            ],
            [
                'id' => 5,
                'nama' => 'Olahraga',
                'kode' => 'olahraga',
                'deskripsi' => 'Aktivitas fisik jasmani, senam, lari pagi, dan pertandingan persahabatan santri.',
                'is_active' => true,
            ],
        ];
    }

    /**
     * Koleksi model kategori kegiatan bawaan di memori.
     *
     * @return Collection<int, static>
     */
    public static function defaultCollection(): Collection
    {
        return collect(self::defaultItems())->map(function (array $item) {
            return (new static)->forceFill($item);
        });
    }

    /**
     * Ambil seluruh kategori kegiatan dengan fallback aman jika tabel belum ada.
     *
     * @return Collection<int, static>
     */
    public static function safeAll(): Collection
    {
        if (! self::hasTable()) {
            return self::defaultCollection();
        }

        try {
            return static::orderBy('nama')->get();
        } catch (Throwable) {
            return self::defaultCollection();
        }
    }

    /**
     * Ambil seluruh kategori kegiatan aktif dengan fallback aman jika tabel belum ada.
     *
     * @return Collection<int, static>
     */
    public static function safeActive(): Collection
    {
        if (! self::hasTable()) {
            return self::defaultCollection();
        }

        try {
            return static::query()->active()->orderBy('nama')->get();
        } catch (Throwable) {
            return self::defaultCollection();
        }
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
