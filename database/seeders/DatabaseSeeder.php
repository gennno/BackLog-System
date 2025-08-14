<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            UnitsTableSeeder::class,
            ToolsTableSeeder::class,
            BacklogTableSeeder::class,
            RepairTableSeeder::class,
        ]);
    }
}
