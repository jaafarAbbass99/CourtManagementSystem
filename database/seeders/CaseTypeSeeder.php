<?php

namespace Database\Seeders;

use App\Models\CaseType;
use App\Models\DisputeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CaseTypeSeeder extends Seeder
{

    public function run(): void
    {
        $disputeTypes = DisputeType::all();

        foreach ($disputeTypes as $disputeType) {
            switch ($disputeType->name) {
                case 'عقدية':
                    CaseType::create(['name' => 'دعوى فسخ','short_form' => 'DF', 'dispute_type_id' => $disputeType->id]);
                    CaseType::create(['name' => 'دعوى تعويض','short_form' => 'DT', 'dispute_type_id' => $disputeType->id]);
                    CaseType::create(['name' => 'دعوى ابطال عقد','short_form' => 'DAC', 'dispute_type_id' => $disputeType->id]);
                    CaseType::create(['name' => 'دعوى استرداد','short_form' => 'DR', 'dispute_type_id' => $disputeType->id]);
                    break;
                case 'أسرية':
                    CaseType::create(['name' => 'دعوى طلاق','short_form' => 'DTQ', 'dispute_type_id' => $disputeType->id]);
                    CaseType::create(['name' => 'دعوى نفقة','short_form' => 'DN', 'dispute_type_id' => $disputeType->id]);
                    CaseType::create(['name' => 'دعوى حضانة','short_form' => 'DH', 'dispute_type_id' => $disputeType->id]);
                    CaseType::create(['name' => 'دعوى اثبات النسب','short_form' => 'DEN', 'dispute_type_id' => $disputeType->id]);
                    CaseType::create(['name' => 'دعوى خلع', 'short_form' => 'DK','dispute_type_id' => $disputeType->id]);
                    break;
                case 'عقارية':
                    CaseType::create(['name' => 'دعوى تثبيت ملكية','short_form' => 'DTM', 'dispute_type_id' => $disputeType->id]);
                    CaseType::create(['name' => 'دعوى تقسيم', 'short_form' => 'DTQSM', 'dispute_type_id' => $disputeType->id]);
                    CaseType::create(['name' => 'دعوى حيازة','short_form' => 'DHZ', 'dispute_type_id' => $disputeType->id]);
                    CaseType::create(['name' => 'دعوى اخلاء', 'short_form' => 'DE', 'dispute_type_id' => $disputeType->id]);
                    CaseType::create(['name' => 'دعوى تصحيح حدود العقار', 'short_form' => 'DTH', 'dispute_type_id' => $disputeType->id]);
                    break;
                case 'تجارية':
                    // أضف الأنواع التجارية هنا إذا لزم الأمر
                    break;
            }
        }
    }
}
