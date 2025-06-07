<?php

namespace App\Enums;

enum Permissions: string
{
    case VIEW_TENANTS = 'view_tenants';
    case REGISTER_TENANT = 'register_tenant';
    
    case VIEW_LOCATIONS = 'view_locations';
    case REGISTER_LOCATION = 'register_location';

    case VIEW_CHARGING_STATIONS_TYPES = 'view_charging_station_types';
    case REGISTER_CHARGING_STATION_TYPE = 'register_charging_station_type';
    case DELETE_CHARGING_STATION_TYPE = 'delete_charging_station_type';
    
    case VIEW_CHARGING_STATIONS = 'view_charging_stations';
    case REGISTER_CHARGING_STATION = 'register_charging_station';
    case DELETE_CHARGING_STATION = 'delete_charging_station';

    case VIEW_CHARGINS_SESSIONS = 'view_charging_sessions';
    case START_CHARGING_SESSION = 'start_charging_session';
    case END_CHARGING_SESSION = 'end_charging_session';
    case FORCE_END_CHARGING_SESSION = 'force_end_charging_session';

    case VIEW_VEHICLES = 'view_vehicles';
    case REGISTER_VEHICLE = 'register_vehicle';
    case DELETE_VEHICLE = 'delete_vehicle';
    case DELETE_EXTERNAL_VEHICLE = 'delete_external_vehicle';
    case VIEW_EXTERNAL_VEHICLES = 'view_external_vehicles';
    case REGISTER_EXTERNAL_VEHICLE = 'register_external_vehicle';

    public function label(): string
    {
        return match ($this) {
            self::VIEW_TENANTS => 'View tenants',
            self::REGISTER_TENANT => 'Register tenant',
            
            self::VIEW_LOCATIONS => 'View locations',
            self::REGISTER_LOCATION => 'Register location',

            self::VIEW_CHARGING_STATIONS_TYPES => 'View charging station types',
            self::REGISTER_CHARGING_STATION_TYPE => 'Register charging station type',
            self::DELETE_CHARGING_STATION_TYPE => 'Delete charging station type',
            
            self::VIEW_CHARGING_STATIONS => 'View charging stations',
            self::REGISTER_CHARGING_STATION => 'Register charging station',
            self::DELETE_CHARGING_STATION => 'Delete charging station',

            self::VIEW_CHARGINS_SESSIONS => 'View charging sessions',
            self::START_CHARGING_SESSION => 'Start charging session',
            self::END_CHARGING_SESSION => 'End charging session',
            self::FORCE_END_CHARGING_SESSION => 'Force end charging session',

            self::VIEW_VEHICLES => 'View own vehicles',
            self::REGISTER_VEHICLE => 'Register own vehicles',
            self::DELETE_VEHICLE => 'Delete own vehicles',
            self::DELETE_EXTERNAL_VEHICLE => 'Delete external vehicles',
            self::VIEW_EXTERNAL_VEHICLES => 'View external vehicles',
            self::REGISTER_EXTERNAL_VEHICLE => 'Register external vehicles',
        };
    }
}
