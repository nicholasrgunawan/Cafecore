<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\CostingCafeController;
use App\Http\Controllers\CostingGudangController;
use App\Http\Controllers\CostingSSGController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HppFoodController;
use App\Http\Controllers\KategoriMenusController;
use App\Http\Controllers\KonversiHargaBahanController;
use App\Http\Controllers\MainMenuEngineeringController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuPricingController;
use App\Http\Controllers\RekapSalesController;
use App\Http\Controllers\SalesPotentialsController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\UserController;
use App\Models\BarangMasuk;
use App\Models\HppFood;
use App\Models\KonversiHargaBahan;
use App\Models\MainMenuEngineering;
use App\Models\SalesReport;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
Route::middleware('guest')->group(function () {
 Route::get('/login', [AuthController::class, "login"])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name("login.post");

// Forgot Password Route
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

// Handle Forgot Password Form Submission
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    // Check if the email exists in the database
    $user = User::where('email', $request->input('email'))->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email is not registered.']);
    }

    // Send the reset password link
    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

// Show Reset Password Form
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

// Handle Reset Password Submission
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill(['password' => bcrypt($password)])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', 'Your password has been changed')
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.update');   

});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Add more protected routes here...



// Login Routes


//Dashboard
Route::get('/dashboard', function () {
    return view('dashboard', ['pageTitle' => 'Dashboard']);
})->name('dashboard');

//Bahan
Route::get('/add_bahan', function () {
    return view('add.add_bahan', ['pageTitle' => 'Add Bahan']);
})->name('add_bahan');

Route::get('/edit_bahan', function () {
    return view('edit.edit_bahan', ['pageTitle' => 'Edit Bahan']);
})->name('edit_bahan');

Route::post('/bahan/store', [BahanController::class, 'store'])->name('bahan.store');
Route::delete('/bahan/{id}', [BahanController::class, 'destroy'])->name('bahan.destroy');
Route::get('/bahan/{id}/edit', [BahanController::class, 'edit'])->name('bahan.edit');
Route::put('/bahan/{id}', [BahanController::class, 'update'])->name('bahan.update');
Route::get('/bahan', [BahanController::class, 'index'])->name('bahan');


