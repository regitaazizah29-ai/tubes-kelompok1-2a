<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Merchant extends Model
{
    protected $fillable = ['user_id', 'business_name', 'category', 'address', 'is_verified'];

    protected $casts = ['is_verified' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function foodSurpluses(): HasMany
    {
        return $this->hasMany(FoodSurplus::class);
    }

    public function claims(): HasManyThrough
    {
        return $this->hasManyThrough(Claim::class, FoodSurplus::class);
    }

    public function getSavedPortionsAttribute(): int
    {
        return (int) $this->claims()->where('claims.status', 'completed')->sum('claimed_portions');
    }

    public function getSavedWasteKgAttribute(): float
    {
        $completedClaims = $this->claims()->where('claims.status', 'completed')->with('foodSurplus')->get();

        $totalGrams = $completedClaims->sum(function ($claim) {
            return $claim->claimed_portions * $claim->foodSurplus->weight_per_portion;
        });

        return round($totalGrams / 1000, 2);
    }
}