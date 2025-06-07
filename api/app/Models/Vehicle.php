<?php

namespace App\Models;

use App\Models\UUIDModel;

class Vehicle extends UUIDModel
{
    protected $fillable = [
        "license_plate"
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type()
    {
        return $this->belongsTo(VehicleType::class,'type_id');
    }
}
