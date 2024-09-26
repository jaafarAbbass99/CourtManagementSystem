<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttorneyOrders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    
    public function order()
    {
        return $this->morphOne(order::class, 'orderable');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
