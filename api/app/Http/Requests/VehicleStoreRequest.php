<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class VehicleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo(Permissions::REGISTER_VEHICLE);
    }

    public function failedAuthorization() {
        if(!$this->user()->hasPermissionTo(Permissions::REGISTER_VEHICLE)) {
            throw new AuthorizationException("You do not have the permission to create a new vehicle");
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
            'owner' => $this->user()->hasPermissionTo(Permissions::REGISTER_EXTERNAL_VEHICLE) ? 'exists:owners,id' : 'prohibited',
            'type' => 'required|exists:vehicle_types,id',
            'plate' => 'required|string|max:16|unique:vehicles,license_plate',
        ];
    }

    public function messages(): array
    {
        return [
            'owner.exists' => 'The owner must be an existing user',
            'owner.prohibited' => 'You cannot chose who the owner of the vehicle is',
            'type.required' => 'The vehicle type id must be specified',
            'type.exists' => 'The vehicle type must be already registered.',
            'plate.required' => 'A license plate is required',
            'plate.string' => 'The license plate must be a string',
            'plate.max' => 'The license plate cannot exceed 16 characters.',
            'plate.unique' => 'The license plate is already registered.',
        ];
    }
}
