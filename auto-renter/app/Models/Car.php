<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'image',
        'brand',
        'model',
        'year',
        'daily_rent',
        'description',
        'status',
    ];

    protected $casts = [
        'year' => 'integer',
        'daily_rent' => 'decimal:2'
    ];

    public function getImageUrl(): string
    {
        return $this->image ? asset('storage/' . $this->image) : 'https://placehold.co/120x80?text=Car';
    }
}
