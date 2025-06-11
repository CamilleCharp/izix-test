<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectorType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'max_voltage',
        'max_current',
        'max_watts',
        'current_type',
    ];

    public function connectors()
    {
        return $this->hasMany(Connector::class, 'type_id');
    }
}
