<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
class Workspace extends Model
{
    use HasFactory;

    protected $fillable = [
    'manager_id',
    'name',
    'location',
    'description',
    'facilities',
    'approval_status',
    'is_active'
];
    protected function casts(): array
{
    return [
        'facilities' => 'array',
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2',
    ];
}

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
    public function membershipPlans(): HasMany
    {
        return $this->hasMany(MembershipPlan::class);
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function images(): HasMany
    {
        return $this->hasMany(WorkspaceImage::class);
    }
 
    public function bookings(): HasManyThrough
    {
        return $this->hasManyThrough(Booking::class, Room::class);
    }
}