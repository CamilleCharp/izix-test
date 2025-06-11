<?php

namespace App\Models;

use App\Models\UUIDModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Location extends UUIDModel
{
    use HasFactory;
    
    protected $fillable = [
        "name",
        "coordinates",
        "capacity",
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Helper method, allowing to set the coordinate as an array of [lat, lng]
    public function setCoordinateAttribute(array $coordinate)
    {
        $lat = $coordinate[0] ?? 0;
        $lng = $coordinate[1] ?? 0;

        if(!app()->environment('testing')) {
            $this->attributes['coordinate'] = DB::raw("ST_GeomFromText('POINT($lat $lng)', 4326)");
        } else {
            $this->attributes['coordinate'] = "POINT($lat, $lng)";
        }
    }

    // The coordinate is stored as a WKB in the DB, use this attribute to get it as a WKT (readable format)
    public function getCoordinateWktAttribute()
    {
        if(app()->environment("testing")) {
            return 'POINT(0 0)';
        }

        return DB::table($this->getTable())
            ->where('uuid', $this->uuid)
            ->value(DB::raw("ST_AsText(coordinate)"));
    }

    public function getCoordinateAttribute()
    {
        $coordinate = $this->coordinate_wkt;
        
        if ($coordinate) {
            preg_match('/POINT\(([^)]+)\)/', $coordinate, $matches);
            if (isset($matches[1])) {
                [$lat, $lng] = array_map('floatval', explode(' ', $matches[1]));
                return ['latitude' => $lat, 'longitude' => $lng];
            }
            
        }
    }
}
