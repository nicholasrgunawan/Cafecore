<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all(); // Get all menus
        return view('menu', [
            'menus'   => $menus,
            'pageTitle' => 'Menu', // Page title
        ]);
    }

    public function store(Request $request)
    {
        // Validate the form inputs
        $validated = $request->validate([
            'menu' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'bahan' => 'required|array|min:1', // Ensure at least one bahan is selected
            'bahan.*' => 'required|string|max:255', // Ensure each bahan is a valid string
        ]);

        try {
            // Store new menu
            $menu = new Menu();
            $menu->menu = $request->menu;
            $menu->kategori = $request->kategori;
            $menu->bahan = implode(', ', $request->bahan); // Combine the bahan array into a single string
            $menu->save();

            return redirect()->route('menu.index')->with('success', 'Menu added successfully!');
        } catch (\Exception $e) {
            // Catch any errors that occur and show a message
            return redirect()->back()->with('error', 'Failed to create menu: ' . $e->getMessage());
        }
    }



    public function destroy($id)
    {
        // Delete the menu
        $menu = Menu::findOrFail($id);
        $menu->delete();

        // Get the new max ID for AUTO_INCREMENT adjustment
        $maxId = DB::table('menus')->max('id');
        $nextId = $maxId ? $maxId + 1 : 1;

        DB::statement("ALTER TABLE menus AUTO_INCREMENT = $nextId");

        return response()->json(['success' => 'Menu deleted successfully.']);
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $bahans = Bahan::all(); // Get all available bahan options
        return view('edit.edit_menu', compact('menu', 'bahans'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'menu' => 'required|string',
            'kategori' => 'required|string',
            'bahan' => 'required|array',
        ]);

        // Get bahan names from IDs
        $bahanNames = Bahan::whereIn('id', $request->bahan)->pluck('bahan')->toArray();
        $bahanString = implode(', ', $bahanNames); // Convert array to string

        // Update the menu
        $menu = Menu::findOrFail($id);
        $menu->menu = $request->menu;
        $menu->kategori = $request->kategori;
        $menu->bahan = $bahanString; // Save as string
        $menu->save();

        return redirect()->route('menu.index')->with('success', 'Menu updated successfully!');
    }

    public function create()
    {
        $bahans = Bahan::pluck('bahan');
        return view('add.add_menu', compact('bahans')); // ✅ THIS IS IMPORTANT
    }
}
