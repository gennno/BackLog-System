<?php

namespace App\Http\Controllers;

use App\Models\Backlog;
use App\Exports\BacklogExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class TindakanController extends Controller
{
public function index()
{
    // ambil semua data langsung, urutkan tanggal terbaru dulu
    $backlogs = Backlog::orderBy('tanggal_temuan', 'desc')->get();

    return view('tindakan', compact('backlogs'));
}



    public function export()
    {
        return Excel::download(new BacklogExport, 'backlogs.xlsx');
    }

    public function show($id)
    {
        $backlog = Backlog::findOrFail($id); // Get the backlog or fail
        return view('detail-tindakan', compact('backlog'));
    }

public function update(Request $request, $id)
{
    $backlog = Backlog::findOrFail($id);

    $validated = $request->validate([
        'id_inspeksi' => 'required|string|max:255',
        'code_number' => 'nullable|string|max:255',
        'hm' => 'nullable|numeric',
        'component' => 'nullable|string|max:255',
        'plan_repair' => 'nullable|string|max:255',
        'status' => 'required|string|max:255',
        'condition' => 'required|string|max:255',
        'gl_pic' => 'nullable|string|max:255',
        'pic_daily' => 'nullable|string|max:255',
        'deskripsi' => 'nullable|string',
        'evidence' => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:2048',

        // ✅ newly added fields
        'part_number' => 'nullable|string|max:255',
        'part_name'   => 'nullable|string|max:255',
        'no_figure'   => 'nullable|string|max:255',
        'qty'         => 'nullable|integer|min:1',
        'close_by'    => 'nullable|string|max:255',
        'id_action'   => 'nullable|string|max:255',
    ]);

// Handle new evidence upload
if ($request->hasFile('evidence')) {
    // Delete old evidence if exists
    if ($backlog->evidence && file_exists(base_path('public_html/' . $backlog->evidence))) {
        unlink(base_path('public_html/' . $backlog->evidence));
    }

    $file = $request->file('evidence');
    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $destination = base_path('public_html/uploads/evidence');
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }

    $file->move($destination, $filename);
    $validated['evidence'] = 'uploads/evidence/' . $filename;
}


    $backlog->update($validated);

    return redirect()->route('detail-tindakan', $backlog->id)
                     ->with('success', 'Backlog updated successfully!');
}




    public function store(Request $request)
    {
        $request->validate([
            'id'        => 'required|exists:backlog,id',    
            'status'    => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        $backlog = Backlog::findOrFail($request->id);
        $backlog->update([
            'status'    => $request->status,
            'deskripsi' => $request->deskripsi,
            'close_by'  => Auth::user()->name,
        ]);

        return redirect()->route('tindakan.index')->with('success', 'Tindakan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tindakan = Backlog::findOrFail($id);
        $tindakan->delete();

        return redirect()->route('tindakan.index')->with('success', 'Data berhasil dihapus.');
    }
}
