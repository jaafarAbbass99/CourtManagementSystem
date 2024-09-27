<?php

namespace App\Models;

use App\Enums\Status;
use App\Enums\TypeOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecisionOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'decision_id',
        'user_id',
        'type_order',
        'status_order',
        'response_date',
    ];

    protected $casts = [
        'response_date' => 'date',
        'type_order' => TypeOrder::class ,
        'status_order' => Status::class ,
    ];

    public function processOrder()
    {
        return $this->hasMany(DewanOrder::class ,'dewan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function decision()
    {
        return $this->belongsTo(Decision::class);
    }


    
}
