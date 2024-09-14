<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtType extends Model
{
    use HasFactory;

    protected $fillable = [
         'court_id','type', 'type_form', 'city',
    ];

    public function court()
    {
        return $this->belongsTo(Court::class,'court_id');
    }

    public function judgeSections()
    {
        return $this->hasMany(JudgeSection::class, 'court_type_id');
    }



    

    
}
