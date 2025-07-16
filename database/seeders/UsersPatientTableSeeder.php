<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersPatientTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id' => 11,
                'name' => 'Ahmed Mohamed',
                'email' => 'ahmed12@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966502345679',
                'image' => 'storage/users/patient2.jpg',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 5,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],
            [
                'id' => 12,
                'name' => 'nada',
                'email' => 'nada@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966502345679',
                'image' => 'storage/users/patient1.jpg',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 5,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],
            [
                'id' => 13,
                'name' => 'mohamed',
                'email' => 'mohamed@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966502345679',
                'image' => 'storage/users/patient4.jpg',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 5,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],
            [
                'id' => 14,
                'name' => 'ahmed',
                'email' => 'ahmed@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966502345679',
                'image' => 'storage/users/patient2.jpg',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 5,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => '2023-01-02 09:00:00',
            ],
        ];

        DB::table('users')->insert($users);
    }
}
