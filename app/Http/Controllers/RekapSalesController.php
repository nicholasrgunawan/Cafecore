<?php

namespace App\Http\Controllers;

use App\Models\SalesReport;
use Illuminate\Http\Request;

class RekapSalesController extends Controller
{
    public function index()
{
    $sales_report = SalesReport::all();
    $totalSales = $sales_report->sum('total'); // Calculate total here

    return view('menu_engineering2', [
        'sales_report' => $sales_report,
        'totalSales' => $totalSales,
        'pageTitle' => 'Rekap Sales',
    ]);
}

}
