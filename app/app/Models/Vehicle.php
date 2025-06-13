<?php

namespace App\Models;

use App\Models\UUIDModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends UUIDModel
{
    use HasFactory;

    protected $fillable = [
        "license_plate"
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function type()
    {
        return $this->belongsTo(VehicleType::class,'type_id');
    }
}
