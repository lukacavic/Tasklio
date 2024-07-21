<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Contact extends BaseModel
{
    protected static function booted(): void
    {
        parent::booted();

        static::created(function (Model $model) {
            $client = Client::find($model->client_id);

            if ($client == null) return;

            $contactsCount = $client->contacts()->count();

            if ($contactsCount == 1) {
                $client->update([
                    'primary_contact_id' => $model->id,
                ]);
            }
        });
    }

    public function primaryContactForClient()
    {
        return $this->hasOne(Client::class, 'primary_contact_id');
    }
}
