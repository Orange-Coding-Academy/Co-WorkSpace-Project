<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function workspaces(): BelongsToMany { return $this->belongsToMany(Workspace::class, 'workspace_facilities')->withTimestamps(); }
    public function rooms(): BelongsToMany { return $this->belongsToMany(Room::class, 'room_facilities')->withTimestamps(); }
}