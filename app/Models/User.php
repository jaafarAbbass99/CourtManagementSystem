<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Gender;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;



class User extends Model
{
    use  HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'role',
        'phone_number',
        'photo',
        'country' , 'city' , 'street'
    ];
    
    protected $casts = [
        'birth_date' => 'date',
        'gender' => Gender::class,
        'role' => Role::class,
    ];

//***************************** relations  **********************/

    public function account(): HasOne
    {
        return $this->hasOne(Account::class);
    }

    public function docs() : HasMany 
    {
        return $this->hasMany(Document::class);   
    }

    public function interest() : HasMany 
    {
        return $this->hasMany(Interest::class);   
    }

    // public function address(): HasOne
    // {
    //     return $this->hasOne(Address::class);
    // }

//***************************** end relations  **********************/



//** check from role */
    public function isJudge()
    {
        return $this->role = Role::JUDGE;
    }

    public function isLawyer()
    {
        return $this->role = Role::LAWYER ; 
    }

    public function isEmployee()
    {
        return $this->role = Role::EMPLOYEE ;
    }

    public function isAdmin()
    {
        return $this->role = Role::ADMIN->value;
    }
    public function isUser()
    {
        return $this->role = Role::USER->value;
    }

}
