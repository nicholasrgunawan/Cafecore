<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    protected $table = 'sales_report';

    // app/Models/SalesReport.php
protected $fillable = ['menu_id','kategori', 'qty', 'harga', 'total'];


    // Relationships
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function hppFood()
    {
        return $this->belongsTo(HppFood::class);
    }

    
}
