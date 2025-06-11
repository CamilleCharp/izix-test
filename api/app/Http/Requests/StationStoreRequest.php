<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo(Permissions::REGISTER_CHARGING_STATION);
    }

    public function failedAuthorization() {
        if(!$this->user()->hasPermissionTo(Permissions::REGISTER_CHARGING_STATION)) {
            throw new AuthorizationException("You do not have the permission to create a new charging station");
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
            "name" => "required|string|max:255",
            "spot" => "required|integer|min:0",
            "type_id" => "required|exists:station_types,id",
            "location_uuid" => "required|exists:locations,uuid",
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The station name is required',
            'name.string' => 'The station name must be a string.',
            'name.max' => 'The station name may not be greater than 255 characters.',
            'spot.required' => 'The parking spot number is required',
            'spot.integer' => 'The parking spot number must be a whole number',
            'spot.min' => 'The parking spot number cannot be negative',
            'type_id.required' => 'You must specify the station type id',
            'type_id.exists' => 'The station type must be registered',
            'location_uuid.required' => 'You must specify the station location',
            'location_uuid.exists' => 'The location must be registered'
        ];
    }
}
