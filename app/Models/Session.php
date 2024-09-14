<?php

namespace App\Models;

use App\Enums\SessionStatus;
use App\Enums\SessionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_judge_id',
        'session_number',
        'session_date',
        'session_time',
        'session_type',
        'session_status',
    ];

    protected $casts = [
        'session_type' => SessionType::class,
        'session_status' => SessionStatus::class ,
    ];

    public function caseJudge()
    {
        return $this->belongsTo(CaseJudge::class, 'case_judge_id');
    }

    public function decision(){
        return $this->hasMany(Decision::class ,'session_id');
    }

}
