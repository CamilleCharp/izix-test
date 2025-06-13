<?php

namespace App\Http\Requests;

use App\Models\ChargingSession;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class ChargingSessionStartRequest extends ChargingSessionRequests
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !$this->connectorIsInUse() &&
            !$this->vehicleIsInUse();
    }

    public function failedAuthorization() {
        if($this->vehicleIsInUse()) {
            throw new AuthorizationException("Vehicle is currently charging, unable to start a new charging session");
        }

        if($this->connectorIsInUse()) {
            throw new AuthorizationException("Connector is currently in use, unable to start a new charging session");
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "starting_battery_percent" => "required|numeric|between:0,100",
            "vehicle_uuid" => "required|exists:vehicles,uuid",
            "connector_uuid" => "required|exists:connectors,uuid",
        ];
    }

    public function messages(): array
    {
        return [
            'starting_battery_percent.required' => 'The starting battery (in %) is required',
            'starting_battery_percent.numeric' => 'The starting battery (in %) must be a number',
            'starting_battery_percent.between' => 'The battery cannot go below 0% or 100%',
            'vehicle_uuid.required' => 'The uuid of the vehicle to charge is required.',
            'vehicle_uuid.exists' => 'The vehicle to charge must be registered',
            'connector_uuid.required' => 'The uuid of the connector to use is required.',
            'connector_uuid.exists' => 'The connector to use must be registered',
        ];
    }
        

    private function connectorIsInUse()
    {
        return ChargingSession::where('connector_uuid', $this->input('connector_uuid'))
            ->whereNotNull('token')
            ->exists();
    }

    private function vehicleIsInUse()
    {
        return ChargingSession::where('vehicle_uuid', $this->input('vehicle_uuid'))
            ->whereNotNull('token')
            ->exists();
    }


    
}
