<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    // The name of the table associated with the model
    protected $table = 'barang_masuk';

    // The attributes that are mass assignable
    // app/Models/BarangMasuk.php

protected $fillable = [
    'bahan', 'kategori', 'qty', 'unit', 'harga', 'jumlah',
    
];

}
