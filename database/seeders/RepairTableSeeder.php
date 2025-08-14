<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RepairTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('repairs')->insert([
            [
                'id_backlog'          => 1, // contoh relasi ke backlog id=1
                'kode_unit'           => 'EX200-01',
                'tanggal'             => '2025-08-06 09:30:00',
                'hm'                  => 12345,
                'component'           => 'Engine Cooling Fan',
                'evidence_temuan'     => null,
                'pic_daily'           => 'Andi Wijaya',
                'gl_pic'              => 'Budi Santoso',
                'evidence_perbaikan'  => null,
                'pic'                 => 'Rahmat Hidayat',
                'status'              => 'closed',
                'nama_pembuat'        => 'Supervisor Maintenance',
                'deskripsi'           => 'Fan sudah dibersihkan dan diberi pelumas, suara berisik hilang.',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id_backlog'          => 2, // contoh relasi ke backlog id=2
                'kode_unit'           => 'EX200-02',
                'tanggal'             => '2025-08-08 14:15:00',
                'hm'                  => 54321,
                'component'           => 'Hydraulic Pump',
                'evidence_temuan'     => null,
                'pic_daily'           => 'Joko Saputra',
                'gl_pic'              => 'Siti Rahma',
                'evidence_perbaikan'  => null,
                'pic'                 => 'Ahmad Fauzi',
                'status'              => 'open',
                'nama_pembuat'        => 'Staff Maintenance',
                'deskripsi'           => 'Menunggu kedatangan spare part untuk seal hidrolik.',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
        ]);
    }
}
