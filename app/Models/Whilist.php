<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Whilist extends Model
{
    protected $fillable = [
        'user_id', 'product_id',
    ];

    public function user()
    {
        return $this->belongsTo(EcommUser::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
