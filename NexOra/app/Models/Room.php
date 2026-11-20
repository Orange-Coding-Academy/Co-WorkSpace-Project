<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Room extends Model
{
    use HasFactory;

   protected $fillable = [
    'workspace_id',
    'name',
    'room_type',
    'capacity',
    'hourly_price',
    'facilities',
    'is_active'
];
   protected function casts(): array
{
    return [
        'capacity' => 'integer',
        'hourly_price' => 'decimal:2',
        'is_active' => 'boolean',
        'facilities' => 'array',
    ];
}

    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
    public function images()
{
    return $this->hasMany(RoomImage::class);
}
}
