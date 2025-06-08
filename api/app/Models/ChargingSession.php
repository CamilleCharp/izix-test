<?php

namespace App\Models;

use App\Models\UUIDModel;

class ChargingSession extends UUIDModel
{
    const CREATED_AT = 'started_at';
    const UPDATED_AT = 'last_status_update';

    protected $fillable = [
        'status',
        'starting_battery_percent',
        'current_battery_percent',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_uuid');
    }

    public function connector()
    {
        return $this->belongsTo(Connector::class, 'connector_uuid');
    }
}
