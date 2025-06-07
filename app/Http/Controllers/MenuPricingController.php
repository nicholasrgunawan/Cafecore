<?php

namespace App\Http\Controllers;

use App\Models\KonversiHargaBahan;
use App\Models\Menu;
use App\Models\MenuPricing;
use App\Models\MenuSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuPricingController extends Controller
{
    public function index()
    {
         $menu_pricing = MenuPricing::with(['menu', 'standardRecipe'])->get();
         $menu_summary = MenuSummary::with('menu')->get(); // make sure you have a 'menu()' relation

    return view('menu_pricing', [
        'menu_pricing' => $menu_pricing,
        'menu_summary' => $menu_summary,
        'pageTitle' => 'Menu Pricing & Summary',
    ]);
    }

    public function create()
{
    $menus = Menu::select('id', 'menu')->get();
    $standard_recipe = KonversiHargaBahan::all(); // ← Get full records

    return view('add.add_menu_pricing', [
        'standard_recipe' => $standard_recipe,
        'menus' => $menus,
        'pageTitle' => 'Create Menu Pricing',
    ]);
}

public function create2()
{
    $menu_summary = MenuPricing::with('menu')
        ->get()
        ->groupBy('menu_id')
        ->map(function ($items, $menuId) {
            $menu = $items->first()->menu;

            $total_cost = $items->sum('used_cost');
            $markup_percent = 30; // You can adjust this
            $final_price = $total_cost + ($total_cost * ($markup_percent / 100));

            return [
                'menu_id' => $menuId,
                'menu_name' => $menu ? $menu->menu : 'N/A',
                'total_cost' => $total_cost,
                'markup_percent' => $markup_percent,
                'final_price' => $final_price,
            ];
        })
        ->values(); // reset keys

    return view('add.add_menu_summary', [
        'menu_pricing' => $menu_summary,
        'pageTitle' => 'Create Menu Summary',
    ]);
}




    public function getConv($id)
{
    $recipe = \App\Models\KonversiHargaBahan::find($id);

    if (!$recipe) {
        return response()->json(['conv' => 'Not found'], 404);
    }

    return response()->json(['conv' => $recipe->conv]);
}


     public function store(Request $request)
{
    $request->validate([
        'menu' => 'required|string',
        'bahan' => 'required|string',
        'unit' => 'required|numeric|min:0',
        'conv' => 'required|numeric|min:0',
    ]);

    // Find menu_id from menu name
    $menu = Menu::find($request->menu);
if (!$menu) {
    return response()->json(['message' => 'Menu not found'], 404);
}

    // Find standard_recipe_id from bahan name
  $standardRecipe = KonversiHargaBahan::find($request->bahan);

if (!$standardRecipe) {
    return response()->json(['message' => 'Bahan not found'], 404);
}

    try {
        MenuPricing::create([
    'menu_id' => $request->menu,
    'standard_recipe_id' => $request->bahan,
    'used_qty' => $request->unit,
    'used_cost' => $request->used_cost, // total cost from form
]);


        return response()->json(['message' => 'Data saved successfully'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to save data'], 500);
    }
}

 public function store2(Request $request)
{
    $menu_id = $request->menu;

    $total_bahan_cost = MenuPricing::where('menu_id', $menu_id)->sum('used_cost');
    $final_price = ($total_bahan_cost * 0.05) + $total_bahan_cost;

    MenuSummary::create([
        'menu_id' => $menu_id,
        'total_bahan_cost' => $total_bahan_cost,
        'final_price' => $final_price,
    ]);

    return response()->json(['message' => 'Menu summary saved successfully.']);
}

public function destroy($id)
{
    $deleted = MenuPricing::find($id);

    if (!$deleted) {
        return response()->json(['message' => 'Data not found'], 404);
    }

    // Check if this is the highest id in the table
    $maxId = MenuPricing::max('id');

    // Delete the record
    $deleted->delete();

    if ($id == $maxId) {
        // Reset auto increment to this id (so next insert will reuse this id)
        $newAutoIncrement = $id;

        // Find the next highest id to reset to, if there are still rows
        $maxRemainingId = MenuPricing::max('id');

        if ($maxRemainingId) {
            // Reset to maxRemainingId + 1
            $newAutoIncrement = $maxRemainingId + 1;
        } else {
            // Table is empty, reset to 1
            $newAutoIncrement = 1;
        }

        // Run raw query to reset auto-increment
        DB::statement("ALTER TABLE menu_pricing AUTO_INCREMENT = $newAutoIncrement");
    }

    return response()->json(['message' => 'Row deleted successfully']);
}


public function destroy2($id)
{
    $deleted = MenuSummary::find($id);

    if (!$deleted) {
        return response()->json(['message' => 'Data not found'], 404);
    }

    $maxId = MenuSummary::max('id');

    $deleted->delete();

    if ($id == $maxId) {
        $maxRemainingId = MenuSummary::max('id');

        $newAutoIncrement = $maxRemainingId ? $maxRemainingId + 1 : 1;

        DB::statement("ALTER TABLE menu_summary AUTO_INCREMENT = $newAutoIncrement");
    }

    return response()->json(['message' => 'Row deleted successfully']);
}

public function edit($id)
{
    $menu_pricing = MenuPricing::findOrFail($id);
    $menus = Menu::select('id', 'menu')->get();
    $standard_recipe = KonversiHargaBahan::all();

    return view('edit.edit_menu_pricing', [
        'menu_pricing' => $menu_pricing,
        'menus' => $menus,
        'standard_recipe' => $standard_recipe,
        'pageTitle' => 'Edit Menu Pricing',
    ]);
}

public function update(Request $request, $id)
{
    $request->validate([
        'menu' => 'required|numeric|exists:menus,id',
        'bahan' => 'required|numeric|exists:standard_recipe,id',
        'unit' => 'required|numeric|min:0',
        'conv' => 'required|numeric|min:0',
        'used_cost' => 'required|numeric|min:0',
    ]);

    $menuPricing = MenuPricing::findOrFail($id);

    $menuPricing->update([
        'menu_id' => $request->menu,
        'standard_recipe_id' => $request->bahan,
        'used_qty' => $request->unit,
        'used_cost' => $request->used_cost,
    ]);

    return redirect()->route('menu_pricing')->with('success', 'Updated successfully!');

}




}
