<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Illuminate\Support\Facades\Storage;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->paginate(10);
        return view('unit', compact('units'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_unit' => 'required|string|unique:units,kode_unit',
            'nama_unit' => 'required|string',
            'baterai' => 'required|string',
            'status' => 'required|in:aktif,off',
            'foto' => 'nullable|image|max:2048',
        ]);

        if($request->hasFile('foto')){
            $data['foto'] = $request->file('foto')->store('unit_photos','public');
        }

        Unit::create($data);
        return redirect()->route('unit.index')->with('success','Unit berhasil ditambahkan.');
    }

    public function edit(Unit $unit)
    {
        return response()->json($unit);
    }

public function update(Request $request, Unit $unit)
{
    $data = $request->validate([
        'kode_unit' => 'required|string|unique:units,kode_unit,'.$unit->id,
        'nama_unit' => 'required|string',
        'baterai' => 'required|string',
        'status' => 'required|in:aktif,off',
        'foto' => 'nullable|image|max:2048',
    ]);

    if($request->hasFile('foto')){
        if($unit->foto && Storage::disk('public')->exists($unit->foto)){
            Storage::disk('public')->delete($unit->foto);
        }
        $data['foto'] = $request->file('foto')->store('unit_photos','public');
    }

    $unit->update($data);

    if ($request->ajax()) {
        return response()->json(['success' => true, 'unit' => $unit]);
    }

    return redirect()->route('unit.index')->with('success','Unit berhasil diupdate.');
}

    public function destroy(Unit $unit)
{
    // Delete the photo file if it exists
    if($unit->foto && Storage::disk('public')->exists($unit->foto)){
        Storage::disk('public')->delete($unit->foto);
    }

    // Delete the unit from the database
    $unit->delete();

    // Handle AJAX request
    if(request()->ajax()){
        return response()->json(['success' => true]);
    }

    // Redirect back with success message
    return redirect()->route('unit.index')->with('success', 'Unit berhasil dihapus.');
}
}
