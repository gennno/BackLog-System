<?php

namespace App\Http\Controllers;


use App\Exports\UnitExport;
use Illuminate\Http\Request;
use App\Models\Unit;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->paginate(10);
        return view('unit', compact('units'));
    }
        public function export()
    {
        return Excel::download(new UnitExport, 'unit.xlsx');
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

// Store method
if ($request->hasFile('foto')) {
    $file = $request->file('foto');
    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $destination = base_path('public_html/unit_photos');
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }

    $file->move($destination, $filename);
    $data['foto'] = 'unit_photos/' . $filename;
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

// Update method
if ($request->hasFile('foto')) {
    // Delete old file if exists
    if ($unit->foto && file_exists(base_path('public_html/' . $unit->foto))) {
        unlink(base_path('public_html/' . $unit->foto));
    }

    $file = $request->file('foto');
    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $destination = base_path('public_html/unit_photos');
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }

    $file->move($destination, $filename);
    $data['foto'] = 'unit_photos/' . $filename;
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
