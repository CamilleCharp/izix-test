<?php

namespace App\Models;

use App\Models\UUIDModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends UUIDModel
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
}
