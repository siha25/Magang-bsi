<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnakAsuh extends Model
{
    /**
     * Nama tabel database yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'anak_asuh';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var list<string>
     */
    protected $fillable = [
        'asrama_id',
        'nomor_induk',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'jenjang_pendidikan',
        'kelas',
        'tanggal_masuk',
        'status_aktif',
        'catatan',
    ];

    /**
     * Tipe data casting atribut model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_masuk' => 'date',
            'status_aktif' => 'boolean',
        ];
    }

    /**
     * Relasi ke data asrama tempat anak asuh bernaung.
     *
     * @return BelongsTo<Asrama, $this>
     */
    public function asrama(): BelongsTo
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }

    /**
     * Scope pencarian anak asuh berdasarkan nomor induk atau nama lengkap.
     *
     * @param  Builder<AnakAsuh>  $query
     * @return Builder<AnakAsuh>
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        $term = trim($keyword);

        return $query->where(function (Builder $q) use ($term): void {
            $q->where('nomor_induk', 'like', "%{$term}%")
                ->orWhere('nama_lengkap', 'like', "%{$term}%");
        });
    }

    /**
     * Scope untuk menyaring anak asuh yang berstatus aktif.
     *
     * @param  Builder<AnakAsuh>  $query
     * @return Builder<AnakAsuh>
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status_aktif', true);
    }

    /**
     * Label representasi jenis kelamin.
     */
    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
