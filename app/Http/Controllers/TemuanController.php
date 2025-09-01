<?php

namespace App\Http\Controllers;

use App\Models\Backlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemuanController extends Controller
{
    public function index()
    {
        $temuan = Backlog::with('creator')->latest()->get();
        return view('temuan', compact('temuan'));
    }

public function store(Request $request)
{
    $request->validate([
        'tanggal_temuan' => 'required|date',
        'code_number'    => 'required|string',
        'component'      => 'required|string',
        'status'         => 'required|string',
        'deskripsi'      => 'nullable|string',
        'evidence'       => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:2048', // add validation for photo
    ]);

    $evidencePath = null;

    if ($request->hasFile('evidence')) {
        $file = $request->file('evidence');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $destination = $_SERVER['DOCUMENT_ROOT'] . '/uploads/evidence';
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $file->move($destination, $filename);
        $evidencePath = 'uploads/evidence/' . $filename; // relative path
    }

    Backlog::create([
        'tanggal_temuan' => $request->tanggal_temuan,
        'id_inspeksi'    => $request->id_inspeksi,
        'code_number'    => $request->code_number,
        'hm'             => $request->hm,
        'component'      => $request->component,
        'plan_repair'    => $request->plan_repair,
        'status'         => $request->status,
        'condition'      => $request->condition,
        'gl_pic'         => $request->gl_pic,
        'pic_daily'      => $request->pic_daily,
        'evidence'       => $evidencePath,
        'deskripsi'      => $request->deskripsi,
        'part_number'    => $request->part_number,
        'part_name'      => $request->part_name,
        'no_figure'      => $request->no_figure,
        'qty'            => $request->qty,
        'close_by'       => $request->close_by,
        'id_action'      => $request->id_action,
        'made_by'        => Auth::id(),
    ]);

    return redirect()->route('temuan')->with('success', 'Temuan berhasil ditambahkan.');
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_temuan' => 'required|date',
            'code_number'    => 'required|string',
            'component'      => 'required|string',
            'status'         => 'required|string',
        ]);

        $temuan = Backlog::findOrFail($id);
        $temuan->update($request->all());

        return redirect()->route('temuan')->with('success', 'Temuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $temuan = Backlog::findOrFail($id);
        $temuan->delete();

        return redirect()->route('temuan')->with('success', 'Temuan berhasil dihapus.');
    }
}
