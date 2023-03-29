<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MandorProyek extends Model
{
    use HasFactory;

    protected $table = "mandor_proyek";

    protected $fillable = [
        'tanggal',
        'nama',
        'mandor',
        'kreator',
        'approved',
        'approver',
        'tahun',
    ];
}
