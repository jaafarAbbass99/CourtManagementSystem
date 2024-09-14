<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LawyerCourt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'court_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function powerOfAttorneys()
    {
        return $this->hasMany(PowerOfAttorney::class, 'lawyer_id');
    }

    public function cases()
    {
        return $this->belongsToMany(Cases::class, 'power_of_attorneys', 'lawyerCourt_id', 'case_id')
                    ->withPivot('representing')
                    ->withTimestamps();
    }

}
