<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'province', 'city',
    ];

    public function courtTypes()
    {
        return $this->hasMany(CourtType::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

}
