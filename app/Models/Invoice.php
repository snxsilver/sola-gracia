<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = "invoice";

    protected $fillable = [
        'no_invoice',
        'faktur_pajak',
        'tanggal',
        'tanggal_jatuh_tempo',
        'nama_perusahaan',
        'alamat',
        'telp',
        'npwp',
        'dp',
        'subtotal',
        'total',
        'tanggal_posted',
        'keterangan',
        'proyek',
        'kreator',
    ];
}
