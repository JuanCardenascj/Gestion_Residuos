<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    //Generate
    protected $fillable = [
        'company_id', 'plate', 'brand', 'model', 
        'capacity', 'type', 'status'
    ];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function requests()
    {
        return $this->hasMany(CollectionRequest::class);
    }
}
