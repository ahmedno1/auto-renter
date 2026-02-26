<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


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

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }


    public function getImageUrl(): string
    {
        if (!$this->image) {
            return asset('image/car.png');
        }

        $publicFile = public_path('storage/' . $this->image);

        if (is_file($publicFile)) {
            return asset('storage/' . $this->image);
        }

        if (Storage::disk('public')->exists($this->image)) {
            return route('uploads.show', ['path' => $this->image]);
        }

        return asset('image/car.png');
    }

    public function isAvailableBetween($start, $end): bool
    {
        $start = $start instanceof Carbon ? $start : Carbon::parse($start);
        $end = $end instanceof Carbon ? $end : Carbon::parse($end);

        // Block if any reservation overlaps, considering pending or approved
        return !$this->reservations()
            ->whereIn('status', ['pending', 'approved'])
            ->overlapping($start, $end)
            ->exists();
    }
}
