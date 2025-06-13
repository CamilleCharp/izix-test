<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TenantUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo(Permissions::UPDATE_TENANT);
    }

    public function failedAuthorization() {
        if(!$this->user()->hasPermissionTo(Permissions::UPDATE_TENANT)) {
            throw new AuthorizationException("You do not have the permission to update a tenant");
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
            "name"=> ["required", "string", "max:255", Rule::unique('tenants', 'name')],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The tenant name is required.',
            'name.string' => 'The tenant name must be a string.',
            'name.max' => 'The tenant name may not be greater than 255 characters.',
            'name.unique' => 'The tenant name has already been taken.',
        ];
    }
}
