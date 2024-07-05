<?php

namespace App\Models;

use IbrahimBougaoua\FilamentSortOrder\Traits\SortOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadStatus extends BaseModel
{
    use SortOrder;

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'status_id');
    }
}
