<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapMainMenuEngineering extends Model
{
    protected $table = 'rekap_menu_engineering';

    protected $fillable = [
        'total_quantity',
        'menu_mix',
        'item_food_cost',
        'item_sell_price',
        'item_food_cost_p',
        'item_contribution',
        'menu_cost',
        'total_sales',
        'menu_contribution',
        'potential_food_cost',
        'average_profit',
        'average_contribution',
    ];

}
