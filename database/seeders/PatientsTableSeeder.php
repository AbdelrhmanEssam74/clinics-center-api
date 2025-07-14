<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientsTableSeeder extends Seeder
{
    public function run()
    {
        $patients = [
            [
                'id' => 1,
                'user_id' => 11,
                'medical_record_number' => 'MRN2S0230001',
                'date_of_birth' => '1985-05-15',
                'gender' => 'Female',
                'address' => '123 King Fahd Rd, Riyadh',
                'phone' => '966504567890',
                'created_at' => '2023-02-01 10:30:00',
                'updated_at' => '2023-02-01 10:30:00',
            ],
            [
                'id' => 2,
                'user_id' => 12,
                'medical_record_number' => 'MRNS20230002',
                'date_of_birth' => '1990-08-22',
                'gender' => 'male',
                'address' => '456 Olaya St, Riyadh',
                'phone' => '966505678901',
                'created_at' => '2023-02-02 11:45:00',
                'updated_at' => '2023-02-02 11:45:00',
            ],
            [
                'id' => 3,
                'user_id' => 13,
                'medical_record_number' => 'MRSN20230002',
                'date_of_birth' => '1990-08-22',
                'gender' => 'male',
                'address' => '456 Olaya St, Riyadh',
                'phone' => '966505678901',
                'created_at' => '2023-02-02 11:45:00',
                'updated_at' => '2023-02-02 11:45:00',
            ],
        ];

        DB::table('patients')->insert($patients);
    }
}