<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StationType extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'current', 'power'];

    public function level(): BelongsTo
    {
        return $this->belongsTo(StationLevel::class, 'station_level_id');
    }
}
