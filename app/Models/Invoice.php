<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = "invoice";

    protected $fillable = [
        'tanggal',
        'no_invoice',
        'total',
        'keterangan',
        'perusahaan',
        'kreator',
    ];
}
