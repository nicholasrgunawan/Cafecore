<?php

namespace App\Http\Controllers;

use App\Models\RekapBarangKeluar;
use Illuminate\Http\Request;

class CostingCafeController extends Controller
{
    public function index()
{
    $rekap_barang_keluar = RekapBarangKeluar::select(
        'created_at',
        'dry_good',
        'veggies',
        'meat',
        'fruit',
        'total',
        'control'
    )->get();

    return view('costing2', [
        'rekap_barang_keluar' => $rekap_barang_keluar,
        'pageTitle' => 'Costing Cafe'
    ]);
}
}
