<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Crypt;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Vault extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    public function setContentAttribute(string $content) {
        $this->attributes['content'] = Crypt::encryptString($content);
    }

    public function getContentAttribute($value)
    {
        if (is_null($value)) return null;

        return Crypt::decryptString($value);
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

}
