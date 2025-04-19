<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Import User model

class AuthController extends Controller
{
    public function login()
    {
        return view("auth.login");
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        // Check if the email exists in the database
        $user = User::where("email", $request->email)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Email is not registered.',
            ])->withInput();
        }

        // Attempt login
        if (Auth::attempt($request->only("email", "password"))) {
            return redirect()->intended(route("dashboard"));
        }

        return redirect()->route('login')->withErrors([
            'password' => 'Incorrect password.', // Error only for password now
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
