<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Filament\Enums\PropertyStatus;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements FilamentUser, HasDefaultTenant, HasTenants, HasAvatar
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guarded = ['id'];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('first_name', 'asc');
            $builder->orderBy('last_name', 'asc');
        });

        static::addGlobalScope('organisation', function (Builder $builder) {
            if (auth()->hasUser()) {
                $builder->where('organisation_id', auth()->user()->organisation_id);
            }
        });

        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('active', true);
        });
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'link_project_users');
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $tenant->users->contains($this);
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'link_event_users');
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        if ($panel->getId() == 'project') {
            return $this->projects->first();
        }

        return $this->organisation;
    }

    public function getTenants(Panel $panel): array|Collection
    {
        if ($panel->getId() == 'project') {
            return $this->projects;
        }

        return [];
    }
}
