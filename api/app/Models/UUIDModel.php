<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class UUIDModel extends Model
{
    protected $primaryKey = "uuid";

    protected $keyType = "string";

    public $incrementing = false;

    public function getRouteKeyName(): string
    {
    return 'uuid';
    }

    public function getKeyName(): string
    {
        return 'uuid';
    }

    public static function boot() {
        parent::boot();

        static::creating(function($model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }
}
