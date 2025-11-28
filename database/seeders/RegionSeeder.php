<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['name' => 'طرابلس', 'name_en' => 'Tripoli', 'description' => 'العاصمة طرابلس'],
            ['name' => 'بنغازي', 'name_en' => 'Benghazi', 'description' => 'مدينة بنغازي'],
            ['name' => 'مصراتة', 'name_en' => 'Misrata', 'description' => 'مدينة مصراتة'],
            ['name' => 'الزاوية', 'name_en' => 'Zawiya', 'description' => 'مدينة الزاوية'],
            ['name' => 'زليتن', 'name_en' => 'Zliten', 'description' => 'مدينة زليتن'],
            ['name' => 'البيضاء', 'name_en' => 'Al Bayda', 'description' => 'مدينة البيضاء - الجبل الأخضر'],
            ['name' => 'طبرق', 'name_en' => 'Tobruk', 'description' => 'مدينة طبرق'],
            ['name' => 'سبها', 'name_en' => 'Sabha', 'description' => 'مدينة سبها - الجنوب'],
            ['name' => 'درنة', 'name_en' => 'Derna', 'description' => 'مدينة درنة'],
            ['name' => 'سرت', 'name_en' => 'Sirte', 'description' => 'مدينة سرت'],
            ['name' => 'غريان', 'name_en' => 'Gharyan', 'description' => 'مدينة غريان - الجبل الغربي'],
            ['name' => 'الخمس', 'name_en' => 'Khoms', 'description' => 'مدينة الخمس'],
            ['name' => 'ترهونة', 'name_en' => 'Tarhuna', 'description' => 'مدينة ترهونة'],
            ['name' => 'المرج', 'name_en' => 'Al Marj', 'description' => 'مدينة المرج'],
            ['name' => 'أجدابيا', 'name_en' => 'Ajdabiya', 'description' => 'مدينة أجدابيا'],
        ];

        foreach ($regions as $region) {
            Region::create([
                'name' => $region['name'],
                'name_en' => $region['name_en'],
                'description' => $region['description'],
                'is_active' => true,
            ]);
        }
    }
}
