<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'dispute_type_id' , 'short_form'];

    public function disputeType()
    {
        return $this->belongsTo(DisputeType::class);
    }

    

}
