<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JudgeSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'section_id', 'court_type_id', 'role',
    ];

    public function cases(){
        return $this->belongsToMany(Cases::class , 'case_judges','judge_section_id','case_id')
        ->withPivot('id','status','is_seen', 'date_close_case','full_number') // الوصول إلى الأعمدة الإضافية في الجدول الوسيط
        ->withTimestamps();
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function courtType()
    {
        return $this->belongsTo(CourtType::class,'court_type_id');
    }

    public function judge()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
}
