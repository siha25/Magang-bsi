<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asrama extends Model
{
    /**
     * Nama tabel database yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'asrama';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_asrama',
        'nama_asrama',
        'wilayah',
        'alamat',
        'penanggung_jawab',
        'nomor_telepon',
        'status_aktif',
    ];

    /**
     * Tipe data casting atribut model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    /**
     * Scope pencarian asrama berdasarkan kode, nama, wilayah, atau kepala asrama.
     *
     * @param  Builder<Asrama>  $query
     * @return Builder<Asrama>
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        $term = trim($keyword);

        return $query->where(function (Builder $q) use ($term): void {
            $q->where('kode_asrama', 'like', "%{$term}%")
                ->orWhere('nama_asrama', 'like', "%{$term}%")
                ->orWhere('wilayah', 'like', "%{$term}%")
                ->orWhere('penanggung_jawab', 'like', "%{$term}%");
        });
    }

    /**
     * Scope untuk menyaring asrama yang berstatus aktif.
     *
     * @param  Builder<Asrama>  $query
     * @return Builder<Asrama>
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status_aktif', true);
    }

    /**
     * Relasi ke data anak asuh yang berada di asrama ini.
     *
     * @return HasMany<AnakAsuh, $this>
     */
    public function anakAsuh(): HasMany
    {
        return $this->hasMany(AnakAsuh::class, 'asrama_id');
    }
}
