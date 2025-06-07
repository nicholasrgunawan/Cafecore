<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPricing extends Model
{
    protected $table = 'menu_pricing';

    protected $fillable = [
        'menu_id',
        'standard_recipe_id',
        'used_qty',
        'used_cost',
    ];

    // Relationships
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function standardRecipe()
    {
        return $this->belongsTo(KonversiHargaBahan::class);
    }
}
