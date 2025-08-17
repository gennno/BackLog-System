<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\Backlog;
use Illuminate\Http\Request;

class PerbaikanController extends Controller
{
    public function index()
    {
        $repairs = Repair::latest()->get();
        $backlogs = Backlog::all();

        return view('perbaikan', compact('repairs', 'backlogs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_backlog' => 'nullable|exists:backlog,id',
            'kode_unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'hm' => 'nullable|integer',
            'component' => 'nullable|string|max:100',
            'evidence_temuan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pic_daily' => 'nullable|string|max:100',
            'gl_pic' => 'nullable|string|max:100',
            'evidence_perbaikan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pic' => 'nullable|string|max:100',
            'status' => 'required|string|in:Open,Closed',
            'nama_pembuat' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('evidence_temuan')) {
            $data['evidence_temuan'] = $request->file('evidence_temuan')->store('evidences', 'public');
        }
        if ($request->hasFile('evidence_perbaikan')) {
            $data['evidence_perbaikan'] = $request->file('evidence_perbaikan')->store('evidences', 'public');
        }

        Repair::create($data);

        return redirect()->route('perbaikan.index')->with('success', 'Perbaikan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $repair = Repair::findOrFail($id);
        return response()->json($repair);
    }

public function update(Request $request, $id)
{
    // ✅ 1. Validate incoming data
    $validated = $request->validate([
        'hm' => 'nullable|numeric',
        'component' => 'nullable|string|max:255',
        'evidence_perbaikan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'pic_daily' => 'nullable|string|max:255',
        'gl_pic' => 'nullable|string|max:255',
        'pic' => 'nullable|string|max:255',
        'status' => 'required|in:Open,Closed',
        'deskripsi' => 'nullable|string',
    ]);

    // ✅ 2. Find the repair record
    $repair = Repair::findOrFail($id);

    // ✅ 3. Handle file upload if provided
    if ($request->hasFile('evidence_perbaikan')) {
        // Delete old file if exists
        if ($repair->evidence_perbaikan && \Storage::exists($repair->evidence_perbaikan)) {
            \Storage::delete($repair->evidence_perbaikan);
        }

        // Store new file
        $validated['evidence_perbaikan'] = $request->file('evidence_perbaikan')
            ->store('evidence_perbaikan', 'public');
    }

    // ✅ 4. Update the record
    $repair->update($validated);

    // ✅ 5. Redirect back with success message
    return redirect()->back()->with('success', 'Data perbaikan berhasil diperbarui.');
}




    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();

        return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil dihapus.');
    }
}
