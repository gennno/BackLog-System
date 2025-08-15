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
    $backlogs = Backlog::orderByRaw("
        CASE 
            WHEN status = 'Open BL' THEN 0
            ELSE 1
        END
    ")
    ->orderByRaw("
        CASE 
            WHEN status = 'Open BL' THEN created_at
            ELSE NULL
        END ASC
    ")
    ->orderByRaw("
        CASE 
            WHEN status != 'Open BL' THEN created_at
            ELSE NULL
        END DESC
    ")
    ->paginate(25);

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

    // Validate only the fields you want to update
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
        'evidence' => 'nullable|image|max:2048', // optional image
    ]);

    // Handle evidence upload
    if ($request->hasFile('evidence')) {
        // Delete old evidence if exists
        if ($backlog->evidence && Storage::exists($backlog->evidence)) {
            Storage::delete($backlog->evidence);
        }
        $path = $request->file('evidence')->store('evidence', 'public');
        $validated['evidence'] = $path;
    }

    // Update backlog (excluding tanggal/created_at)
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
