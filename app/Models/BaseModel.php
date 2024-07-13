<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        $isProjectPanel = Filament::getCurrentPanel()->getId() == 'project';

        static::creating(function (Model $model) use($isProjectPanel) {
            if (auth()->hasUser()) {
                $model->organisation_id = auth()->user()->organisation_id;

                if (Schema::hasColumn($model->getTable(), 'user_id')) {
                    $model->user_id = auth()->user()->id;
                }

                if(Schema::hasColumn($model->getTable(), 'project_id') && $isProjectPanel ) {
                    $model->project_id = Filament::getTenant()->id;
                }
            }
        });

        //Filter all queries by organisation.
        static::addGlobalScope('organisation', function (Builder $query)  {
            if (auth()->hasUser() ) {
                $query->where('organisation_id', auth()->user()->organisation_id);
            }
        });
    }

    public function addLog($logMessage): void
    {
        activity()->performedOn($this)->by(auth()->user())->withProperty('system', false)->log($logMessage);
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function userCreated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
