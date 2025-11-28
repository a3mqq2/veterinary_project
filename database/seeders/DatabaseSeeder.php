<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Users first (no dependencies)
            UserSeeder::class,

            // Symptoms (no dependencies)
            SymptomsSeeder::class,

            // Regions (no dependencies)
            RegionSeeder::class,

            // Diseases (depends on symptoms)
            DiseaseSeeder::class,

            // Cases (depends on users, regions, symptoms)
            AnimalCaseSeeder::class,
        ]);
    }
}
