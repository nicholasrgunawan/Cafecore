<?php

namespace App\Http\Controllers;

use App\Models\HppFood;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HppFoodController extends Controller
{
    function index()
{
    $menus = Menu::with(['kategoriMenu', 'summary'])
    ->get()
    ->map(function ($menu) {
        return [
            'id' => $menu->id,
            'name' => $menu->menu,
            'kategori' => $menu->kategoriMenu->kategori ?? 'N/A',
            'hpp' => $menu->summary->final_price ?? 0,
        ];
    });



    return view('hpp_food', [
        'menus' => $menus,
        'pageTitle' => 'HPP Food'
    ]);
}

public function save(Request $request)
    {
        $items = $request->input('items');

        foreach ($items as $item) {
            HppFood::create([
                'kategori' => $item['kategori'],
                'menu' => $item['menu'],
                'hpp' => $item['hpp'],
                'hjp' => $item['hjp'],
                'hjp_nett' => $item['hjp_nett'],
                'percent_cost' => $item['percent_cost'],
            ]);
        }

        return response()->json(['message' => 'Data saved successfully']);
    }

public function saved_data()
{
    
    $hpp_foods = HppFood::all();

    return view('hppfood.saved_data', [
        'hpp_foods' => $hpp_foods,
        'pageTitle' => 'Data HPP Food',
    ]);
}

public function clearAll(Request $request)
{
    // Optional: Check for authorization or permission here

    // Disable foreign key checks if needed
    DB::statement('SET FOREIGN_KEY_CHECKS=0');

    // Truncate the table (delete all and reset auto-increment)
    DB::table('hpp_foods')->truncate();

    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    return response()->json(['message' => 'All data has been cleared and ID reset.']);
}

public function destroy($id)
{
    $hpp = HppFood::find($id);

    if (!$hpp) {
        return response()->json(['message' => 'Data not found'], 404);
    }

    $hpp->delete();

    return response()->json(['message' => 'Data deleted successfully']);
}

}
