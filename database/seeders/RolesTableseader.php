<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RolesTableseader extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id' => 2, 'name' => 'Doctor'],
            ['id' => 5, 'name' => 'Patient']
        ];

        DB::table('roles')->insert($roles);
    }
}
