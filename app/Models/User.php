<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
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

class User extends Authenticatable implements FilamentUser, HasDefaultTenant, HasTenants
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
        static::addGlobalScope('organisation', function (Builder $builder) {
            if (auth()->hasUser()) {
                $builder->where('organisation_id', auth()->user()->organisation_id);
            }
        });
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
        if($panel->getId() == 'project') {
            return $this->projects->first();
        }

        return $this->organisation;
    }

    public function getTenants(Panel $panel): array|Collection
    {
        if($panel->getId() == 'project') {
            return $this->projects;
        }

        return [];
    }
}
