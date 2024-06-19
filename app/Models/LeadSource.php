<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadSource extends BaseModel
{
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
