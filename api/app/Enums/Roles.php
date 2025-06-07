<?php

namespace App\Enums;

enum Roles: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case GUEST = 'guest';
    case STATION = 'station';
    case VEHICLE = 'vehicle';
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::USER => 'User',
            self::GUEST => 'Guest',
            self::STATION => 'Station',
            self::VEHICLE => 'Vehicle',
        };
    }
}
