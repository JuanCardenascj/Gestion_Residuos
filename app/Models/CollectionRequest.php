<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_id', 'vehicle_id', 'date', 'type', 'weight', 'points', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
