<?php

namespace App\Exports;

use App\Models\Backlog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BacklogExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Backlog::select(
            'code_number',
            'deskripsi',
            'created_at',
            'condition',
            'status'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Code Number',
            'Deskripsi',
            'Tanggal',
            'Condition',
            'Status'
        ];
    }
}
