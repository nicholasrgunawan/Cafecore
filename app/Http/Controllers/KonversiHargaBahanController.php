<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\KonversiHargaBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KonversiHargaBahanController extends Controller
{
    public function index()
{
    $bahans = Bahan::pluck('bahan');; 
    return view('standard_recipe', [
        'bahans' => $bahans,
        'pageTitle' => 'Konversi Harga Bahan'
    ]); // this MUST match the Blade file name
}

public function getBahanDetails(Bahan $bahan)
{
    return response()->json([
        'kategori' => $bahan->kategori,
        'unit' => $bahan->unit,
        'harga' => $bahan->harga,
    ]);
}

public function store(Request $request)
{
    $items = $request->input('items');

    // Validate if needed
    foreach ($items as $item) {
        // Example: Save each item to the database
        KonversiHargaBahan::create([
            'bahan' => $item['bahan'],
            'unit1' => $item['unit1'],
            'harga' => $item['harga'],
            'qty' => $item['qty'],
            'unit2' => $item['unit2'],
            'p_waste' => $item['p_waste'],
            'qty_waste' => $item['qty_waste'],
            'p_use' => $item['p_use'],
            'qty_use' => $item['qty_use'],
            'conv' => $item['conv'],
        ]);
    }

    return response()->json(['success' => true]);
}


public function saved_data()
{
    
    $standard_recipe = KonversiHargaBahan::all();

    return view('standardrecipe.saved_data', [
        'standard_recipe' => $standard_recipe,
        'pageTitle' => 'Data Konversi Harga Bahan',
    ]);
}

public function clear()
{
    try {
        // Disable foreign key checks (for MySQL)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Delete all rows in related tables
        DB::table('menu_pricing')->delete();
        DB::table('menu_summary')->delete();

        // Delete all rows from standard_recipe using the model
        KonversiHargaBahan::query()->delete();

        // Reset the auto-increment for the standard_recipe table
        DB::statement('ALTER TABLE standard_recipe AUTO_INCREMENT = 1;');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return response()->json(['message' => 'All data cleared successfully.'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to clear data: ' . $e->getMessage()], 500);
    }
}



public function destroy($id)
{
    $deleted = KonversiHargaBahan::find($id);

    if ($deleted) {
        $deleted->delete();
        return response()->json(['message' => 'Row deleted successfully']);
    } else {
        return response()->json(['message' => 'Data not found'], 404);
    }
}

public function import(Request $request)
{
    $rows = $request->input('rows');

    if (!$rows || !is_array($rows)) {
        return response()->json(['message' => 'Invalid data'], 400);
    }

    foreach ($rows as $row) {
        // Assuming your row is an array with the fields matching your database columns
        DB::table('standard_recipe')->insert([
            'id' => $row[0],
            'bahan' => $row[1],
            'unit1' => $row[2],
            'harga' => $row[3],
            'qty' => $row[4],
            'unit2' => $row[5],
            'p_waste' => $row[6],
            'qty_waste' => $row[7],
            'p_use' => $row[8],
            'qty_use' => $row[9],
            'conv' => $row[10],
            'created_at' => $row[11],
            'updated_at' => now(),
        ]);
    }

    return response()->json(['message' => 'Imported successfully']);
}
}
