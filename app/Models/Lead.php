<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Lead extends BaseModel implements HasMedia
{
    use HasTags, InteractsWithMedia, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function getDates()
    {
        return ['last_contact_at'];
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'link_lead_followers', 'lead_id', 'user_id');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'related');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'related');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LeadStatus::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'related');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function convertToClient(array $data): void
    {
        //kreiraj kompaniju
        $client = Filament::getTenant()->clients()->create([
            'name' => $data['company'],
            'country' => $data['country'],
            'phone' => $data['phone'],
            'website' => $data['website'],
        ]);

        $client->contacts()->create([
            'full_name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'position' => $data['position'],
        ]);

        $client->addLog('Klijent kreiran od potencijalnog klijenta');

        $this->update([
            'client_id' => $client->id,
            'client_converted_at' => now(),
            'status_id' => Filament::getTenant()->leadStatuses()->where('is_client', true)->get()->first()->id,
        ]);

        //Update lead notes and transfer to client
        if ($data['transfer_notes']) {
            Note::where([
                'related_type' => Lead::class,
                'related_id' => $this->id,
            ])->update([
                'related_type' => Client::class,
                'related_id' => $client->id,
            ]);
        }

        $this->addLog('Potencijalni klijent prebačen u klijenta.');
    }
}
