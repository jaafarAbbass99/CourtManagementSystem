<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdenDoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'doc_id','user_id' , 'req_doc_id' ,'status'
    ];

    protected $casts = [
        'status' => Status::class
    ];

    public function file()
    {
        return $this->belongsTo(Document::class,'doc_id');
    }

    public function type()
    {
        return $this->belongsTo(RequiredIdeDoc::class,'req_doc_id');
    }


    
    public function isApproved()
    {
        return $this->status = Status::APPROVED->value;
    }

    public function isRejected()
    {
        return $this->status = Status::REJECTED->value;
    }

    public function isPending()
    {
        return $this->status = Status::PENDING->value;
    }

}
