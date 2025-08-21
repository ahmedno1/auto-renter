<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Car extends Model
{
    protected $fillable = [
        'owner_id',
        'image',
        'brand',
        'model',
        'year',
        'daily_rent',
        'description',
        'status',
    ];

    protected $casts = [
        'owner_id' => 'integer',
        'year' => 'integer',
        'daily_rent' => 'decimal:2'
    ];

        public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


    public function getImageUrl(): string
    {
        return $this->image ? asset('storage/' . $this->image) : 'https://placehold.co/120x80?text=Car';
    }
}
