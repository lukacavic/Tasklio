<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Event extends BaseModel
{
    public function project(): MorphTo
    {
        return $this->related();
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }
}
