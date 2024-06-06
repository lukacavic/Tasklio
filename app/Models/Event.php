<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends BaseModel
{
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
