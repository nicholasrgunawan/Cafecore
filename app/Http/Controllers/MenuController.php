<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\KategoriMenu;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kategoriMenu')->get(); // Get all menus
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
        'kategori_menu_id' => 'required|exists:kategori_menus,id',
        'desc' => 'required|string|max:255',
    ]);

    try {
        // Store new menu
        $menu = new Menu();
        $menu->menu = $request->menu;
        $menu->kategori_menu_id = $request->kategori_menu_id;
        $menu->desc = $request->desc;
        $menu->save();

        return redirect()->route('menu.index')->with('success', 'Menu added successfully!');
    } catch (\Exception $e) {
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
        $kategori_menus = KategoriMenu::select('id', 'kategori')->get();
        $bahans = Bahan::all(); // Get all available bahan options
        return view('edit.edit_menu', compact('menu', 'bahans','kategori_menus'));
    }


    public function update(Request $request, $id)
{
    $request->validate([
        'menu' => 'required|string',
        'kategori_menu_id' => 'required|exists:kategori_menus,id',
        'desc' => 'required|string',
    ]);

    $menu = Menu::findOrFail($id);
    $menu->menu = $request->menu;
    $menu->kategori_menu_id = $request->kategori_menu_id;
    $menu->desc = $request->desc;
    $menu->save();

    return redirect()->route('menu.index')->with('success', 'Menu updated successfully!');
}


    public function create()
    {
        $kategori_menus = KategoriMenu::select('id', 'kategori')->get();
        return view('add.add_menu', [
        'kategori_menus' => $kategori_menus,
        'pageTitle' => 'Create Menu',
    ]);
    }
}
