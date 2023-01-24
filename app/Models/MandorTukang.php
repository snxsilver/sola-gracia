<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MandorTukang extends Model
{
    use HasFactory;

    protected $table = "mandor_tukang";

    protected $fillable = [
        'mandor_proyek',
        'nama',
        'tukang',
        'kreator',
    ];
}
