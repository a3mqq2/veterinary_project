<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Symptom;

class SymptomsSeeder extends Seeder
{
    public function run()
    {
        $clinical = [
            ['Late-term abortion', 'الإجهاض المتأخر'],
            ['Vaginal discharge', 'إفرازات مهبلية'],
            ['Ewes appear clinically healthy', 'النعاج تبدو بصحة جيدة'],
            ['Birth of weak lambs', 'ولادة حملان ضعيفة'],
            ['Stillbirths', 'ولادة أجنة ميتة'],
            ['Normal-looking lambs but weaken or die soon', 'حملان طبيعية الشكل لكنها تضعف أو تموت سريعًا'],
            ['Hairy shaker lambs', 'حملان مشعرة مرتجفة'],
            ['History of repeated in forward ram', 'تاريخ تكرار التزاوج غير الناجح'],
            ['Metritis and placentitis during pregnancy', 'التهاب الرحم والمشيمة خلال الحمل'],
            ['Lambs dull, weak, unable to nurse', 'حملان خاملة وضعيفة وغير قادرة على الرضاعة'],
            ['Lambs die shortly after birth', 'موت الحملان بعد الولادة مباشرة'],
            ['Encephalitis', 'التهاب الدماغ'],
            ['Barren at lambing time', 'عقم عند موعد الولادة'],
            ['Abnormal body fetus', 'جنين مشوه'],
            ['Thickened leather-like skin', 'جلد سميك شبيه بالجلد المدبوغ'],
            ['Shaker lambs', 'حملان مرتجفة'],
            ['Dwarfism', 'قزامة'],
            ['Mortality in flock', 'نفوق في القطيع'],
            ['Septicemia in ewes or lambs', 'تسمم دموي في النعاج أو الحملان'],
            ['Enteritis', 'التهاب الأمعاء'],
            ['Fever or transient fever', 'حمى أو حمى عابرة'],
            ['Anorexia', 'فقدان الشهية'],
            ['Diarrhea in ewes', 'إسهال عند النعاج'],
            ['Diarrhea in lambs with sunken eyes', 'إسهال عند الحملان مع عيون غائرة'],
            ['Tightened skin dehydration', 'جلد مشدود نتيجة الجفاف'],
            ['Sporadic infection', 'إصابة متفرقة'],
            ['Storm abortion', 'وباء إجهاض'],
            ['Lambs scouring', 'إسهال الحملان'],
            ['Ewe die before abortion', 'نفوق النعجة قبل الإجهاض'],
            ['Increasing infertility', 'زيادة معدل العقم'],
            ['Stillborn or weak lambs', 'حملان ميتة أو ضعيفة'],
            ['Off milk', 'انخفاض إنتاج الحليب'],
            ['Jaundice', 'يرقان'],
            ['Hematuria', 'بول دموي'],
            ['Death of newborn lambs', 'وفاة المواليد الجدد'],
            ['Swelling of nostrils lips eyes', 'تورم الأنف والشفاه والعينين'],
            ['Excess salivation', 'لعاب مفرط'],
            ['Nasal and ocular discharge', 'إفرازات أنفية وعينية'],
            ['Respiratory signs', 'علامات تنفسية'],
            ['Skin lesions', 'آفات جلدية'],
            ['Edema of head and neck', 'وذمة الرأس والرقبة'],
            ['Cyanotic tongue', 'لسان مزرق'],
            ['Lameness and recumbence', 'عرج واستلقاء'],
            ['Rapid loss of condition', 'فقدان سريع للحالة العامة'],
            ['Infertility', 'عقم'],
            ['Bloody vaginal discharge', 'إفرازات مهبلية دموية'],
            ['Retained placenta', 'احتباس المشيمة'],
            ['Dystocia', 'عسر الولادة'],
            ['Hypothermia', 'انخفاض حرارة الجسم'],
            ['Low viability', 'انخفاض الحيوية'],
            ['Generalized lethargy and weakness', 'خمول عام وضعف'],
            ['Tachycardia and tachypnea', 'تسارع ضربات القلب والتنفس'],
            ['Sudden death without signs', 'موت مفاجئ بدون أعراض'],
            ['Isolation from flock', 'انعزال عن القطيع'],
            ['Muscle tremors', 'ارتعاش العضلات'],
            ['Circling behavior', 'الدوران'],
            ['Ketotic breath odor', 'رائحة نفس كيتونية'],
        ];

        $pm = [
            ['Thickened intercotyledonary placenta', 'سماكة المساحة بين الفصوص في المشيمة'],
            ['Pus on placenta', 'صديد على المشيمة'],
            ['White pinhead foci on placenta', 'بؤر بيضاء على المشيمة'],
            ['Dirty pink exudate on placenta', 'إفرازات وردية متسخة على المشيمة'],
            ['Mummified fetus', 'جنين متحنط'],
            ['Corneal opacity in fetus', 'عتامة قرنية في الجنين'],
            ['Wool detachment', 'انفصال الصوف'],
            ['Congestion and enlarged fetal liver', 'احتقان وتضخم كبد الجنين'],
            ['Necrotic foci on fetal liver', 'بؤر نخرية على كبد الجنين'],
            ['Bright or dark cotyledons with foci', 'أجزاء لامعة أو داكنة في الفصوص مع بؤر بيضاء'],
            ['Autolytic placenta', 'تحلل ذاتي للمشيمة'],
            ['Swollen fetal membranes', 'انتفاخ الأغشية الجنينية'],
            ['Stomach contents show hyphae', 'وجود خيوط فطرية في محتويات المعدة'],
            ['Smoke ring in liver', 'حلقة دخانية على الكبد'],
            ['Bloody serous fluid in fetal cavities', 'سائل مصلي دموي في تجاويف الجنين'],
            ['Cotyledons detach easily', 'انفصال سهل للفصوص'],
            ['Thrombosis in placenta', 'جلطات دموية في المشيمة'],
            ['Grey-white necrosis in liver', 'نخر رمادي أبيض في الكبد'],
            ['Generalized congestion', 'احتقان عام'],
            ['Enlarged liver and spleen', 'تضخم الكبد والطحال'],
            ['Splenomegaly', 'تضخم الطحال'],
            ['Necrotic placental foci', 'بؤر نخرية على المشيمة'],
            ['Growth retardation', 'تأخر النمو'],
            ['Stillbirth', 'جنين ميت'],
            ['Small yellow necrotic foci in liver', 'بؤر نخرية صفراء صغيرة في الكبد'],
            ['Abomasal erosions', 'تآكل في الأمعاء الرابعة'],
            ['Yellow-orange meconium', 'عقي أصفر برتقالي'],
            ['Fetus edematous', 'جنين مصاب بالوذمة'],
            ['Endometritis', 'التهاب بطانة الرحم'],
            ['Lung poorly inflated', 'الرئة غير منتفخة جيدًا'],
            ['Thrombus umbilical artery end', 'جلطة في نهاية الشريان السري'],
            ['Severe congenital abnormality', 'تشوه خلقي شديد'],
            ['Empty GIT contents', 'قناة هضمية فارغة'],
            ['Meconium not expelled', 'عدم خروج العقي'],
            ['Brain cavity lesion swayback', 'تجويف دماغي (سواي باك)'],
            ['Brain damage', 'تلف دماغي'],
            ['White streak muscle WMD', 'خطوط بيضاء في العضلات (نقص السيلينيوم)'],
        ];

        $lab = [
            ['CBC leukocyte counts', 'تعداد الدم الكامل وخلايا الدم البيضاء'],
            ['Ketone bodies measurement', 'قياس الأجسام الكيتونية'],
            ['Hypoglycemia', 'انخفاض سكر الدم'],
            ['Blood acidosis tendency', 'ميل الدم للحموضة'],
        ];

        foreach ($clinical as $s) {
            Symptom::create([
                'name_en' => $s[0],
                'name_ar' => $s[1],
                'category' => 'clinical_signs',
                'is_active' => true,
            ]);
        }

        foreach ($pm as $s) {
            Symptom::create([
                'name_en' => $s[0],
                'name_ar' => $s[1],
                'category' => 'pm_lesions',
                'is_active' => true,
            ]);
        }

        foreach ($lab as $s) {
            Symptom::create([
                'name_en' => $s[0],
                'name_ar' => $s[1],
                'category' => 'lab_findings',
                'is_active' => true,
            ]);
        }
    }
}
