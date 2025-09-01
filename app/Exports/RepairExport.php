<?php

namespace App\Exports;

use App\Models\Repair;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RepairExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Repair::select(
            'id_backlog',
            'kode_unit',
            'tanggal',
            'hm',
            'component',
            'evidence_temuan',
            'pic_daily',
            'gl_pic',
            'evidence_perbaikan',
            'pic',
            'status',
            'nama_pembuat',
            'deskripsi'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID Backlog',
            'Kode Unit',
            'Tanggal',
            'HM',
            'Component',
            'Evidence Temuan',
            'PIC Daily',
            'GL PIC',
            'Evidence Perbaikan',
            'PIC',
            'Status',
            'Nama Pembuat',
            'Deskripsi',
        ];
    }
}
