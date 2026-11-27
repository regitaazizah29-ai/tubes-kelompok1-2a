<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoodSurplus extends Model
{
    protected $fillable = [
        'merchant_id', 'title', 'description',
        'initial_portions', 'remaining_portions',
        'weight_per_portion', 'expired_at'
    ];

    protected $casts = ['expired_at' => 'datetime'];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('remaining_portions', '>', 0)
                     ->where('expired_at', '>', now());
    }
}