<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class VehicleDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $canDeleteOwnVehicle = $this->user()->hasPermissionTo(Permissions::DELETE_VEHICLE);
        $canDeleteAllVehicles = $this->user()->hasPermissionTo(Permissions::DELETE_EXTERNAL_VEHICLE);

        if($canDeleteAllVehicles) return true;

        if($canDeleteOwnVehicle) {
            $isVehicleOwner = $this->route('vehicle')->owner()->firstOrFail()->is($this->user());
            if($isVehicleOwner) return true;
        }

        return false;
    }


    public function failedAuthorization() {
        if(!$this->user->hasPermissionTo(Permissions::DELETE_VEHICLE)) {
            throw new AuthorizationException("You do not have the permission to delete a vehicle.");
        }
        
        if(!$this->route('session')->vehicle()->first()->owner()->firstOrFail()->is($this->user())) {
            if(!$this->user->hasPermissionTo(Permissions::DELETE_EXTERNAL_VEHICLE)) {
                throw new AuthorizationException('You are not authorized to delete a vehicle other than yours');
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
            //
        ];
    }
}
