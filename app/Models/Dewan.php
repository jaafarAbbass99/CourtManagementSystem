<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dewan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'court_type_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courtType()
    {
        return $this->belongsTo(CourtType::class);
    }

    public function processOrder()
    {
        return $this->hasMany(DewanOrder::class ,'dewan_id');
    }


}
