<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Task extends BaseModel
{
    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (Model $model) {
            $model->status_id = 1;
        });

    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'link_task_users');
    }

    public function project(): MorphTo
    {
        return $this->related();
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }
  

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
