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
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Models\BarangMasuk;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Route::middleware("auth")->group(function () {
    Route::view('/', 'dashboard')->name("dashboard");

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Login Routes
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
Route::get('menu/create', [MenuController::class, 'create'])->name('menu.create');
Route::post('/menu/store', [MenuController::class, 'store'])->name('menu.store');
Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');
Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
Route::put('/menu/{id}', [MenuController::class, 'update'])->name('menu.update');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/add_menu', [MenuController::class, 'create'])->name('add_menu');

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
Route::get('/standard_recipe', function () {
    return view('standard_recipe', ['pageTitle' => 'Standard Recipe(Konversi Harga Bahan)']);
})->name('standard_recipe');

//REV HPP Food
Route::get('/standard_recipe2', function () {
    return view('standard_recipe2', ['pageTitle' => 'Standard Recipe(HPP Food)']);
})->name('standard_recipe2');

//REV Menu Pricing
Route::get('/standard_recipe3', function () {
    return view('standard_recipe3', ['pageTitle' => 'Standard Recipe(Menu Pricing)']);
})->name('standard_recipe3');

//Menu Engineering
Route::get('/menu_engineering', function () {
    return view('menu_engineering', ['pageTitle' => 'Sales Report']);
})->name('menu_engineering');

//Menu Engineering2
Route::get('/menu_engineering2', function () {
    return view('menu_engineering2', ['pageTitle' => 'Rekap Sales']);
})->name('menu_engineering2');

//Menu Engineering3
Route::get('/menu_engineering3', function () {
    return view('menu_engineering3', ['pageTitle' => 'Sales & Potentials']);
})->name('menu_engineering3');

//Menu Engineering4
Route::get('/menu_engineering4', function () {
    return view('menu_engineering4', ['pageTitle' => 'Main']);
})->name('menu_engineering4');

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

