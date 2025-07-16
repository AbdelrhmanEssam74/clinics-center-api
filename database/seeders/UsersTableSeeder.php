<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Dr. Nada Smith',
                'email' => 'nadas@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966501234567',
                'image' => 'storage/doctors/doctor1.jpg',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 2,
                'created_at' => '2023-01-01 08:00:00',
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Dr. Emily Johnson',
                'email' => 'emily.johnson@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966502345678',
                'image' => 'storage/doctors/doctor2.jpg',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 2,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Dr. Michael Brown',
                'email' => 'michael.brown@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966502345478',
                'image' => 'storage/doctors/doctor3.webp',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 2,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Dr. Sarah Davis',
                'email' => 'Sarah.Davis@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966502365679',
                'image' => 'storage/doctors/doctor4.webp',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 2,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Dr. William Wilson',
                'email' => 'William@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966502345679',
                'image' => 'storage/doctors/doctor5.webp',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 2,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Dr. Linda Martinez',
                'email' => 'Linda@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966502345679',
                'image' => 'storage/doctors/doctor6.webp',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 2,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'name' => 'Dr. James Anderson',
                'email' => 'James@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966502345679',
                'image' => 'storage/doctors/doctor7.webp',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 2,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'name' => 'Dr. Barbara Thomas',
                'email' => 'Barbara@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966502345679',
                'image' => 'storage/doctors/doctor8.webp',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 2,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'name' => 'Dr. Christopher Taylor',
                'email' => 'Christopher@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966502345679',
                'image' => 'storage/doctors/doctor9.jpg',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 2,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'name' => 'Dr. Patricia Moore',
                'email' => 'Patricia@hospital.com',
                'password' => Hash::make('password'),
                'phone' => '966502345679',
                'image' => 'storage/doctors/doctor10.webp',
                'profile_description' => 'Experienced cardiologist with over 10 years in patient care.',
                'role_id' => 2,
                'created_at' => '2023-01-02 09:00:00',
                'updated_at' => now(),
            ],
        ];
        DB::table('users')->insert($users);
    }
}