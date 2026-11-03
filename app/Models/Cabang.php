<?php

// BAGIAN: SHARED - Model Cabang (Entitas Cabang Asrama)

namespace App\Models;

class Cabang extends Asrama
{
    protected $table = 'asrama';

    /**
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
        'kode_cabang',
        'nama_cabang',
    ];

    public function getKodeCabangAttribute(): ?string
    {
        return $this->kode_asrama;
    }

    public function setKodeCabangAttribute(?string $value): void
    {
        $this->attributes['kode_asrama'] = $value;
    }

    public function getNamaCabangAttribute(): ?string
    {
        return $this->nama_asrama;
    }

    public function setNamaCabangAttribute(?string $value): void
    {
        $this->attributes['nama_asrama'] = $value;
    }
}
