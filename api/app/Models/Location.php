<?php

namespace App\Models;

use App\Models\UUIDModel;

class Location extends UUIDModel
{
    protected $fillable = [
        "name",
        "coordinates",
        "capacity",
    ];
}
