<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class VehicleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $canUpdateOwnVehicle = $this->user()->hasPermissionTo(Permissions::UPDATE_VEHICLE);
        $canUpdateAllVehicles = $this->user()->hasPermissionTo(Permissions::UPDATE_EXTERNAL_VEHICLE);

        if($canUpdateAllVehicles) return true;

        if($canUpdateOwnVehicle) {
            return $this->route('vehicle')->owner()->firstOrFail()->is($this->user());
        }
    }

    public function failedAuthorization() {
        if(!$this->user->hasPermissionTo(Permissions::UPDATE_VEHICLE)) {
            throw new AuthorizationException("You do not have the permission to update a vehicle.");
        }
        
        if(!$this->route('session')->vehicle()->first()->owner()->firstOrFail()->is($this->user())) {
            if(!$this->user->hasPermissionTo(Permissions::UPDATE_EXTERNAL_VEHICLE)) {
                throw new AuthorizationException('You are not authorized to update a vehicle other than yours');
            }
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
            'owner' => $this->user()->hasPermissionTo(Permissions::UPDATE_EXTERNAL_VEHICLE) ? 'exists:owners,id' : 'prohibited',
            'type' => 'exists:vehicle_types,id',
            'plate' => 'string|max:16|unique:vehicles,plate',
        ];
    }

    public function messages(): array
    {
        return [
            'owner.exists' => 'The owner must be an existing user',
            'owner.prohibited' => 'You cannot chose who the owner of the vehicle is',
            'type.exists' => 'The vehicle type must be already registered.',
            'plate.string' => 'The license plate must be a string',
            'plate.max' => 'The license plate cannot exceed 16 characters.',
            'plate.unique' => 'The license plate is already registered.',
        ];
    }
}
