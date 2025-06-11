<?php

namespace App\Models;

use App\Models\UUIDModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Station extends UUIDModel
{
    use HasFactory;
    
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
