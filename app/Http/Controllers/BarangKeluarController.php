<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\BarangKeluar;
use App\Models\RekapBarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
// App\Http\Controllers\BarangkeluarController.php
public function index()
{
    $bahans = Bahan::pluck('bahan');; 
    return view('barang_keluar', [
        'bahans' => $bahans,
        'pageTitle' => 'Barang Keluar'
    ]); // this MUST match the Blade file name
}

public function getBahanDetails(Bahan $bahan)
{
    return response()->json([
        'kategori' => $bahan->kategori,
        'unit' => $bahan->unit,
        'harga' => $bahan->harga,
        'created_at' => $bahan->created_at->toDateString(),
    ]);
}

public function store(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'items' => 'required|array',
        'items.*.bahan' => 'required|string',
        'items.*.kategori' => 'required|string',
        'items.*.qty' => 'required|numeric|min:1',
        'items.*.unit' => 'required|string',
        'items.*.harga' => 'required|numeric|min:0',
        'items.*.jumlah' => 'required|numeric|min:0',
        'summary' => 'required|array',
        'summary.dry' => 'numeric|min:0',
        'summary.veggies' => 'numeric|min:0',
        'summary.meat' => 'numeric|min:0',
        'summary.fruit' => 'numeric|min:0',
        'summary.total' => 'numeric|min:0',
        'summary.control' => 'numeric|min:0',
    ]);

    $items = $validated['items'];
    $summary = $validated['summary'];

    // Save barang_keluar
    foreach ($items as $item) {
        BarangKeluar::create([
            'bahan'    => $item['bahan'],
            'kategori' => $item['kategori'],
            'qty'      => $item['qty'],
            'unit'     => $item['unit'],
            'harga'    => $item['harga'],
            'jumlah'   => $item['jumlah'],
        ]);
    }

    // Save rekap_barang_keluar
    RekapBarangKeluar::create([
        'dry_good' => $summary['dry'] ?? 0,
        'veggies'  => $summary['veggies'] ?? 0,
        'meat'     => $summary['meat'] ?? 0,
        'fruit'    => $summary['fruit'] ?? 0,
        'total'    => $summary['total'] ?? 0,
        'control'  => $summary['control'] ?? 0,
    ]);

    return response()->json(['status' => 'success']);
}

public function saved_data()
{
    
    $barang_keluar = BarangKeluar::all();

    $summary = [
    'dry' => BarangKeluar::whereRaw('LOWER(kategori) LIKE ?', ['%dry%'])->sum('jumlah'),
    'veggies' => BarangKeluar::whereRaw('LOWER(kategori) LIKE ?', ['%veggies%'])->sum('jumlah'),
    'meat' => BarangKeluar::whereRaw('LOWER(kategori) LIKE ?', ['%meat%'])->sum('jumlah'),
    'fruit' => BarangKeluar::whereRaw('LOWER(kategori) LIKE ?', ['%fruit%'])->sum('jumlah'),
];

    

    $summary['total'] = $barang_keluar->sum('jumlah');
    $summary['control'] = 
        ($summary['dry'] ?? 0) +
        ($summary['veggies'] ?? 0) +
        ($summary['meat'] ?? 0) +
        ($summary['fruit'] ?? 0);
    $summary['control_zero'] = $summary['total'] - $summary['control'];

    return view('barangkeluar.saved_data', [
        'barang_keluar' => $barang_keluar,
        'summary' => $summary,
        'pageTitle' => 'Data Barang Keluar',
    ]);
}

public function clearAll()
{
    // Delete all records from barang_keluar table
    DB::table('barang_keluar')->truncate(); // truncates the table and resets auto-increment

    return response()->json(['message' => 'All data cleared successfully']);
}
public function destroy($id)
{
    $deleted = BarangKeluar::find($id);

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
        DB::table('barang_keluar')->insert([
            'id' => $row[0],
            'bahan' => $row[1],
            'kategori' => $row[2],
            'qty' => $row[3],
            'unit' => $row[4],
            'harga' => $row[5],
            'jumlah' => $row[6],
            'created_at' => $row[7],
            'updated_at' => now(),
        ]);
    }

    return response()->json(['message' => 'Imported successfully']);
}



}
