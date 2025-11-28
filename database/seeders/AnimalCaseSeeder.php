<?php

namespace Database\Seeders;

use App\Models\AnimalCase;
use App\Models\Region;
use App\Models\Symptom;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnimalCaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $regions = Region::all();
        $symptoms = Symptom::all();

        // Owner names (Libyan names)
        $ownerNames = [
            'عبدالله محمد الطرابلسي',
            'سالم أحمد البنغازي',
            'محمد علي المصراتي',
            'خالد عمر الزاوي',
            'أحمد سليمان الفيتوري',
            'مصطفى عبدالسلام الورفلي',
            'عمر حسين الجهمي',
            'إبراهيم محمد الككلي',
            'يوسف عبدالله الشريف',
            'علي سالم المقريف',
            'حسن محمد الغرياني',
            'فتحي عبدالرحمن الدرناوي',
            'عادل محمود السبهاوي',
            'ناصر علي الطبراقي',
            'رمضان أحمد الزنتاني',
        ];

        // Farm locations (Libyan locations)
        $farmLocations = [
            'جنوب طرابلس - طريق السواني',
            'شرق بنغازي - منطقة قمينس',
            'غرب مصراتة - طريق زليتن',
            'جنوب الزاوية - منطقة صرمان',
            'شمال غريان - طريق الأصابعة',
            'شرق سبها - منطقة القرضة',
            'جنوب سرت - طريق الجفرة',
            'غرب البيضاء - شحات',
            'شمال ترهونة - وادي الربيع',
            'جنوب الخمس - لبدة',
        ];

        // Feed types
        $feedTypes = [
            'شعير + برسيم',
            'علف مركب + تبن',
            'برسيم أخضر + حبوب',
            'علف جاف + فيتامينات',
            'شعير + نخالة قمح',
            'علف مخلوط + معادن',
        ];

        // Vaccination history samples
        $vaccinations = [
            'تم التطعيم ضد الحمى القلاعية - يناير 2024',
            'تطعيم ضد التسمم المعوي - فبراير 2024',
            'تطعيم دوري كامل - مارس 2024',
            'لم يتم التطعيم هذا العام',
            'تطعيم ضد البروسيلا - ديسمبر 2023',
            'برنامج تطعيم شامل - كل 6 أشهر',
        ];

        // Notes samples
        $notes = [
            'الحالة تحتاج متابعة دورية',
            'تم بدء العلاج - يرجى المتابعة بعد أسبوع',
            'حالة مستقرة حالياً',
            'يُنصح بعزل الحيوانات المصابة',
            'تحسن ملحوظ بعد العلاج',
            'يحتاج فحص مخبري إضافي',
            'تم الشفاء - للمتابعة فقط',
        ];

        $statuses = ['open', 'in_progress', 'closed'];
        $farmTypes = ['extensive', 'semi_intensive', 'intensive'];
        $breeds = ['local', 'imported', 'mixed'];

        // Create 75 random cases
        for ($i = 0; $i < 75; $i++) {
            $createdAt = now()->subDays(rand(0, 180));
            $case = AnimalCase::create([
                'case_number' => 'CASE-' . $createdAt->format('Y') . '-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'owner_name' => $ownerNames[array_rand($ownerNames)],
                'owner_phone' => '05' . rand(10000000, 99999999),
                'farm_location' => $farmLocations[array_rand($farmLocations)],
                'region_id' => $regions->random()->id,
                'farm_type' => $farmTypes[array_rand($farmTypes)],
                'flock_size' => rand(20, 500),
                'other_animals' => rand(0, 1) ? 'ماعز - دجاج' : null,
                'age_years' => rand(0, 5),
                'age_months' => rand(0, 11),
                'breed' => $breeds[array_rand($breeds)],
                'milking_ewes' => rand(5, 50),
                'dry_ewes' => rand(5, 30),
                'milking_feed_type' => $feedTypes[array_rand($feedTypes)],
                'milking_daily_consumption' => rand(1, 3) . ' كجم',
                'milking_feeding_schedule' => 'مرتين يومياً - صباحاً ومساءً',
                'milking_mineral_vitamin' => (bool) rand(0, 1),
                'dry_ewes_nutrition' => rand(0, 1) ? 'علف جاف مع تبن' : null,
                'lambs_health_problems' => rand(0, 1) ? 'إسهال خفيف في الأسابيع الأولى' : null,
                'vaccination_history' => $vaccinations[array_rand($vaccinations)],
                'medication_programs' => rand(0, 1) ? 'برنامج تطهير داخلي كل 3 أشهر' : null,
                'notes' => rand(0, 1) ? $notes[array_rand($notes)] : null,
                'status' => $statuses[array_rand($statuses)],
                'user_id' => $users->random()->id,
                'created_at' => $createdAt,
            ]);

            // Attach 2-8 random symptoms to each case
            $randomSymptoms = $symptoms->random(rand(2, min(8, $symptoms->count())));
            $case->symptoms()->attach($randomSymptoms->pluck('id'));
        }
    }
}
