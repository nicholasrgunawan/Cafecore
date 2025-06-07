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
        'desc',
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
    

     // Menu.php (Menu model)
     public function bahans()
     {
         return $this->belongsToMany(Bahan::class, 'menu_bahan', 'menu_id', 'bahan_id');
     }

// Menu.php
public function kategoriMenu()
{
    return $this->belongsTo(KategoriMenu::class, 'kategori_menu_id');
}

public function summary()
{
    return $this->hasOne(MenuSummary::class, 'menu_id');
}


}
