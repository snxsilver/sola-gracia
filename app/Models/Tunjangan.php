<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tunjangan extends Model
{
    use HasFactory;

    protected $table = "setting_tunjangan";

    protected $fillable = [
        'jenis',
        'level',
        'nominal',
        'nominal_baru',
        'approved',
        'approver',
        'tanggal',
        'kreator',
    ];
}
