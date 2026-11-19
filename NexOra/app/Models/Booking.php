<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'start_date_time',
        'end_date_time',
        'total_price',
        'status',
        'payment_expires_at',
        'commission_rate',
        'platform_commission_amount',
        'workspace_net_amount'
    ];
    protected function casts(): array
    {
        return ['start_date_time' => 'datetime', 'end_date_time' => 'datetime', 'payment_expires_at' => 'datetime', 'total_price' => 'decimal:2'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}