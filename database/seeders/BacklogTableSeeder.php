<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BacklogTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('backlog')->insert([
            [
                'tanggal_temuan' => '2025-08-01',
                'id_inspeksi'    => 'INSP-2025-001',
                'code_number'    => 'CN-001',
                'hm'             => 12345,
                'component'      => 'Engine Cooling Fan',
                'plan_repair'    => 'Next PS',
                'status'         => 'Open BL',
                'condition'      => 'Caution',
                'gl_pic'         => 'Budi Santoso',
                'pic_daily'      => 'Andi Wijaya',
                'evidence'       => null,
                'deskripsi'      => 'Fan menghasilkan suara berisik, perlu dicek dan pelumasan ulang.',
                'part_number'    => 'PN-12345',
                'part_name'      => 'Cooling Fan',
                'no_figure'      => 'NF-001',
                'qty'            => 1,
                'close_by'       => null,
                'id_action'      => null,
                'made_by'        => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'tanggal_temuan' => '2025-08-05',
                'id_inspeksi'    => 'INSP-2025-002',
                'code_number'    => 'CN-002',
                'hm'             => 54321,
                'component'      => 'Hydraulic Pump',
                'plan_repair'    => 'No Repair',
                'status'         => 'Close',
                'condition'      => 'Urgent',
                'gl_pic'         => 'Siti Rahma',
                'pic_daily'      => 'Joko Saputra',
                'evidence'       => null,
                'deskripsi'      => 'Kebocoran pada seal pompa hidrolik, sudah diganti.',
                'part_number'    => 'PN-98765',
                'part_name'      => 'Hydraulic Seal Kit',
                'no_figure'      => 'NF-002',
                'qty'            => 2,
                'close_by'       => 'Supervisor Maintenance',
                'id_action'      => 1,
                'made_by'        => 2,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
