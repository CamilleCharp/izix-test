<?php

namespace App\Models;

use App\Models\UUIDModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Connector extends UUIDModel
{
    use HasFactory;

    public function station()
    {
        return $this->belongsTo(Station::class, 'station_uuid');
    }

    public function type()
    {
        return $this->belongsTo(ConnectorType::class, 'type_id');
    }
}
