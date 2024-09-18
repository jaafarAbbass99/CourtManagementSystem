<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseJudge extends Model
{
    use HasFactory;

    protected $fillable = [ 'case_id', 'judge_section_id', 'status', 'base_number', 'full_number', 'is_seen', 'date_close_case'];
     // العلاقة مع الدعوى 
     public function case() { return $this->belongsTo(Cases::class); } 
     // العلاقة مع القسم القضائي 
     public function judgeSection() { return $this->belongsTo(JudgeSection::class,'judge_section_id'); } 
     
     public static function boot(){ 
        parent::boot(); 
        static::creating(function($model){ 
            $max = CaseJudge::max('base_number');

            $model->base_number = $model->id == 1 ? 56 : $max +=3 ; 
            $year = date('y',$model->create_at); 
            $model->full_number = $model->case->full_number . "-" . $year . "-" .str_pad($model->base_number , 6 , 0 ,STR_PAD_LEFT); }); 
        }
}
