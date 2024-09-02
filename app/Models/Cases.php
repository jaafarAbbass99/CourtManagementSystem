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
        'court_id',
        'case_type_id',
    ];

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function caseType()
    {
        return $this->belongsTo(CaseType::class);
    }

    public function powerOfAttorneys(){
        return $this->haseMany(PowerOfAttorney::class);
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
