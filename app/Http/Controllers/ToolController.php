<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;
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
        'kode_tool' => 'required|string|unique:tools,kode_tool',
        'nama_tool' => 'required|string',
        'status' => 'required|in:Baik,Rusak',
        'deskripsi' => 'required|string',
        'foto' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('foto')) {
        $data['foto'] = $request->file('foto')->store('tool_photos', 'public');
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
        'kode_tool' => 'required|string|unique:tools,kode_tool,' . $tool->id,
        'nama_tool' => 'required|string',
        'status' => 'required|in:Baik,Rusak',
        'deskripsi' => 'required|string',
        'foto' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('foto')) {
        if ($tool->foto && Storage::disk('public')->exists($tool->foto)) {
            Storage::disk('public')->delete($tool->foto);
        }
        $data['foto'] = $request->file('foto')->store('tool_photos', 'public');
    }

    $tool->update($data);

    return redirect()->route('tools.index')->with('success', 'Tool berhasil diupdate!');
}

    // Delete tool
    public function destroy(Tool $tool)
    {
        if($tool->foto && Storage::disk('public')->exists($tool->foto)){
            Storage::disk('public')->delete($tool->foto);
        }
        $tool->delete();

        if(request()->ajax()){
            return response()->json(['success' => true]);
        }

        return redirect()->route('tools.index')->with('success', 'Tool berhasil dihapus.');
    }
}
