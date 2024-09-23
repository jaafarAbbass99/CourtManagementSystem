<?php

namespace App\Models;

use App\Enums\Party;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'case_id',
        'party',
        'is_admin',
    ];

    protected $casts = [
        'party' => Party::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function case()
    {
        return $this->belongsTo(Cases::class);
    }
}
