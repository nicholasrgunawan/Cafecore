<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapBarangKeluar extends Model
{
    protected $table = 'rekap_barang_keluar';

    protected $fillable = [
        'dry_good', 'veggies', 'meat', 'fruit', 'total', 'control'
    ];
}
