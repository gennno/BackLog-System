<?php

namespace App\Http\Controllers;

use App\Exports\RepairExport;
use App\Models\Repair;
use App\Models\Backlog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

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
    $file = $request->file('evidence_temuan');
    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $destination = $_SERVER['DOCUMENT_ROOT'] . '/uploads/evidences';
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }

    $file->move($destination, $filename);
    $data['evidence_temuan'] = 'uploads/evidences/' . $filename;
}

if ($request->hasFile('evidence_perbaikan')) {
    $file = $request->file('evidence_perbaikan');
    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $destination = $_SERVER['DOCUMENT_ROOT'] . '/uploads/evidence_perbaikan';
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }

    $file->move($destination, $filename);
    $data['evidence_perbaikan'] = 'uploads/evidence_perbaikan/' . $filename;
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

    $repair = Repair::findOrFail($id);

if ($request->hasFile('evidence_perbaikan')) {
    // delete old file if exists
    if ($repair->evidence_perbaikan && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $repair->evidence_perbaikan)) {
        unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $repair->evidence_perbaikan);
    }

    // store new file
    $file = $request->file('evidence_perbaikan');
    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $destination = $_SERVER['DOCUMENT_ROOT'] . '/uploads/evidence_perbaikan';
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }

    $file->move($destination, $filename);
    $validated['evidence_perbaikan'] = 'uploads/evidence_perbaikan/' . $filename;
}


    $repair->update($validated);

    return redirect()->back()->with('success', 'Data perbaikan berhasil diperbarui.');
}


    public function export()
    {
        return Excel::download(new RepairExport, 'repair.xlsx');
    }


    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();

        return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil dihapus.');
    }
}
