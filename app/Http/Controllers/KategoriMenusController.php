<?php

namespace App\Http\Controllers;

use App\Models\KategoriMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriMenusController extends Controller
{
    public function index()
    {
        $kategori_menus = KategoriMenu::all(); // Get all menus
        return view('kategori_menu', [
            'kategori_menus'   => $kategori_menus,
            'pageTitle' => 'Kategori Menu', // Page title
        ]);
    }

    public function create()
    {
        return view('add.add_kategori_menu', [
            'pageTitle' => 'Add Kategori', // Page title
        ]); // ✅ THIS IS IMPORTANT
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:255',
            'desc' => 'required|string|max:255',
        ]);

        KategoriMenu::create([
            'kategori' => $request->kategori,
            'desc' => $request->desc,
        ]);


        return redirect()->back()->with('success', 'Kategori menu added successfully!');
    }

    public function edit($id)
    {
        $kategori_menu = KategoriMenu::findOrFail($id);
        return view('edit.edit_kategori_menu', compact('kategori_menu'));
    }

    public function destroy($id)
    {
        // Delete the menu
        $kategori_menu = KategoriMenu::findOrFail($id);
        $kategori_menu->delete();

        // Get the new max ID for AUTO_INCREMENT adjustment
        $maxId = DB::table('kategori_menus')->max('id');
        $nextId = $maxId ? $maxId + 1 : 1;

        DB::statement("ALTER TABLE kategori_menus AUTO_INCREMENT = $nextId");

        return response()->json(['success' => 'Menu deleted successfully.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|string',
            'desc' => 'required|string',
        ]);

      

        // Update the menu
        $kategori_menu = KategoriMenu::findOrFail($id);
        $kategori_menu->kategori = $request->kategori;
        $kategori_menu->desc = $request->desc;
        $kategori_menu->save();

        return redirect()->route('kategori_menu.index')->with('success', 'Menu updated successfully!');
    }
}
