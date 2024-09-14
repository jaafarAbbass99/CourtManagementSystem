<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Win extends Model
{
    use HasFactory;

    protected $fillable = [
        'court_type_id',
        'attorney_id',
        'get'
    ];

    public function courtType()
    {
        return $this->belongsTo(CourtType::class);
    }

    public function attorney()
    {
        return $this->belongsTo(PowerOfAttorney::class,'attorney_id');
    }

}
