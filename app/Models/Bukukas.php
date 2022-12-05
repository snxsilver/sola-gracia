<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bukukas extends Model
{
    use HasFactory;

    protected $table = "bukukas";

    protected $fillable = [
        'tanggal',
        'keterangan',
        'keluar',
        'masuk',
        'no_bukti',
        'nota',
        'kategori',
        'proyek',
        'kreator',
        'ambil_stok',
    ];
}
