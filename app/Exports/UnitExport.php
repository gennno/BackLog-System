<?php

namespace App\Exports;

use App\Models\Unit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UnitExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Unit::select(
            'kode_unit',
            'nama_unit',
            'foto',
            'status',
            'baterai'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Kode Unit',
            'Nama Unit',
            'Foto',
            'Status',
            'Baterai',
        ];
    }
}
