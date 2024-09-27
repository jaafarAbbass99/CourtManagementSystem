<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DewanOrder extends Model
{
    use HasFactory;

    protected $fillable = ['dewan_id', 'decision_order_id'];

    public function order()
    {
        return $this->belongsTo(DecisionOrder::class);
    }

    public function dewan()
    {
        return $this->belongsTo(Dewan::class);
    }

}
