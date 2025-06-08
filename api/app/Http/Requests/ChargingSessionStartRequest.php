<?php

namespace App\Http\Requests;

use App\Models\ChargingSession;
use Illuminate\Foundation\Http\FormRequest;

class ChargingSessionStartRequest extends ChargingSessionRequests
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->hasValidAPIKey() &&
            !$this->connectorIsInUse() &&
            !$this->vehicleIsInUse();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "starting_battery_percent" => "required|numeric|min:0|max:100",
            "vehicle_uuid" => "required|exists:vehicles,uuid",
            "connector_uuid" => "required|exists:connectors,uuid",
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
