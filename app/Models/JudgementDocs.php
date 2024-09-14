<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudgementDocs extends Model
{
    use HasFactory;

    protected $fillable = ['decision_id', 'case_doc_id'];
    public function decision() { return $this->belongsTo(Decision::class,'decision_id'); } 
    public function caseDoc() { return $this->belongsTo(CaseDoc::class,'case_doc_id'); }
    
}
