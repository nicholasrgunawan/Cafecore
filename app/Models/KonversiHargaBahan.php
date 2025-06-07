<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonversiHargaBahan extends Model
{
    use HasFactory;

    // The name of the table associated with the model
    protected $table = 'standard_recipe';
    

    // The attributes that are mass assignable
    // app/Models/BarangMasuk.php

protected $fillable = [
    'id', 'bahan', 'unit1',	'harga', 'qty', 'unit2', 'p_waste', 'qty_waste', 'p_use', 'qty_use', 'conv',	
    
];
}
