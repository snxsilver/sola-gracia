<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tukang extends Model
{
    use HasFactory;

    protected $table = "tukang";

    protected $fillable = [
        'nama',
        'alamat',
        'hp',
        'mandor',
        'kreator',
    ];
}
