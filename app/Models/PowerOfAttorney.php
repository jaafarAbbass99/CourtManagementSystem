<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowerOfAttorney extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'lawyerCourt_id',
        'representing',
        'get'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class);
    }

    public function lawyerInCourt()
    {
        return $this->belongsTo(LawyerCourt::class, 'lawyerCourt_id');
    }
}
