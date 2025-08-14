<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToolsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('tools')->insert([
            [
                'kode_tool' => 'TOOL-001',
                'nama_tool' => 'Kunci Inggris',
                'foto'      => null,
                'deskripsi' => 'Berfungsi dengan baik',
                'status'    => 'baik',
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'kode_tool' => 'TOOL-002',
                'nama_tool' => 'Obeng Plus',
                'foto'      => null,
                'deskripsi' => 'Kepala sedikit aus, masih bisa digunakan',
                'status'    => 'baik',
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'kode_tool' => 'TOOL-003',
                'nama_tool' => 'Tang Potong',
                'foto'      => null,
                'deskripsi' => 'Pegangan retak, perlu diganti',
                'status'    => 'rusak',
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
        ]);
    }
}
