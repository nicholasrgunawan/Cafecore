<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainMenuEngineering extends Model
{
    protected $table = 'menu_engineerings';

    protected $fillable = [
        'menu',
        'kategori',
        'total_sold',
        'menu_mix',
        'food_cost',
        'sell_price',
        'food_cost_p',
        'cont',
        'menu_cost',
        'total_sales',
        'm_cont',
        'lhcm',
        'lhmm',
        'mi_class',
    ];


}
