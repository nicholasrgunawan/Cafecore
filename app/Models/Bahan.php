<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'bahan',
        'merk',
        'harga',
        'kategori',
        'qty',
        'unit',
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
        // If there are any fields that require casting, you can specify them here
        // 'field_name' => 'type',
    ];

    // Bahan.php (Bahan model)

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_bahan', 'bahan_id', 'menu_id');
    }
    public function getRouteKeyName()
{
    return 'bahan'; // the column to bind the model by
}

}
