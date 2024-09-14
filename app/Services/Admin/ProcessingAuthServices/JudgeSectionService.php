<?php 

namespace App\Services\Admin\ProcessingAuthServices;

use App\Models\JudgeSection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JudgeSectionService
{

    public function addJudgeToSection(array $data)
    {
        $exists = JudgeSection::where('user_id', $data['user_id'])
            ->where('section_id', $data['section_id'])
            ->where('court_type_id', $data['court_type_id'])
            ->where('role',$data['role'])
            ->exists();

        if ($exists) {
            throw new \Exception('القاضي موجود بالفعل في هذا القسم والمحكمة');
        }

        // إضافة القاضي إلى القسم
        JudgeSection::create($data);
    }

    // جلب جميع القضاة في قسم محدد
    public function getJudgesBySection(int $sectionId)
    {
        return JudgeSection::with(['judge', 'court','section'])
            ->where('section_id', $sectionId)
            ->get();
    }

    public function deleteJudgeFromSection(JudgeSection $judgeSection)
    {        
        $judgeSection->delete();
    }

}