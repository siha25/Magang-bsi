<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumentasiKegiatan extends Model
{
    protected $table = 'dokumentasi_kegiatan';

    protected $fillable = [
        'kegiatan_id',
        'path_file',
        'nama_asli',
        'ukuran_byte',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'ukuran_byte' => 'integer',
            'urutan' => 'integer',
        ];
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(KegiatanHarian::class, 'kegiatan_id');
    }

    public function getUrlAttribute(): string
    {
        return route('kegiatan-harian.dokumentasi.show', ['id' => $this->id]);
    }

    public function getUkuranFormattedAttribute(): string
    {
        if ($this->ukuran_byte >= 1048576) {
            return number_format($this->ukuran_byte / 1048576, 2).' MB';
        }

        return number_format($this->ukuran_byte / 1024, 1).' KB';
    }
}

