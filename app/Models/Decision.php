<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'summary',
        'status',
        'favor',
        'case_id'
    ];

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }

    public function caseDocs()
    {
        return $this->hasManyThrough(
            CaseDoc::class, 
            JudgementDocs::class,
            'decision_id', // المفتاح الأجنبي في JudgementDocs
            'id',          // المفتاح الأجنبي في CaseDoc
            'id',          // المفتاح المحلي في Decision
            'case_doc_id'  // المفتاح المحلي في JudgementDocs
        );
    }

    public function judgementDocs(){
        return $this->hasMany(JudgementDocs::class , 'decision_id');
    }

}
