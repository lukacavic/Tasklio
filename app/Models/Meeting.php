<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Meeting extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTags, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->setDescriptionForEvent(function($name) {
            if($name === 'created') {
                return "Kreirano";
            }

            return $name;
        });
    }

    public function userParticipants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'link_meeting_users');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
