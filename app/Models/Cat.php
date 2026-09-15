<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    protected $fillable = [
        'name',
        'img',
        'description',
        'status',
        'num_products',
    ];

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
