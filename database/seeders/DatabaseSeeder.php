<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Specialty;
use App\Models\User;
use Doctrine\Inflector\Rules\Pattern;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            // RolesTableseader::class,
            // UsersTableSeeder::class,
            // UsersPatientTableSeeder::class,
            // PatientsTableSeeder::class,
            // DoctorsTableSeeder::class,
            // SpecialtiesTableSeeder::class,
        ]);
    }
}
