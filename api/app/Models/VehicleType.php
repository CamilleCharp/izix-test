<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
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
