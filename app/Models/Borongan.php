<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borongan extends Model
{
    use HasFactory;

    protected $table = "borongan";

    protected $fillable = [
        'proyek',
        'nama',
        'kreator',
    ];
}
