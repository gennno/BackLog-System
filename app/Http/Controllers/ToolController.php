<?php

namespace App\Http\Controllers;

use App\Exports\ToolExport;
use App\Models\Tool;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ToolController extends Controller
{
    // Show tools page
    public function index()
    {
        $tools = Tool::all(); // or paginate()
        return view('tools', compact('tools'));
    }

// Store new tool
public function store(Request $request)
{
    $data = $request->validate([
        'lokasi'    => 'required|string',
        'nama_tool' => 'required|string',
        'status'    => 'required|in:Baik,Rusak',
        'deskripsi' => 'required|string',
        'foto'      => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:2048',
    ]);

if ($request->hasFile('foto')) {
    $file = $request->file('foto');
    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $destination = $_SERVER['DOCUMENT_ROOT'] . '/tool_photos';
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }

    $file->move($destination, $filename);
    $data['foto'] = 'tool_photos/' . $filename; // relative path
}


    Tool::create($data);

    return redirect()->route('tools.index')->with('success', 'Tool berhasil ditambahkan!');
}

public function edit(Tool $tool)
{
    return response()->json($tool); // for JS modal to fill form
}

public function update(Request $request, Tool $tool)
{
    $data = $request->validate([
        'lokasi'    => 'required|string',
        'nama_tool' => 'required|string',
        'status'    => 'required|in:Baik,Rusak',
        'deskripsi' => 'required|string',
        'foto'      => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:2048',
    ]);

if ($request->hasFile('foto')) {
    // Delete old photo if exists
    if ($tool->foto && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $tool->foto)) {
        unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $tool->foto);
    }

    $file = $request->file('foto');
    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $destination = $_SERVER['DOCUMENT_ROOT'] . '/tool_photos';
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }

    $file->move($destination, $filename);
    $data['foto'] = 'tool_photos/' . $filename; // relative path
}


    $tool->update($data);

    return redirect()->route('tools.index')->with('success', 'Tool berhasil diupdate!');
}


    // Delete tool
    public function destroy(Tool $tool)
    {
if ($tool->foto && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $tool->foto)) {
    unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $tool->foto);
}


        $tool->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('tools.index')->with('success', 'Tool berhasil dihapus.');
    }

        public function export()
    {
        return Excel::download(new ToolExport, 'tool.xlsx');
    }
}
