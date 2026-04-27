<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Registration;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create administrator registration profile
        Registration::firstOrCreate(
            ['email' => 'peterkakooza968@gmail.com'],
            [
                'full_name' => 'kakooza peter',
                'email' => 'peterkakooza968@gmail.com',
                'phone' => '0709061019',
                'gender' => 'Male',
                'address' => 'nakawa/mbuya',
                'category' => 'Student',
                'year_of_study' => '3',
                'program_name' => 'Bachelor of Business Computing',
                'program_category' => 'Bachelors',
                'year_of_entry' => '2023/2024',
                'division_of_study' => 'Lesson Study',
                'family' => 'Jericho',
                'renting_area' => 'Mbuya',
                'hostel_name' => null,
            ]
        );
    }
}
