<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'court_id', 'section_number', 'location',
    ];

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function judges()
    {
        return $this->hasMany(JudgeSection::class);
    }

}
