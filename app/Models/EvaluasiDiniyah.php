<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluasiDiniyah extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_PERLU_REVISI = 'perlu_revisi';

    protected $table = 'evaluasi_diniyah';

    protected $fillable = [
        'asrama_id',
        'anak_asuh_id',
        'jenis_evaluasi',
        'materi',
        'tanggal_evaluasi',
        'catatan_pengaju',
        'status',
        'predikat',
        'catatan_revisi',
        'diajukan_oleh',
        'diajukan_pada',
        'dinilai_oleh',
        'dinilai_pada',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_evaluasi' => 'date',
            'diajukan_pada' => 'datetime',
            'dinilai_pada' => 'datetime',
        ];
    }

    public function asrama(): BelongsTo
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }

    public function anakAsuh(): BelongsTo
    {
        return $this->belongsTo(AnakAsuh::class, 'anak_asuh_id');
    }

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function penilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dinilai_oleh');
    }

    /**
     * @param  Builder<EvaluasiDiniyah>  $query
     * @return Builder<EvaluasiDiniyah>
     */
    public function scopeDisetujui(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DISETUJUI);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isDisetujui(): bool
    {
        return $this->status === self::STATUS_DISETUJUI;
    }

    public function isPerluRevisi(): bool
    {
        return $this->status === self::STATUS_PERLU_REVISI;
    }
}
