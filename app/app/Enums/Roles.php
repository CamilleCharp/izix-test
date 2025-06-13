<?php

namespace App\Enums;

/**
 * Represent a user role, used in tandem with Permissions
 * @see project://app/Enums/Permissions.php
 */
enum Roles: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case GUEST = 'guest';
    case STATION = 'station';
    case VEHICLE = 'vehicle';
    
    /**
     * Return the formatted name of the role
     * @return string the formatted name of the role
     */
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
