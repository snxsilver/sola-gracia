<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harian extends Model
{
    use HasFactory;

    protected $table = "harian";

    protected $fillable = [
        'nama',
        'tukang',
        'pokok',
        'insentif',
        'lembur',
        'kreator',
        'approved',
        'approver',
        'total',
        'tahun',
    ];
}
