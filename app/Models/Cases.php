<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_number',
        'number',
        'full_number',
        'party_one',
        'party_two',
        'subject',
        'court_type_id',
        'case_type_id',
        'exist_now'
    ];

    public function courtType()
    {
        return $this->belongsTo(CourtType::class);
    }

    public function caseType()
    {
        return $this->belongsTo(CaseType::class);
    }

    public function powerOfAttorneys(){
        return $this->haseMany(PowerOfAttorney::class);
    }

    public function decisions(){
        return $this->haseMany(Decision::class,'case_id');
    }

    public function case_judges()
    {
        return $this->hasMany(CaseJudge::class,'case_id');
    }

    public function judgeSections(){
        return $this->belongsToMany(JudgeSection::class, 'case_judges', 'case_id', 'judge_section_id')
                    ->withPivot('id','is_seen','status', 'date_close_case','full_number')
                    ->withTimestamps();
    }

    public function lawyers()
    {
        return $this->belongsToMany(LawyerCourt::class, 'power_of_attorneys', 'case_id', 'lawyerCourt_id')
                    ->withPivot('representing')
                    ->withTimestamps();
    }

     
    public function now()
    {
        return $this->belongsTo(JudgeSection::class,'exist_now','id');
    }


    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $max = Cases::where('case_type_id',$model->case_type_id)->max('number');
            $model->number = $max ? $max + 1 : 1 ;

            $model->full_number = $model->caseType->short_form . "-"
                    .str_pad($model->number , 6 , 0 ,STR_PAD_LEFT);
        });
    }


    
}
