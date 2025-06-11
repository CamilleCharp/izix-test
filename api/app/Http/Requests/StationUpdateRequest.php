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
            "spot" => "integer",
            "type_id" => "exists:station_types,id",
            "location_uuid" => "exists:locations,uuid",
        ];
    }
}
