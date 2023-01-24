<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HarianBayar extends Model
{
    use HasFactory;

    protected $table = "harian_bayar";

    protected $fillable = [
        'harian',
        'tanggal',
        'bukukas',
        'proyek',
        'keterangan',
        'jam',
        'makan',
        'uang_makan',
        'transport',
        'uang_transport',
        'total',
        'kreator',
    ];
}
