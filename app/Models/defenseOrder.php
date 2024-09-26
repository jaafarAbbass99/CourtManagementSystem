<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class defenseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'interest_id',
    ];

    public function interest()
    {
        return $this->belongsTo(Interest::class, 'interest_id');
    }

    public function order()
    {
        return $this->morphOne(order::class, 'orderable');
    }
}
