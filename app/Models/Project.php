<?php

namespace App\Models;

use App\ProjectSettingsItems;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Glorand\Model\Settings\Traits\HasSettingsField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model implements HasCurrentTenantLabel
{
    use HasFactory, SoftDeletes, HasSettingsField;

    protected $guarded = ['id'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public $defaultSettings = [
        ProjectSettingsItems::LEADS_MANAGEMENT_ENABLED->value => true
    ];

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

        static::creating(function (Model $model) {
            $model->slug = Str::slug($model->name);
        });
    }

    public function events(): MorphMany
    {
        return $this->morphMany(Event::class, 'related');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function projectMilestones(): HasMany
    {
        return $this->hasMany(ProjectMilestone::class);
    }

    public function ticketDepartments(): HasMany
    {
        return $this->hasMany(TicketDepartment::class);
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

    public function projectLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
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

    public function leadStatuses(): HasMany
    {
        return $this->hasMany(LeadStatus::class);
    }

    public function leadSources(): HasMany
    {
        return $this->hasMany(LeadSource::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'related');
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'project_id');
    }

    public function getCurrentTenantLabel(): string
    {
        return 'TRENUTNI PROJEKT';
    }
}
