<?php

namespace App\Http\Controllers;

use App\Models\RekapBarangMasuk;
use Illuminate\Http\Request;

class CostingGudangController extends Controller
{
    public function index()
{
    $rekap_barang_masuk = RekapBarangMasuk::select(
        'created_at',
        'dry_good',
        'veggies',
        'meat',
        'fruit',
        'total',
        'control'
    )->get();

    return view('costing', [
        'rekap_barang_masuk' => $rekap_barang_masuk,
        'pageTitle' => 'Costing Gudang'
    ]);
}

}
