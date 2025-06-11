<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo(Permissions::UPDATE_CHARGING_STATION);
    }

    public function failedAuthorization() {
        if(!$this->user()->hasPermissionTo(Permissions::UPDATE_CHARGING_SESSION)) {
            throw new AuthorizationException("You do not have the permission to update a charging station");
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
            "name"=> "string|max:255",
            "spot" => "integer|min:0",
            "type_id" => "exists:station_types,id",
            "location_uuid" => "exists:locations,uuid",
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'The tenant name must be a string.',
            'name.max' => 'The tenant name may not be greater than 255 characters.',
            'spot.integer' => 'The parking spot number must be a whole number',
            'spot.min' => 'The parking spot number cannot be negative',
            'type_id.exists' => 'The station type must be registered',
            'location_uuid.exists' => 'The location must be registered'
        ];
    }
}
