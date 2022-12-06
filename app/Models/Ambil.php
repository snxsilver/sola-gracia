<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambil extends Model
{
    use HasFactory;

    protected $table = "ambil_stok";

    protected $fillable = [
        'tanggal',
        'stok',
        'kuantitas',
        'harga',
        'bukukas',
        'kreator',
    ];
}
