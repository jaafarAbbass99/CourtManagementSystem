<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;

    protected $fillable = [
        'lawyer_court_id' , 'status_order'
    ];

    protected $casts = [
        'status_order' => Status::class ,
    ];

    public function orderable()
    {
        return $this->morphTo();
    }

    public function lawyerUser()
    {
        return $this->hasOneThrough(User::class, LawyerCourt::class, 'id', 'id', 'lawyer_court_id', 'user_id');
    }

    
    public function LawyerCourt()
    {
        return $this->belongsTo(LawyerCourt::class, 'lawyer_court_id');
    }

    public function requester()
    {
        // الوصول إلى المستخدم عبر AttorneyOrders
        return $this->hasOneThrough(User::class, AttorneyOrders::class, 'id', 'id', 'orderable_id', 'user_id');
    }

    public function interest()
    {
        return $this->hasOneThrough(Interest::class, defenseOrder::class, 'id', 'id', 'orderable_id', 'interest_id');
    }


}
