<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoronganBayar extends Model
{
    use HasFactory;

    protected $table = "borongan_bayar";

    protected $fillable = [
        'borongan',
        'bukukas',
        'tanggal',
        'nominal',
        'kreator',
    ];
}
