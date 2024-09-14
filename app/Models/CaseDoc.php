<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseDoc extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'summary',
        'type',
        'court_type_id',
        'case_id',
        'doc_id',
        'user_id'
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function file()
    {
        return $this->belongsTo(Document::class,'doc_id');
    }

    public function courtType()
    {
        return $this->belongsTo(CourtType::class, 'court_type_id');
    }

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }

    public function by(){
        return $this->belongsTo(User::class,'user_id');
    }

}
