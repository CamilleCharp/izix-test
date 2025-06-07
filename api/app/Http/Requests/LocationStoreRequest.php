<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Foundation\Http\FormRequest;

class LocationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo(Permissions::REGISTER_LOCATION);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name"=> "required|string|max:255|unique:locations,name",
            "coordinate" => "required|array|size:2",
            "capacity" => "required|integer|min:1",
            "tenant" => "required|exists:tenants,uuid",
        ];
    }
}
