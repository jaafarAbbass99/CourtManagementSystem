<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JudgeSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'section_id', 'court_id', 'role',
    ];


    // public static function findOrFail($id)
    // {
    //     $model = static::find($id);

    //     if (!$model) {
    //         throw new ModelNotFoundException("القسم بقيمة {$id} غير موجود.");
    //     }

    //     return $model;
    // }



    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function judge()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
