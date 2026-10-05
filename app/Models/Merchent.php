<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Merchent extends Model
{
    protected $fillable = [
        'user_id', 'status', 'approved_at', 'rejected_at', 'rejected_reason',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(EcommUser::class, 'user_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'merchent_id');
    }
}
