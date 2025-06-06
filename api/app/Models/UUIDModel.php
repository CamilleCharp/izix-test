<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UUIDModel extends Model
{
    protected $primaryKey = "uuid";

    protected $keyType = "string";

    public $incrementing = false;

    public static function boot() {
        parent::boot();

        static::creating(function($model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }
}
