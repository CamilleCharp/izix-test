<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class LocationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo(Permissions::UPDATE_LOCATION);
    }

    public function failedAuthorization() {
        if(!$this->user()->hasPermissionTo(Permissions::UPDATE_LOCATION)) {
            throw new AuthorizationException("You do not have the permission to update a location");
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
            "name"=> "string|max:255|unique:locations,name",
            "coordinates" => "array|size:2",
            "capacity" => "integer|min:1",
            "tenant" => "exists:tenants,uuid",
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'The location name must be a string.',
            'name.max' => 'The location name may not be greater than 255 characters.',
            'name.unique' => 'The location name must be unique',
            'coordinates.array' => 'The location coordinates must be an array composed of the latitude and the longitude',
            'coordinates.size' => 'The location coordinate must contain and only contain the latitude and longitude',
            'capacity.integer' => 'The location capacity must be a whole number',
            'capacity.min' => 'The location capacity cannot be less than 1',
            'tenant.exists' => 'The tenant id must point to a registered tenant',
        ];
    }
}
