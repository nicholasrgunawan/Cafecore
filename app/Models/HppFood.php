<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HppFood extends Model
{
    use HasFactory;

    // The name of the table associated with the model
    protected $table = 'hpp_foods';

    // The attributes that are mass assignable
    // app/Models/BarangMasuk.php

protected $fillable = [
    'id', 'kategori', 'menu', 'hpp', 'hjp', 'hjp_nett', 'percent_cost',	
];
}
