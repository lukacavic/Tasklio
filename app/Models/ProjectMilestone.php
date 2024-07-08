<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectMilestone extends BaseModel
{
    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('order', function (\Illuminate\Contracts\Database\Eloquent\Builder $builder) {
            $builder->orderBy('start_date', 'asc');
        });

    }
    public function getDates(): array
    {
        return ['created_at', 'updated_at', 'start_date', 'due_date'];
    }

    public function scopeFuture(Builder $query): void
    {
        $query->where('start_date', '>', now());
    }

    public function scopeCurrent(Builder $query): void
    {
        $query->where('start_date', '<', now())
                ->where('due_date', '>', now());
    }

    public function scopePast(Builder $query): void
    {
        $query->where('due_date', '<', now());
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_milestone_id');
    }


}
