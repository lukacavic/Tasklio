<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Vault extends BaseModel
{
    public function related(): MorphTo
    {
        return $this->morphTo();
    }
}
