<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StationLevel extends Model
{
    public $timestamps = false;

    public function types(): HasMany {
        return $this->hasMany(StationType::class);
    }
}
