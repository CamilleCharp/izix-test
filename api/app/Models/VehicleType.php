<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "name",
        "maximum_ac_input",
        "maximum_dc_input",
        "battery_capacity",
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'type_id');
    }
}
