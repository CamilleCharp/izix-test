<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo(Permissions::UPDATE_VEHICLE);
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
}
