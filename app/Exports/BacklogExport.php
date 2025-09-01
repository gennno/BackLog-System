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
            'tanggal_temuan',
            'id_inspeksi',
            'code_number',
            'hm',
            'component',
            'plan_repair',
            'status',
            'condition',
            'gl_pic',
            'pic_daily',
            'evidence',
            'deskripsi',
            'part_number',
            'part_name',
            'no_figure',
            'qty',
            'close_by'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal Temuan',
            'ID Inspeksi',
            'Code Number',
            'HM',
            'Component',
            'Plan Repair',
            'Status',
            'Condition',
            'GL PIC',
            'PIC Daily',
            'Evidence',
            'Deskripsi',
            'Part Number',
            'Part Name',
            'No Figure',
            'Qty',
            'Close By'
        ];
    }
}
