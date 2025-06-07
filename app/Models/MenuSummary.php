<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSummary extends Model
{
    use HasFactory;

    protected $table = 'menu_summary'; // Match your table name

    protected $fillable = [
        'menu_id',
        'total_bahan_cost',
        'final_price',
    ];

    /**
     * Relationship: Each summary belongs to one menu
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
