<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    protected $fillable = [
        'food_surplus_id', 'recipient_id', 'claimed_portions',
        'pickup_code', 'status', 'proof_photo', 'distributed_at'
    ];

    protected $casts = ['distributed_at' => 'datetime'];

    public function foodSurplus(): BelongsTo
    {
        return $this->belongsTo(FoodSurplus::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}