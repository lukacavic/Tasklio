<?php

namespace App\Models;

use App\TicketStatus;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Ticket extends BaseModel implements HasMedia
{
    use HasTags, InteractsWithMedia;

    protected $casts = [
        'status' =>  TicketStatus::class,
    ];
    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (Model $model) {
            $model->code = rand(11111,99999);
        });
    }

    public function getDates(): array
    {
        return ['created_at', 'updated_at', 'last_reply_at'];
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(TicketDepartment::class, 'department_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}
