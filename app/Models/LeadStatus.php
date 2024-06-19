<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadStatus extends BaseModel
{
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'status_id');
    }
}