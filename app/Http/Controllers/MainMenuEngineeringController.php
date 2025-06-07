<?php

namespace App\Http\Controllers;

use App\Models\MainMenuEngineering;
use App\Models\RekapMainMenuEngineering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MainMenuEngineeringController extends Controller
{
   public function index()
{
    // Get menus with kategori
    $menus = DB::table('sales_potentials')
    ->select('menu as name', 'kategori', 'per_cost as hpp', 'price')
    ->distinct()
    ->orderBy('name')
    ->get(); // ← returns a collection of objects


    // Get total sold qty per menu
    $totalSold = DB::table('sales_potentials')
        ->select('menu', DB::raw('SUM(qty) as total_qty'))
        ->groupBy('menu')
        ->pluck('total_qty', 'menu');

    // Get food cost (per_cost) per menu
    $foodCosts = DB::table('sales_potentials')
        ->select('menu', 'per_cost')
        ->orderBy('menu')
        ->get()
        ->keyBy('menu')
        ->map(fn($item) => $item->per_cost);

    // Get sell price per menu
    $sellPrices = DB::table('sales_potentials')
        ->select('menu', 'price')
        ->orderBy('menu')
        ->get()
        ->keyBy('menu')
        ->map(fn($item) => $item->price);

    // Pass all data to the view
    return view('menu_engineering4', [
    'menus' => $menus,
    'totalSold' => $totalSold,
    'foodCosts' => $foodCosts,
    'sellPrices' => $sellPrices,
    'pageTitle' => 'Menu Engineering'
]);

}

public function viewSavedData()
{
   $details = MainMenuEngineering::orderBy('created_at', 'desc')->get();
    $summary  = RekapMainMenuEngineering::latest()->first();
    return view('menuengineering.saved_data', [
        'details' => $details,
        'summary' => $summary,
        'pageTitle' => 'Data Menu Engineering'
    ]);

}

public function save(Request $request)
{
    $data = $request->input('data'); // main table data
    $summary = $request->input('summary'); // summary data

    if (!$data || !is_array($data)) {
        return response()->json(['error' => 'Invalid main data'], 400);
    }

    if (!$summary || !is_array($summary)) {
        return response()->json(['error' => 'Invalid summary data'], 400);
    }

    // Save main detailed rows
    foreach ($data as $row) {
        MainMenuEngineering::create([
            'menu' => $row['menu'] ?? null,
            'kategori' => $row['kategori'] ?? null,
            'total_sold' => $row['total_sold'] ?? 0,
            'menu_mix' => $row['menu_mix'] ?? 0,
            'food_cost' => $row['food_cost'] ?? 0,
            'sell_price' => $row['sell_price'] ?? 0,
            'food_cost_p' => $row['food_cost_p'] ?? 0,
            'cont' => $row['cont'] ?? 0,
            'menu_cost' => $row['menu_cost'] ?? 0,
            'total_sales' => $row['total_sales'] ?? 0,
            'm_cont' => $row['m_cont'] ?? 0,
            'lhcm' => $row['lhcm'] ?? 0,
            'lhmm' => $row['lhmm'] ?? 0,
            'mi_class' => $row['mi_class'] ?? '',
        ]);
    }

    // Save summary data to rekap_menu_engineering
    RekapMainMenuEngineering::create([
        'total_quantity' => $summary['total_quantity'] ?? 0,
        'menu_mix' => $summary['menu_mix'] ?? 0,
        'item_food_cost' => $summary['item_food_cost'] ?? 0,
        'item_sell_price' => $summary['item_sell_price'] ?? 0,
        'item_food_cost_p' => $summary['item_food_cost_p'] ?? 0,
        'item_contribution' => $summary['item_contribution'] ?? 0,
        'menu_cost' => $summary['menu_cost'] ?? 0,
        'total_sales' => $summary['total_sales'] ?? 0,
        'menu_contribution' => $summary['menu_contribution'] ?? 0,
        'potential_food_cost' => $summary['potential_food_cost'] ?? 0,
        'average_profit' => $summary['average_profit'] ?? 0,
        'average_contribution' => $summary['average_contribution'] ?? 0,
    ]);

    return response()->json(['success' => true]);
}

public function destroy($id)
{
    MainMenuEngineering::findOrFail($id)->delete();
    return response()->json(['ok' => true]);
}

public function destroySummary($id)
{
    RekapMainMenuEngineering::findOrFail($id)->delete();

    return response()->json(['ok' => true]);
}
}




