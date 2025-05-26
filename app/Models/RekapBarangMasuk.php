<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapBarangMasuk extends Model
{
    protected $table = 'rekap_barang_masuk';

    protected $fillable = [
        'dry_good', 'veggies', 'meat', 'fruit', 'total', 'control'
    ];
}
