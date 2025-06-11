<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Foundation\Http\FormRequest;

class VehicleDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo(Permissions::DELETE_VEHICLE) || $this->user->hasPermissionTo(Permissions::DELETE_EXTERNAL_VEHICLE);
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
