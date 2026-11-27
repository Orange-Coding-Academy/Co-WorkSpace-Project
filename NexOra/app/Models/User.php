<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'password', 'role', 'is_active', 'email_verified_at', 'reminder_sent_at'];

    protected $hidden = ['password', 'remember_token'];

    public function managedWorkspaces(): HasMany
    {
        return $this->hasMany(Workspace::class, 'manager_id');
    }
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    
}