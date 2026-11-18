<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = ['workspace_id', 'plan_name', 'duration_type', 'duration_days', 'price', 'description', 'is_active'];
    protected function casts(): array { return ['duration_days' => 'integer', 'price' => 'decimal:2', 'is_active' => 'boolean']; }

    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
    public function memberships(): HasMany { return $this->hasMany(Membership::class, 'plan_id'); }
}
