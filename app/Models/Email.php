<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use RickDBCN\FilamentEmail\Models\Email as BaseEmail;

class Email extends BaseEmail
{
    public static function booted(): void
    {
        static::creating(function (Model $model) {
            if(auth()->hasUser()) {
                $model->organisation_id = auth()->user()->organisation_id;
            }
        });

        static::addGlobalScope('organisation', function (Builder $query) {
            if (auth()->hasUser()) {
                $query->where('organisation_id', auth()->user()->organisation_id);
            }
        });
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
