<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SpecialtiesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('specialties')->insert([
            [
                'name' => 'Orthopedics',
                'description' => 'Orthopedics focuses on diagnosing, treating, and preventing musculoskeletal disorders, including bones, joints, muscles, and ligaments.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Dermatology specializes in treating skin, hair, and nail conditions, including acne, eczema, and skin cancer.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Endocrinology',
                'description' => 'Endocrinology is concerned with the diagnosis and treatment of hormone-related disorders such as diabetes, thyroid conditions, and metabolic diseases.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Gynecology',
                'description' => 'Gynecology focuses on the health of the female reproductive system, including pregnancy, menstrual disorders, and menopause management.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Psychiatry',
                'description' => 'Psychiatry involves the treatment of mental health disorders such as depression, anxiety, schizophrenia, and bipolar disorder.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Oncology',
                'description' => 'Oncology specializes in diagnosing and treating cancers, including chemotherapy, radiation, and surgical options.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Ophthalmology',
                'description' => 'Ophthalmology focuses on the diagnosis and treatment of eye diseases, including vision correction, cataract surgery, and eye trauma.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Urology',
                'description' => 'Urology specializes in the treatment of urinary tract issues and male reproductive health, including kidney stones, infections, and prostate problems.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Rheumatology',
                'description' => 'Rheumatology deals with the diagnosis and treatment of autoimmune diseases and musculoskeletal disorders, such as rheumatoid arthritis and lupus.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Hematology',
                'description' => 'Hematology focuses on blood disorders, including anemia, clotting problems, leukemia, and other blood cancers.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Gastroenterology',
                'description' => 'Gastroenterology deals with the digestive system, diagnosing and treating issues like ulcers, IBS, liver disease, and colorectal cancer.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
