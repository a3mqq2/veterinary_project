<?php

namespace Database\Seeders;

use App\Models\Disease;
use App\Models\Symptom;
use Illuminate\Database\Seeder;

class DiseaseSeeder extends Seeder
{
    public function run(): void
    {
        $diseases = [
            [
                'name_en' => 'Brucellosis',
                'name_ar' => 'الحمى المالطية (البروسيلا)',
                'description_en' => 'A bacterial disease causing abortion in late pregnancy and infertility in sheep.',
                'description_ar' => 'مرض بكتيري يسبب الإجهاض في أواخر الحمل والعقم في الأغنام.',
            ],
            [
                'name_en' => 'Enterotoxemia',
                'name_ar' => 'التسمم المعوي',
                'description_en' => 'A fatal disease caused by Clostridium perfringens affecting lambs and adult sheep.',
                'description_ar' => 'مرض قاتل يسببه كلوستريديوم بيرفرنجنز يصيب الحملان والأغنام البالغة.',
            ],
            [
                'name_en' => 'Foot Rot',
                'name_ar' => 'تعفن القدم',
                'description_en' => 'A bacterial infection causing severe lameness and hoof damage.',
                'description_ar' => 'عدوى بكتيرية تسبب عرجًا شديدًا وتلفًا في الحوافر.',
            ],
            [
                'name_en' => 'Pneumonia',
                'name_ar' => 'الالتهاب الرئوي',
                'description_en' => 'Respiratory infection causing breathing difficulties and death in severe cases.',
                'description_ar' => 'عدوى تنفسية تسبب صعوبة في التنفس والموت في الحالات الشديدة.',
            ],
            [
                'name_en' => 'Mastitis',
                'name_ar' => 'التهاب الضرع',
                'description_en' => 'Inflammation of the udder affecting milk production and quality.',
                'description_ar' => 'التهاب الضرع يؤثر على إنتاج الحليب وجودته.',
            ],
            [
                'name_en' => 'Pregnancy Toxemia',
                'name_ar' => 'تسمم الحمل',
                'description_en' => 'A metabolic disease affecting ewes in late pregnancy carrying multiple lambs.',
                'description_ar' => 'مرض أيضي يصيب النعاج في أواخر الحمل الحاملة لعدة حملان.',
            ],
            [
                'name_en' => 'Orf (Contagious Ecthyma)',
                'name_ar' => 'الإكثيما المعدية (الأورف)',
                'description_en' => 'A viral disease causing scabby lesions around mouth and nose.',
                'description_ar' => 'مرض فيروسي يسبب آفات قشرية حول الفم والأنف.',
            ],
            [
                'name_en' => 'Internal Parasites',
                'name_ar' => 'الطفيليات الداخلية',
                'description_en' => 'Gastrointestinal worm infections causing weight loss and anemia.',
                'description_ar' => 'عدوى الديدان المعوية تسبب فقدان الوزن وفقر الدم.',
            ],
            [
                'name_en' => 'External Parasites (Mange)',
                'name_ar' => 'الطفيليات الخارجية (الجرب)',
                'description_en' => 'Skin infestation by mites causing intense itching and wool loss.',
                'description_ar' => 'إصابة جلدية بالعث تسبب حكة شديدة وفقدان الصوف.',
            ],
            [
                'name_en' => 'Listeriosis',
                'name_ar' => 'الليستريا',
                'description_en' => 'A bacterial disease causing circling, head tilt and nervous signs.',
                'description_ar' => 'مرض بكتيري يسبب الدوران وميل الرأس وعلامات عصبية.',
            ],
            [
                'name_en' => 'White Muscle Disease',
                'name_ar' => 'مرض العضلات البيضاء',
                'description_en' => 'A nutritional disease caused by selenium/vitamin E deficiency in lambs.',
                'description_ar' => 'مرض غذائي ناتج عن نقص السيلينيوم/فيتامين E في الحملان.',
            ],
            [
                'name_en' => 'Caseous Lymphadenitis',
                'name_ar' => 'التهاب الغدد الليمفاوية الجبني',
                'description_en' => 'A chronic bacterial disease causing abscesses in lymph nodes.',
                'description_ar' => 'مرض بكتيري مزمن يسبب خراجات في الغدد الليمفاوية.',
            ],
            [
                'name_en' => 'Bluetongue',
                'name_ar' => 'اللسان الأزرق',
                'description_en' => 'A viral disease transmitted by midges causing fever and oral lesions.',
                'description_ar' => 'مرض فيروسي ينتقل عن طريق الحشرات يسبب الحمى وآفات الفم.',
            ],
            [
                'name_en' => 'Footand Mouth Disease',
                'name_ar' => 'الحمى القلاعية',
                'description_en' => 'A highly contagious viral disease causing blisters on feet and mouth.',
                'description_ar' => 'مرض فيروسي شديد العدوى يسبب بثورًا على القدمين والفم.',
            ],
            [
                'name_en' => 'Johnes Disease',
                'name_ar' => 'مرض جونز',
                'description_en' => 'A chronic intestinal infection causing progressive weight loss.',
                'description_ar' => 'عدوى معوية مزمنة تسبب فقدان الوزن التدريجي.',
            ],
        ];

        // Get all symptoms
        $symptoms = Symptom::all();

        foreach ($diseases as $diseaseData) {
            $disease = Disease::create([
                'name_en' => $diseaseData['name_en'],
                'name_ar' => $diseaseData['name_ar'],
                'description_en' => $diseaseData['description_en'],
                'description_ar' => $diseaseData['description_ar'],
                'is_active' => true,
            ]);

            // Attach 3-8 random symptoms to each disease
            $randomSymptoms = $symptoms->random(rand(3, min(8, $symptoms->count())));
            $disease->symptoms()->attach($randomSymptoms->pluck('id'));
        }
    }
}
