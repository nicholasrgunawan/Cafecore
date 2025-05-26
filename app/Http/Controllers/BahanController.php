<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bahan;
use Illuminate\Support\Facades\DB;

class BahanController extends Controller
{
    public function index()
    {
        $bahans = Bahan::all();

    return view('bahan', [
        'bahans'   => $bahans,
        'pageTitle' => 'Bahan',
    ]);       
    }

    public function store(Request $request)
{
    // Validate the form inputs
    $request->validate([
        'bahan' => 'required|string|max:255',
        'merk' => 'required|string|max:255',
        'harga' => 'required|numeric',
        'kategori' => 'required|string|max:255',
        'qty' => 'required|numeric',
        'unit' => 'required|string|max:255',
    ]);

    // Store Bahan
    Bahan::create([
        'bahan' => $request->bahan,
        'merk' => $request->merk,
        'harga' => $request->harga,
        'kategori' => $request->kategori,
        'qty' => $request->qty,
        'unit' => $request->unit,
    ]);

    return redirect()->back()->with('success', 'Bahan added successfully!');
}


public function destroy($id)
{
    // Delete the user
    Bahan::destroy($id);

    // Get the new max ID
    $maxId = DB::table('bahans')->max('id');

    // Set AUTO_INCREMENT to max + 1 (or 1 if no users left)
    $nextId = $maxId ? $maxId + 1 : 1;
    DB::statement("ALTER TABLE bahans AUTO_INCREMENT = $nextId");

    return response()->json(['success' => 'Bahan deleted successfully.']);
}


    public function edit($id)
    {
        $bahan = Bahan::findOrFail($id); // ✅ Ensures bahan is found or throws 404
        return view('edit.edit_bahan', [
            'bahan' => $bahan,
            'pageTitle' => 'Edit Bahan' // Set the page title here
        ]); // ✅ Passes $bahan to view
    }

    public function update(Request $request, $id)
    {
        $bahan = Bahan::findOrFail($id);
        $bahan->bahan = $request->input('bahan');
        $bahan->merk = $request->input('merk');
        $bahan->harga = $request->input('harga');
        $bahan->kategori = $request->input('kategori');
        $bahan->qty = $request->input('qty');
        $bahan->unit = $request->input('unit');

        $bahan->save();

        return redirect()->route('bahan')->with('success', 'Bahan updated successfully.');
    }

    
}
