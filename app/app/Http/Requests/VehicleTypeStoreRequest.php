<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class VehicleTypeStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo(Permissions::REGISTER_VEHICLE_TYPE);
    }

    public function failedAuthorization() {
        if(!$this->user()->hasPermissionTo(Permissions::REGISTER_VEHICLE_TYPE)) {
            throw new AuthorizationException("You do not have the permission to create a new vehicle type");
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
            "name"=> "required|string|max:255",
            "maximum_ac_input" => "required|numeric|min:100",
            "maximum_dc_input" => "required|numeric|min:100",
            "battery_capacity" => "required|numeric|min:1",
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The vehicle type name is required',
            'name.string' => 'The name must be a string',
            'name.max' => 'The name cannot exceed 255 characters',
            'maximum_ac_input.numeric' => 'The AC input must be a number',
            'maximum_ac_input.min' => 'The AC input must be greater than 100watts',
            'maximum_dc_input.numeric' => 'The DC input must be a number',
            'maximum_dc_input.min' => 'The DC input must be greater than 100watts',
            'battery_capacity.numeric' => 'The battery capacity must be a number',
            'battery_capacity.min' => 'The battery capacity must be greater than 1watt/hour',
        ];
    }
}
