<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends BaseModel
{
    public function primaryContactForClient() {
        return $this->hasOne(Client::class, 'primary_contact_id');
    }
}
