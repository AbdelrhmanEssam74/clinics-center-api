<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorsTableSeeder extends Seeder
{
    public function run()
    {
        $doctors = [
            [
                'id' => 1,
                'user_id' => 1,
                'specialty_id' => 1,
                'experience_years' => 10,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'specialty_id' => 2,
                'experience_years' => 8,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'specialty_id' => 3,
                'experience_years' => 10,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],
            [
                'id' => 4,
                'user_id' => 4,
                'specialty_id' => 4,
                'experience_years' => 2,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],
            [
                'id' => 5,
                'user_id' => 5,
                'specialty_id' => 5,
                'experience_years' => 4,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],
            [
                'id' => 6,
                'user_id' => 6,
                'specialty_id' => 6,
                'experience_years' => 9,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],
            [
                'id' => 7,
                'user_id' => 7,
                'specialty_id' => 7,
                'experience_years' => 4,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],
            [
                'id' => 8,
                'user_id' => 8,
                'specialty_id' => 8,
                'experience_years' => 9,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],
            [
                'id' => 9,
                'user_id' => 9,
                'specialty_id' => 9,
                'experience_yearse' => 5,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],
            [
                'id' => 10,
                'user_id' => 10,
                'specialty_id' => 10,
                'experience_years' => 20,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],

        ];

        DB::table('doctors')->insert($doctors);
    }
}