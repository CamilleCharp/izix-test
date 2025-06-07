<?php

namespace App\Models;

use App\Models\UUIDModel;

class Station extends UUIDModel
{
    protected $fillable = [
        "last_used_at",
        "spot"
    ];

    public function type()
    {
        return $this->belongsTo(StationType::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_uuid');
    }
}
