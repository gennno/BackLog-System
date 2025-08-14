<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('units')->insert([
            [
                'kode_unit' => 'EX200-01',
                'nama_unit' => 'Excavator Hitachi EX200',
                'foto'      => null,
                'status'    => 'aktif',
                'baterai'   => '80%',
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'kode_unit' => 'DT785-02',
                'nama_unit' => 'Dump Truck Komatsu HD785',
                'foto'      => null,
                'status'    => 'aktif',
                'baterai'   => '90%',
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'kode_unit' => 'WL950-01',
                'nama_unit' => 'Wheel Loader Caterpillar 950',
                'foto'      => null,
                'status'    => 'off',
                'baterai'   => 'Low',
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
        ]);
    }
}
