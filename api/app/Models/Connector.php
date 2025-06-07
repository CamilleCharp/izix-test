<?php

namespace App\Models;

use App\Models\UUIDModel;

class Connector extends UUIDModel
{
    public function station()
    {
        return $this->belongsTo(Station::class, 'station_uuid');
    }

    public function type()
    {
        return $this->belongsTo(ConnectorType::class, 'type_id');
    }
}
