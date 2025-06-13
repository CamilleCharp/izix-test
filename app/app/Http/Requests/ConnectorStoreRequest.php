<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class ConnectorStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo(Permissions::REGISTER_CONNECTOR);
    }

    public function failedAuthorization() {
        if(!$this->user()->hasPermissionTo(Permissions::REGISTER_CONNECTOR)) {
            throw new AuthorizationException("You do not have the permission to create a new connector");
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
            "station_uuid" => "required|exists:stations,uuid",
            "type_id" => "required|exists:connector_types,id",
        ];
    }

    public function messages(): array
    {
        return [
            'station_uuid.required' => 'The connector station of origin id is required',
            'station_uuid.exists' => 'The connector station origin must be registered',
            'type_id.required' => 'The connector type iq required',
            'type_id.exists' => 'The connector type must exists',
        ];
    }
}
