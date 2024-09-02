<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;


    protected $fillable = [
        'doc_name', 'document_path'
    ];

    protected $casts = [
        //'created_at' => 'date',
        //'updated_at' => 'date'
    ];


//** relations */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

//**end relations */


}
