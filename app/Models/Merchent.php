<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchent extends Model
{
    protected $fillable = [
        "user_id" , "status" , "approved_at" , "rejected_at" , "rejected_reason"
    ];

    public function user(){
        return $this->belongsTo(EcommUser::class);
    }
}
