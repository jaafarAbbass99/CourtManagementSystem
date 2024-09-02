<?php

namespace App\Models;

use App\Enums\ReqDocs;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequiredIdeDoc extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'req_doc','for',
    ];

    protected $casts = [
        'req_doc' => ReqDocs::class,
        'for' => Role::class,
    ];

    public static function forLawyers()
    {
        return self::where('for', Role::LAWYER->value)->get();
    }

 
    public static function forJudges()
    {
        return self::where('for', Role::JUDGE->value)->get();
    }

    public static function forBoth()
    {
        return self::whereIn('for', [Role::LAWYER->value, Role::JUDGE->value])->get();
    }
}
