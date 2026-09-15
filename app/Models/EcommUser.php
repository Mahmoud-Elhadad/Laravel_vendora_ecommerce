<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class EcommUser extends Authenticatable
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'password', 'image',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id', 'id');
    }

    public function wishlists()
    {
        return $this->hasMany(Whilist::class, 'user_id', 'id');
    }
}
