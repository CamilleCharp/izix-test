<?php

namespace App\Models;

use App\Models\UUIDModel;

class Tenant extends UUIDModel
{
    protected $fillable = [
        'name',
    ];
}
