<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'discount',
        'count',
        'sold_quantity',
        'description',
        'cat_id',
    ];

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }

    public function image()
    {
        return $this->hasMany(Image::class);
    }
}
