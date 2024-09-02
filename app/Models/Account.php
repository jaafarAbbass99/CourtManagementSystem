<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Account extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'user_name',
        'email',
        'password',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'=>'hashed'
    ];

//******************** relations ****************************/
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

//**********************end relations **********************/



    public function isPending()
    {
        return $this->status == Status::PENDING->value ;
    }

    public function isApproved()
    {
        return $this->status == Status::APPROVED->value ;
    }

    public function isRejected()
    {
        return $this->status == Status::REJECTED->value ;
    }

}
