<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Http\FormRequest;

class StationTypeDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo(Permissions::DELETE_CHARGING_STATION_TYPE);
    }

    public function failedAuthorization() {
        if(!$this->user()->hasPermissionTo(Permissions::DELETE_CHARGING_STATION_TYPE)) {
            throw new AuthenticationException("You do not have the permission to delete a charging station type");
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
