<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\RekapBarangKeluar;
use App\Models\RekapBarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CostingSSGController extends Controller
{
     public function index()
    {
    $pemasukan = DB::table('rekap_barang_masuk')->get();
    $pengeluaran = DB::table('rekap_barang_keluar')->get();

    $categories = ['dry_good', 'veggies', 'meat', 'fruit'];

    $result = [];

    foreach ($pemasukan as $row) {
    foreach ($categories as $kategori) {
        $value = $row->$kategori;
        if ($value > 0) {
            $date = Carbon::parse($row->created_at)->format('Y-m-d');

            $matchedPengeluaran = $pengeluaran->first(function ($p) use ($date) {
                return Carbon::parse($p->created_at)->format('Y-m-d') === $date;
            });

            $result[] = [
                'date' => $date,
                'kategori' => $kategori,
                'pembelanjaan' => $value,
                'pengeluaran' => $matchedPengeluaran?->{$kategori} ?? 0,
            ];
        }
    }
}

    // Totals
    $totalPemasukan = 0;
    $totalPengeluaran = 0;

    foreach ($categories as $kategori) {
        $totalPemasukan += $pemasukan->sum($kategori);
        $totalPengeluaran += $pengeluaran->sum($kategori);
    }

    return view('costing3', [
        'data' => $result,
        'totalPemasukan' => $totalPemasukan,
        'totalPengeluaran' => $totalPengeluaran,
        'pageTitle' => 'Costing SSG'
    ]);
    
    }
}
