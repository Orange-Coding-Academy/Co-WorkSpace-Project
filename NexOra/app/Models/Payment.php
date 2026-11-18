<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'booking_id', 'membership_id', 'provider', 'stripe_checkout_session_id', 'stripe_payment_intent_id', 'amount', 'currency', 'status', 'paid_at', 'provider_response'];
    protected function casts(): array { return ['amount' => 'decimal:2', 'paid_at' => 'datetime', 'provider_response' => 'array']; }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
    public function membership(): BelongsTo { return $this->belongsTo(Membership::class); }
}