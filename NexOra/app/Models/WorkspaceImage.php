<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkspaceImage extends Model
{
    use HasFactory;

    protected $fillable = ['workspace_id', 'image_path', 'alt_text', 'is_primary', 'sort_order'];
    protected function casts(): array { return ['is_primary' => 'boolean', 'sort_order' => 'integer']; }

    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
}