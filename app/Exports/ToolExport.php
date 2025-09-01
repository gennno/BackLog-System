<?php

namespace App\Exports;

use App\Models\Tool;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ToolExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Tool::select(
            'lokasi',
            'nama_tool',
            'foto',
            'deskripsi',
            'status'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Lokasi',
            'Nama Tool',
            'Foto',
            'Deskripsi',
            'Status',
        ];
    }
}
