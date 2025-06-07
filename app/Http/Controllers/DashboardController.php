<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\SalesReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
{
    $totalUsers = \App\Models\User::count();
    $totalBarangMasuk = BarangMasuk::count();
    $totalBarangKeluar = BarangKeluar::count();
    $totalSales = SalesReport::sum('total');

    $monthlySales = SalesReport::select(
        DB::raw("DATE_FORMAT(created_at, '%M') as month"),
        DB::raw("SUM(total) as total")
    )
    ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M')"))
    ->orderBy(DB::raw("MIN(created_at)")) // To maintain month order
    ->get();

    // Get current year and month in Y-m format
    $currentMonth = now()->format('Y-m');
    $lastMonth = now()->subMonth()->format('Y-m');

    // Sum sales for current month
    $thisMonthSales = SalesReport::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
        ->sum('total');

    // Sum sales for last month
    $lastMonthSales = SalesReport::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$lastMonth])
        ->sum('total');

    // Determine trend: up, down, or neutral
    if ($thisMonthSales > $lastMonthSales) {
        $salesTrend = 'up';
    } elseif ($thisMonthSales < $lastMonthSales) {
        $salesTrend = 'down';
    } else {
        $salesTrend = 'neutral';
    }

    return view('dashboard', [
        'totalUsers' => $totalUsers,
        'totalBarangMasuk' => $totalBarangMasuk,
        'totalBarangKeluar' => $totalBarangKeluar,
        'totalSales' => $totalSales,
        'monthlySales' => $monthlySales,
        'thisMonthSales' => $thisMonthSales,
        'lastMonthSales' => $lastMonthSales,
        'salesTrend' => $salesTrend,
        'pageTitle' => 'Dashboard'
    ]);
}
}
