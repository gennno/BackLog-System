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
            'id_inspeksi'   => 'required|string',
            'code_number'   => 'required|string',
            'hm'            => 'nullable|numeric',
            'component'     => 'required|string',
            'plan_repair'   => 'nullable|string',
            'status'        => 'required|string',
            'condition'     => 'nullable|string',
            'gl_pic'        => 'nullable|string',
            'pic_daily'     => 'nullable|string',
            'deskripsi'     => 'nullable|string',
            'evidence'      => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'part_number'   => 'nullable|string',
            'part_name'     => 'nullable|string',
            'no_figure'     => 'nullable|string',
            'qty'           => 'nullable|integer',
            'close_by'      => 'nullable|string',
            'id_action'     => 'nullable|string',
        ]);

        $data = $request->only([
            'tanggal_temuan', 'id_inspeksi', 'code_number', 'hm', 'component', 
            'plan_repair', 'status', 'condition', 'gl_pic', 'pic_daily', 
            'deskripsi', 'part_number', 'part_name', 'no_figure', 'qty', 
            'close_by', 'id_action'
        ]);

        // Handle evidence upload
        if ($request->hasFile('evidence')) {
            $file = $request->file('evidence');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destination = $_SERVER['DOCUMENT_ROOT'] . '/uploads/evidence';
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $data['evidence'] = 'uploads/evidence/' . $filename;
        }

        $data['made_by'] = Auth::id();

        Backlog::create($data);

        return redirect()->route('temuan')->with('success', 'Temuan berhasil ditambahkan.');
    }

public function update(Request $request, $id)
{
    // Validate only the fields that exist in your form
    $request->validate([
        'tanggal_temuan' => 'required|date',
        'code_number'   => 'required|string',
        'component'     => 'required|string',
        'status'        => 'required|string',
        'condition'     => 'nullable|string',
        'deskripsi'     => 'nullable|string',
    ]);

    // Find the record
    $temuan = Backlog::findOrFail($id);

    // Only take the fields present in your form
    $data = $request->only([
        'tanggal_temuan',
        'code_number',
        'component',
        'status',
        'condition',
        'deskripsi',
    ]);

    // Update the record
    $temuan->update($data);

    // Redirect with success message
    return redirect()->route('temuan')->with('success', 'Temuan berhasil diperbarui.');
}


    public function destroy($id)
    {
        $temuan = Backlog::findOrFail($id);

        // Delete evidence file if exists
        if ($temuan->evidence && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $temuan->evidence)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $temuan->evidence);
        }

        $temuan->delete();

        return redirect()->route('temuan')->with('success', 'Temuan berhasil dihapus.');
    }
}
