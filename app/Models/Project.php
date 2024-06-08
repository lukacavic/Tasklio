<?php

namespace App\Models;

use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model implements HasCurrentTenantLabel
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::addGlobalScope('organisation', function (Builder $builder) {
            if (auth()->hasUser()) {
                $builder->where('organisation_id', auth()->user()->organisation_id);
            }
        });

        static::updating(function (Model $model) {
            if ($model->internal) {
                $model->client_id = null;
            } else {
                $model->clients()->detach();
            }
        });
    }

    public function events(): MorphMany
    {
        return $this->morphMany(Event::class, 'related');
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'related');
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'link_project_clients');
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'link_project_users');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'related');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function vaults(): MorphMany
    {
        return $this->morphMany(Vault::class, 'related');
    }
    public function knowledgeArticles(): HasMany
    {
        return $this->hasMany(KnowledgeArticle::class);
    }

    public function knowledgeCategories(): HasMany
    {
        return $this->hasMany(KnowledgeCategory::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'related');
    }

    public function getCurrentTenantLabel(): string
    {
        return 'TRENUTNI PROJEKT';
    }
}
