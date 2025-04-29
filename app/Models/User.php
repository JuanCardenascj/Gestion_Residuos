<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'points', 'whatsapp', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function collectionRequests()
    {
        return $this->hasMany(CollectionRequest::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'company_id');
    }
}

