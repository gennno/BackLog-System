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
            'foto'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
    $filename = time() . '.' . $request->foto->extension();
    $request->foto->move(public_path('tool_photos'), $filename);
    $data['foto'] = 'tool_photos/' . $filename;
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
            'foto'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // hapus foto lama jika ada
            if ($tool->foto && file_exists(public_path('storage/' . $tool->foto))) {
                unlink(public_path('storage/' . $tool->foto));
            }

            $filename = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('storage/tool_photos'), $filename);
            $data['foto'] = 'tool_photos/' . $filename;
        }

        $tool->update($data);

        return redirect()->route('tools.index')->with('success', 'Tool berhasil diupdate!');
    }

    // Delete tool
    public function destroy(Tool $tool)
    {
        if ($tool->foto && file_exists(public_path('storage/' . $tool->foto))) {
            unlink(public_path('storage/' . $tool->foto));
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
