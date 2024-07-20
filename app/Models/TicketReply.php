<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TicketReply extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
