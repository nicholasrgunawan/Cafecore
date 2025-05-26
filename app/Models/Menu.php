<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'menu',           // Adjusted for menu name
        'kategori',       // Adjusted for menu category
        'bahan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    // If there's anything you'd like to hide from serialization, you can add it here
    // protected $hidden = [
    //     'column_name_to_hide',
    // ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'price' => 'float',        // Ensure price is cast to float
        'availability' => 'boolean', // If availability is a boolean field
    ];

     // Menu.php (Menu model)
     public function bahans()
     {
         return $this->belongsToMany(Bahan::class, 'menu_bahan', 'menu_id', 'bahan_id');
     }


}
