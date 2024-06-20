<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Tags\HasTags;

class Event extends BaseModel
{
    use HasTags;

    public function getDates()
    {
        return ['created_at', 'updated_at', 'start_at', 'end_at'];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'link_event_users');
    }
}
