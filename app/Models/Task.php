<?php

namespace App\Models;

use App\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Task extends BaseModel implements HasMedia
{
    use HasTags, LogsActivity, InteractsWithMedia;

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (Model $model) {
            $model->status_id = 1;

            if ($model->related_id == null || $model->related_type == null) {
                $model->related_id = auth()->id();
                $model->related_type = User::class;
            }
        });

    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->dontLogFillable()
            ->setDescriptionForEvent(function ($name) {
                if ($name === 'created') {
                    return "Kreirao zadatak";
                } else if ($name === 'updated') {
                    return 'Ažurirao informacije o zadatku.';
                }

                return $name;
            });
    }

    public function scopeNotCompleted(Builder $query): void
    {
        $query->whereNot('status_id', TaskStatus::Completed->value);
    }
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'link_task_users');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function updateTaskStatus($statusId): void
    {
        $this->update([
            'status_id' => $statusId
        ]);

        $this->addLog('Promjenjen status zadatka na: ' . TaskStatus::from($statusId)->getLabel());
    }

    public function usersToNotify(): \Illuminate\Support\Collection
    {
        $users = collect();

        foreach ($this->members as $member) {
            $users->add($member);
        }

        $users->add($this->creator);

        return $users->filter(function (User $user, int $key) {
            return $user->id != auth()->id();
        });
    }
}
