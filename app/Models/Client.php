<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Client extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    public function vaults(): MorphMany
    {
        return $this->morphMany(Vault::class, 'related');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'related');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'related');
    }

}
