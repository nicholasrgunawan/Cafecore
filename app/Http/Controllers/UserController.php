<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('users', [
            'users'     => $users,
            'pageTitle' => 'Users',
        ]);
    }

    public function store(Request $request)
{
    // Validate inputs
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'password' => 'required|string|min:8',
    ]);
    

    // Store user
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password), // hash the password
    ]);

    return redirect()->back()->with('success', 'User registered successfully!');
}

public function destroy($id)
{
    // Delete the user
    User::destroy($id);

    // Get the new max ID
    $maxId = DB::table('users')->max('id');

    // Set AUTO_INCREMENT to max + 1 (or 1 if no users left)
    $nextId = $maxId ? $maxId + 1 : 1;
    DB::statement("ALTER TABLE users AUTO_INCREMENT = $nextId");

    return response()->json(['success' => 'User deleted successfully.']);
}

public function edit($id)
{
    $user = User::findOrFail($id); // ✅ Ensures user is found or throws 404
    return view('edit.edit_user', [
        'user' => $user,
        'pageTitle' => 'Edit User' // Set the page title here
    ]); // ✅ Passes $user to view
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    
    if ($request->filled('password')) {
        $user->password = bcrypt($request->input('password'));
    }

    $user->save();

    return redirect()->route('users')->with('success', 'User updated successfully.');
}



    
}
