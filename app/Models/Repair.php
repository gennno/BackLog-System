<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_backlog',
        'kode_unit',
        'tanggal',
        'hm',
        'component',
        'evidence_temuan',
        'pic_daily',
        'gl_pic',
        'evidence_perbaikan',
        'pic',
        'status',
        'nama_pembuat',
        'deskripsi',
    ];

    /**
     * Relasi ke Backlog (opsional)
     */
    public function backlog()
    {
        return $this->belongsTo(Backlog::class, 'id_backlog');
    }

    /**
     * Relasi ke Unit berdasarkan kode_unit (manual)
     */
    public function unit()
    {
        return $this->hasOne(Unit::class, 'kode_unit', 'kode_unit');
    }
}
