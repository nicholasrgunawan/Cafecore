<?php

namespace App\Http\Controllers;

use App\Models\HppFood;
use App\Models\KonversiHargaBahan;
use App\Models\Menu;
use App\Models\SalesReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
     public function index()
    {
         $sales_report = SalesReport::with(['menu', 'hppFood'])->get();
         

        return view('menu_engineering', [
        'sales_report' => $sales_report,
        'pageTitle' => 'Sales Report',
    ]);
    }

    public function create()
{
    $hpp_foods = HppFood::select('id', 'menu', 'kategori', 'hjp_nett')->get();

    return view('add.add_sales_report', [
        'hpp_foods' => $hpp_foods,
        'pageTitle' => 'Create Sales Report',
    ]);
}

public function store(Request $request)
{
    $request->validate([
    'menu' => 'required|string',
    'kategori' => 'required|string',
    'qty' => 'required|numeric',
    'total' => 'required|numeric',
]);


    DB::table('sales_report')->insert([
    'menu' => $request->menu,
    'kategori' => $request->kategori,
    'qty' => $request->qty,
    'total' => $request->total,
    'created_at' => now(),
    'updated_at' => now(),
]);


    return redirect()->back()->with('success', 'Sales report saved!');
}


public function destroy($id)
{
    // Find and delete the record
    $salesReport = SalesReport::find($id);

    if (!$salesReport) {
        return response()->json(['success' => false, 'message' => 'Data not found']);
    }

    $salesReport->delete();

    // Check if table is empty after delete
    $count = SalesReport::count();

    if ($count === 0) {
        // Reset auto increment to 1
        DB::statement('ALTER TABLE sales_report AUTO_INCREMENT = 1');
    }

    return response()->json(['success' => true]);
}

public function clear()
{
    try {
        // Delete all records
        DB::table('sales_report')->truncate();

        // If using MySQL, TRUNCATE resets auto-increment by default.
        // If not, you can reset explicitly with raw SQL:
        // \DB::statement('ALTER TABLE sales_reports AUTO_INCREMENT = 1');

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}


}
