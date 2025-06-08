<?php

namespace App\Http\Controllers;

use App\Models\SalesPotentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesPotentialsController extends Controller
{
    public function index()
{
    $sales_potential = SalesPotentials::all();
    $totalSales = $sales_potential->sum('total'); // Calculate total here

    return view('menu_engineering3', [
        'sales_potentials' => $sales_potential,
        'totalSales' => $totalSales,
        'pageTitle' => 'Sales & Potentials',
    ]);
}

 public function create()
{
    $sales_reports = DB::table('sales_report')
                ->select('menu', 'kategori')
                ->distinct()
                ->get();

    return view('add.add_sales_potentials', [
        'sales_reports' => $sales_reports,
        'pageTitle' => 'Add Sales & Potentials',
    ]);
}

public function getSalesData(Request $request)
{
    $menu = $request->query('menu');
    $start_date = $request->query('start_date');
    $end_date = $request->query('end_date');

    // Append time to end_date to include the whole day
    $startDateTime = $start_date . ' 00:00:00';
    $endDateTime = $end_date . ' 23:59:59';

    $totalQty = DB::table('sales_report')
        ->where('menu', $menu)
        ->whereBetween('created_at', [$startDateTime, $endDateTime])
        ->sum('qty');

    return response()->json(['qty' => $totalQty]);
}

public function getAmountCost(Request $request)
{
    $menu = $request->query('menu');
    $qty = $request->query('qty');

    // Find hpp for the menu from hpp_foods table
    $hppRecord = DB::table('hpp_foods')->where('menu', $menu)->first();
    $hpp = $hppRecord ? $hppRecord->hpp : 0;

    // Calculate amount cost
    $amountCost = $hpp * $qty;

    return response()->json(['amountCost' => $amountCost]);
}

public function getAmountSales(Request $request)
{
    $menu = $request->query('menu');
    $qty = (float) $request->query('qty', 0);

    $hppRecord = DB::table('hpp_foods')->where('menu', $menu)->first();
    $hjp_nett = $hppRecord ? $hppRecord->hjp_nett : 0;

    $amountSales = $hjp_nett * $qty;

    return response()->json(['amountSales' => $amountSales]);
}

public function getPrice(Request $request)
{
    $menu = $request->query('menu');

    $hppRecord = DB::table('hpp_foods')->where('menu', $menu)->first();
    $hjp_nett = $hppRecord ? $hppRecord->hjp_nett : 0;

    $price = $hjp_nett;

    return response()->json(['price' => $price]);
}

public function getCost(Request $request)
{
    $menu = $request->query('menu');

    $hppRecord = DB::table('hpp_foods')->where('menu', $menu)->first();
    $hpp = $hppRecord ? $hppRecord->hpp : 0;

    $cost = $hpp;

    return response()->json(['per_cost' => $cost]);
}

public function store(Request $request)
{
    $validated = $request->validate([
    'menu' => 'required|string|max:255',
    'kategori' => 'required|string|max:255',
    'start_date' => 'required|date',
    'end_date' => 'required|date|after_or_equal:start_date',
    'price' => 'required|numeric|min:0',
    'per_cost' => 'required|numeric|min:0',
    'qty' => 'required|numeric|min:0',
    'cost' => 'required|numeric|min:0',
    'sales' => 'required|numeric|min:0',
]);

    DB::table('sales_potentials')->insert([
    'menu' => $validated['menu'],
    'kategori' => $validated['kategori'],
    'start_date' => $validated['start_date'],
    'end_date' => $validated['end_date'],
    'price' => $validated['price'],
    'per_cost' => $validated['per_cost'],
    'qty' => $validated['qty'],
    'cost' => $validated['cost'],
    'sales' => $validated['sales'],
    'created_at' => now(),
    'updated_at' => now(),
]);


    if ($request->ajax()) {
        return response()->json(['message' => 'Data saved successfully']);
    }

    return redirect()->back()->with('success', 'Data saved successfully');
}

public function destroy($id)
{
    // Delete the record
    DB::table('sales_potentials')->where('id', $id)->delete();

    // Find the smallest missing ID to reset AUTO_INCREMENT
    $existingIds = DB::table('sales_potentials')->orderBy('id')->pluck('id')->toArray();

    $nextId = 1;
    foreach ($existingIds as $existingId) {
        if ($existingId != $nextId) {
            // Found a gap
            break;
        }
        $nextId++;
    }

    // Reset AUTO_INCREMENT to nextId (lowest missing)
    DB::statement("ALTER TABLE sales_potentials AUTO_INCREMENT = $nextId");

    return response()->json(['success' => true]);
}

public function clear()
{
    Log::info('Clear method triggered');

    try {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        SalesPotentials::query()->delete();
        DB::statement('ALTER TABLE sales_potentials AUTO_INCREMENT = 1;');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return response()->json(['message' => 'All data cleared successfully.'], 200);
    } catch (\Exception $e) {
        Log::error('Clear data failed: ' . $e->getMessage());
        return response()->json(['message' => 'Failed to clear data: ' . $e->getMessage()], 500);
    }
}
















}
