<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
Route::get('/bahan', function () {
    return view('bahan', ['pageTitle' => 'Bahan']);
})->name('bahan');

//Menu
Route::get('/menu', function () {
    return view('menu', ['pageTitle' => 'Menu']);
})->name('menu');

//Barang Masuk
Route::get('/barang_masuk', function () {
    return view('barang_masuk', ['pageTitle' => 'Barang Masuk']);
})->name('barang_masuk');

//Barang Keluar
Route::get('/barang_keluar', function () {
    return view('barang_keluar', ['pageTitle' => 'Barang Keluar']);
})->name('barang_keluar');

//Costing(Gudang)
Route::get('/costing', function () {
    return view('costing', ['pageTitle' => 'Costing(Gudang)']);
})->name('costing');

//Costing(Cafe)
Route::get('/costing2', function () {
    return view('costing2', ['pageTitle' => 'Costing(Café)']);
})->name('costing2');

//Costing(SSG)
Route::get('/costing3', function () {
    return view('costing3', ['pageTitle' => 'Costing(SSG)']);
})->name('costing3');

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
