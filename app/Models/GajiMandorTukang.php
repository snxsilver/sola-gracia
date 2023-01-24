<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiMandorTukang extends Model
{
    use HasFactory;

    protected $table = "gaji_mandor_tukang";

    protected $fillable = [
        'tanggal',
        // 'nama',
        'mandor_tukang',
        'proyek',
        'gaji_mandor',
        'uang_pokok',
        'jam_lembur',
        'uang_lembur',
        'makan',
        'uang_makan',
        'transport',
        'uang_transpor',
        'total',
        'bukukas',
        'kreator',
    ];
}
