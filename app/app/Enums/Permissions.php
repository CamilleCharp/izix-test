<?php

namespace App\Enums;

/**
 * Representation of all the users permissions
 * used in tandem with Roles
 * @see project://app/Enums/Roles.php
 */
enum Permissions: string
{
    case VIEW_TENANTS = 'view_tenants';
    case REGISTER_TENANT = 'register_tenant';
    case UPDATE_TENANT = 'update_tenant';
    case DELETE_TENANT = 'delete_tenant';

    
    case VIEW_LOCATIONS = 'view_locations';
    case REGISTER_LOCATION = 'register_location';
    case UPDATE_LOCATION = 'update_location';
    case DELETE_LOCATION = 'delete_location';

    case VIEW_CHARGING_STATIONS_TYPES = 'view_charging_station_types';
    case REGISTER_CHARGING_STATION_TYPE = 'register_charging_station_type';
    case UPDATE_CHARGING_STATION_TYPE = 'update_charging_station_type';
    case DELETE_CHARGING_STATION_TYPE = 'delete_charging_station_type';
    
    case VIEW_CHARGING_STATIONS = 'view_charging_stations';
    case REGISTER_CHARGING_STATION = 'register_charging_station';
    case UPDATE_CHARGING_STATION = 'update_charging_station';
    case DELETE_CHARGING_STATION = 'delete_charging_station';

    case VIEW_CONNECTORS = 'view_connectors';
    case REGISTER_CONNECTOR = 'register_connector';
    case UPDATE_CONNECTOR = 'update_connector';
    case DELETE_CONNECTOR = 'delete_connector';

    case VIEW_CHARGING_SESSIONS = 'view_charging_sessions';
    case VIEW_EXTERNAL_CHARGING_SESSIONS = 'view_external_charging_sessions';
    case START_CHARGING_SESSION = 'start_charging_session';
    case UPDATE_CHARGING_SESSION = 'update_charging_session';
    case END_CHARGING_SESSION = 'end_charging_session';
    case FORCE_END_CHARGING_SESSION = 'force_end_charging_session';

    case VIEW_VEHICLE_TYPES = 'view_vehicle_types';
    case REGISTER_VEHICLE_TYPE = 'register_vehicle_type';
    case UPDATE_VEHICLE_TYPE = 'update_vehicle_type';
    case DELETE_VEHICLE_TYPE = 'delete_vehicle_type';

    case VIEW_VEHICLES = 'view_vehicles';
    case REGISTER_VEHICLE = 'register_vehicle';
    case UPDATE_VEHICLE = 'update_vehicle';
    case DELETE_VEHICLE = 'delete_vehicle';
    case DELETE_EXTERNAL_VEHICLE = 'delete_external_vehicle';
    case VIEW_EXTERNAL_VEHICLES = 'view_external_vehicles';
    case REGISTER_EXTERNAL_VEHICLE = 'register_external_vehicle';
    case UPDATE_EXTERNAL_VEHICLE = 'update_external_vehicle';

    /**
     * Return a readable summary of what the permission is
     * @return string The permission description
     */
    public function label(): string
    {
        return match ($this) {
            self::VIEW_TENANTS => 'View tenants',
            self::REGISTER_TENANT => 'Register tenant',
            self::UPDATE_TENANT => 'Update tenant',
            self::DELETE_TENANT => 'Delete tenant',
            
            self::VIEW_LOCATIONS => 'View locations',
            self::REGISTER_LOCATION => 'Register location',
            self::UPDATE_LOCATION => 'Update location',
            self::DELETE_LOCATION => 'Delete location',

            self::VIEW_CHARGING_STATIONS_TYPES => 'View charging station types',
            self::REGISTER_CHARGING_STATION_TYPE => 'Register charging station type',
            self::UPDATE_CHARGING_STATION_TYPE => 'Update charging station type',
            self::DELETE_CHARGING_STATION_TYPE => 'Delete charging station type',
            
            self::VIEW_CHARGING_STATIONS => 'View charging stations',
            self::REGISTER_CHARGING_STATION => 'Register charging station',
            self::UPDATE_CHARGING_STATION => 'Update charging station',
            self::DELETE_CHARGING_STATION => 'Delete charging station',

            self::VIEW_CONNECTORS => 'View connectors',
            self::REGISTER_CONNECTOR => 'Register connector',
            self::DELETE_CONNECTOR => 'Delete connector',

            self::VIEW_CHARGING_SESSIONS => 'View charging sessions',
            self::VIEW_EXTERNAL_CHARGING_SESSIONS => 'View external charging sessions',
            self::START_CHARGING_SESSION => 'Start charging session',
            self::UPDATE_CHARGING_SESSION => 'Update charging session',
            self::END_CHARGING_SESSION => 'End charging session',
            self::FORCE_END_CHARGING_SESSION => 'Force end charging session',

            self::VIEW_VEHICLE_TYPES => 'View vehicle types',
            self::REGISTER_VEHICLE_TYPE => 'Register vehicle type',
            self::UPDATE_VEHICLE_TYPE => 'Update vehicle type',
            self::DELETE_VEHICLE_TYPE => 'Delete vehicle type',

            self::VIEW_VEHICLES => 'View own vehicles',
            self::REGISTER_VEHICLE => 'Register own vehicles',
            self::UPDATE_VEHICLE => 'Update own vehicles',
            self::DELETE_VEHICLE => 'Delete own vehicles',
            self::DELETE_EXTERNAL_VEHICLE => 'Delete external vehicles',
            self::VIEW_EXTERNAL_VEHICLES => 'View external vehicles',
            self::REGISTER_EXTERNAL_VEHICLE => 'Register external vehicles',
            self::UPDATE_EXTERNAL_VEHICLE => 'Update external vehicles',
        };
    }
}
