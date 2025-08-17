<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backlog extends Model
{
    use HasFactory;

    protected $casts = [
    'tanggal_temuan' => 'datetime',
];

    protected $table = 'backlog'; // karena bukan jamak "backlogs"

    protected $fillable = [
        'tanggal_temuan',
        'id_inspeksi',
        'code_number',
        'hm',
        'component',
        'plan_repair',
        'status',
        'condition',
        'gl_pic',
        'pic_daily',
        'evidence',
        'deskripsi',
        'part_number',
        'part_name',
        'no_figure',
        'qty',
        'close_by',
        'id_action',
        'made_by',
    ];

    // Relasi opsional
    public function creator()
    {
        return $this->belongsTo(User::class, 'made_by');
    }
}