//Menu
Route::get('/edit_menu', function () {
    return view('edit.edit_menu', ['pageTitle' => 'Edit Menu']);
})->name('edit_menu');
Route::get('/menus', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('menu/create', [MenuController::class, 'create'])->name('menu.create');
Route::post('/menu/store', [MenuController::class, 'store'])->name('menu.store');
Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');
Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
Route::put('/menu/{id}', [MenuController::class, 'update'])->name('menu.update');
Route::get('/add_menu', [MenuController::class, 'create'])->name('add_menu');

//kategori_menus
Route::get('/kategori_menus', [KategoriMenusController::class, 'index'])->name('kategori_menu.index');
Route::get('/kategori_menu', [KategoriMenusController::class, 'index'])->name('kategori_menu');
Route::get('kategori_menu/create', [KategoriMenusController::class, 'create'])->name('kategori_menu.create');
Route::get('/add_kategori_menu', [KategoriMenusController::class, 'create'])->name('add_kategori_menu');
Route::post('/kategori_menu', [KategoriMenusController::class, 'store'])->name('kategori_menu.store');
Route::delete('/kategori_menu/{id}', [KategoriMenusController::class, 'destroy'])->name('kategori-menu.destroy');
Route::get('/kategori_menu/{id}/edit', [KategoriMenusController::class, 'edit'])->name('kategori_menu.edit');
Route::put('/kategori_menu/{id}', [KategoriMenusController::class, 'update'])->name('kategori_menu.update');

//Barang Masuk
Route::get('/get-bahan-details/{bahan}', [BarangMasukController::class, 'getBahanDetails']);
Route::get('/barang_masuk', [BarangMasukController::class, 'index'])->name('barang_masuk');
Route::post('/save-barang-masuk', [BarangMasukController::class, 'store']);
Route::get('/barangmasuk/saved_data', [BarangMasukController::class, 'saved_data'])->name('barangmasuk.saved_data');
Route::delete('/barang-masuk/clear', [App\Http\Controllers\BarangMasukController::class, 'clearAll'])->name('barang-masuk.clear');
Route::delete('/barang-masuk/{id}', [BarangMasukController::class, 'destroy'])->name('barang-masuk.destroy');
Route::post('/barang-masuk/import', [BarangMasukController::class, 'import'])->name('barang-masuk.import');

//Barang Keluar
Route::get('/get-bahan-details/{bahan}', [BarangKeluarController::class, 'getBahanDetails']);
Route::get('/barang_keluar', [BarangKeluarController::class, 'index'])->name('barang_keluar');
Route::post('/save-barang-keluar', [BarangKeluarController::class, 'store']);
Route::get('/barangkeluar/saved_data', [BarangKeluarController::class, 'saved_data'])->name('barangkeluar.saved_data');
Route::delete('/barang-keluar/clear', [App\Http\Controllers\BarangKeluarController::class, 'clearAll'])->name('barang-keluar.clear');
Route::delete('/barang-keluar/{id}', [BarangKeluarController::class, 'destroy'])->name('barang-keluar.destroy');
Route::post('/barang-keluar/import', [BarangKeluarController::class, 'import'])->name('barang-keluar.import');



//Costing(Gudang)
Route::get('/costing', [CostingGudangController::class, 'index'])->name('costing');


//Costing(Cafe)
Route::get('/costing2', [CostingCafeController::class, 'index'])->name('costing2');

//Costing(SSG)
Route::get('/costing3', [CostingSSGController::class, 'index'])->name('costing3');

//REV Konversi Harga Barang
Route::get('/get-bahan-details/{bahan}', [KonversiHargaBahanController::class, 'getBahanDetails']);
Route::get('/standard_recipe', [KonversiHargaBahanController::class, 'index'])->name('standard_recipe');
Route::post('/save-standard-recipe', [KonversiHargaBahanController::class, 'store'])->name('standardrecipe.store');
Route::get('/standardrecipe/saved_data', [KonversiHargaBahanController::class, 'saved_data'])->name('standardrecipe.saved_data');
Route::delete('/standard-recipe/clear', [KonversiHargaBahanController::class, 'clear'])->name('standard-recipe.clear');
Route::delete('/standard-recipe/{id}', [KonversiHargaBahanController::class, 'destroy'])->name('standard-recipe.destroy');
Route::post('/standard-recipe/import', [KonversiHargaBahanController::class, 'import'])->name('standard-recipe.import');

//REV Menu Pricing
Route::get('/menu_pricing', [MenuPricingController::class, 'index'])->name('menu_pricing');
Route::get('/add_menu_pricing', [MenuPricingController::class, 'create'])->name('add_menu_pricing');
Route::get('/add_menu_summary', [MenuPricingController::class, 'create2'])->name('add_menu_summary');
Route::get('/edit_menu_pricing', [MenuPricingController::class, 'edit'])->name('edit_menu_pricing');
Route::get('/edit_menu_summary', [MenuPricingController::class, 'edit2'])->name('edit_menu_summary');
Route::delete('/menu-pricing/{id}', [MenuPricingController::class, 'destroy'])->name('menu-pricing.destroy');
Route::delete('/menu-summary/{id}', [MenuPricingController::class, 'destroy2'])->name('menu-summary.destroy');
Route::post('/menu_pricing/store', [MenuPricingController::class, 'store'])->name('menu_pricing.store');
Route::post('/menu_summary', [MenuPricingController::class, 'store2'])->name('menu_summary.store');
Route::get('/get-conv/{id}', [App\Http\Controllers\MenuPricingController::class, 'getConv']);
Route::delete('/menu-pricing/clear', [MenuPricingController::class, 'clearMenuPricing'])->name('menu-pricing.clear');
Route::delete('/menu-summary/clear', [MenuPricingController::class, 'clearMenuSummary'])->name('menu-summary.clear');
Route::get('/menu-pricing/{id}/edit', [MenuPricingController::class, 'edit'])->name('menu_pricing.edit');
Route::put('/menu-pricing/{id}', [MenuPricingController::class, 'update'])->name('menu_pricing.update');



//REV HPP Food
Route::get('/hpp_food', [HppFoodController::class, 'index'])->name('hpp_food');
Route::post('/save-hpp-food', [HppFoodController::class, 'save'])->name('save.hpp_food');
Route::get('/hppfood/saved_data', [HppFoodController::class, 'saved_data'])->name('hppfood.saved_data');
Route::delete('/hppfood/clear', [HppFoodController::class, 'clearAll'])->name('hppfood.clear');
Route::delete('/hppfood/{id}', [HppFoodController::class, 'destroy'])->name('hppfood.destroy');
Route::delete('/sales-report/{id}', [SalesReportController::class, 'destroy'])->name('sales-report.destroy');




//Menu Engineering
Route::get('/menu_engineering', [SalesReportController::class, 'index'])->name('menu_engineering');
Route::get('/add_sales_report', [SalesReportController::class, 'create'])->name('add_sales_report');
Route::post('/sales-report', [SalesReportController::class, 'store'])->name('sales_report.store');
Route::delete('/sales-report/{id}', [SalesReportController::class, 'destroy'])->name('sales-report.destroy');
Route::post('/sales-report/clear', [SalesReportController::class, 'clear'])->name('sales-report.clear');




//Menu Engineering2
Route::get('/menu_engineering2', [RekapSalesController::class, 'index'])->name('menu_engineering2');

//Menu Engineering3
Route::get('/menu_engineering3', [SalesPotentialsController::class, 'index'])->name('menu_engineering3');
Route::get('/add_sales_potentials', [SalesPotentialsController::class, 'create'])->name('add_sales_potentials');
Route::get('/get-sales-data', [SalesPotentialsController::class, 'getSalesData']);
Route::get('/get-amount-cost', [SalesPotentialsController::class, 'getAmountCost']);
Route::get('/get-amount-sales', [SalesPotentialsController::class, 'getAmountSales']);
Route::get('/get-price', [SalesPotentialsController::class, 'getPrice']);
Route::get('/get-per-cost', [SalesPotentialsController::class, 'getCost']);
Route::post('/sales_potentials/store', [SalesPotentialsController::class, 'store'])->name('sales_potentials.store');
Route::delete('/sales-potentials/{id}', [SalesPotentialsController::class, 'destroy'])->name('sales-potentials.destroy');
Route::delete('/sales-potentials/clear', [SalesPotentialsController::class, 'clear'])->name('sales-potentials.clear');




//Menu Engineering4
Route::get('/menu_engineering4', [MainMenuEngineeringController::class, 'index'])->name('menu_engineering4');
Route::post('/menu-engineerings/save', [MainMenuEngineeringController::class, 'save'])->name('menu-engineerings.save');
Route::get('/menu-engineering/saved', [MainMenuEngineeringController::class, 'viewSavedData'])->name('menuengineering.saved_data');
Route::get('/menu-engineering/latest-summary', [MainMenuEngineeringController::class, 'getLatestSummary'])->name('menu-engineering.getLatestSummary');
Route::delete('/menu-engineerings/{id}', [MainMenuEngineeringController::class, 'destroy'])->name('menuengineerings.destroy');
Route::delete('/menu-engineering-summary/{id}', [MainMenuEngineeringController::class, 'destroySummary'])
    ->name('menuengineeringsummary.destroy');

//Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');





//Users
Route::get('/users', [UserController::class, 'index'])->name('users');

Route::get('/add_user', function () {
    return view('add.add_user', ['pageTitle' => 'Add User']);
})->name('add_user');

Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('delete_user');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::get('/users', [UserController::class, 'index'])->name('users');



Route::get('/edit_user', function () {
    return view('edit.edit_user', ['pageTitle' => 'Edit User']);
})->name('edit_user');

});