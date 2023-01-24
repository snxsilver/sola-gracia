<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiMandor extends Model
{
    use HasFactory;

    protected $table = "gaji_mandor";

    protected $fillable = [
        'tanggal',
        'level',
        'pokok',
        'pokok_baru',
        'lembur',
        'lembur_baru',
        'mandor',
        'kreator',
        'approved',
        'approver',
    ];
}
