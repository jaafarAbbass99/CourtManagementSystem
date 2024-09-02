<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewDocsLog extends Model
{
    use HasFactory;


    protected $fillable = [
        'admin_id',
        'docs_id',
        'status',
        'review_comments',
    ];

    protected $casts = [
        'status' => 'string',
    ];


//******************** relations ****************************/
    
    public function byAdmin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function doc()
    {
        return $this->belongsTo(Document::class,);
    }

//**********************end relations **********************/

    public function isTrue()
    {
        return $this->action === 'true';
    }

    public function isFalse()
    {
        return $this->action === 'false';
    }

    
}
