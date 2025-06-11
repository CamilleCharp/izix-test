<?php

namespace App\Http\Requests;

use App\Enums\Current;
use App\Enums\Permissions;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StationTypeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo(Permissions::UPDATE_CHARGING_STATION_TYPE);
    }

    public function failedAuthorization() {
        if(!$this->user()->hasPermissionTo(Permissions::UPDATE_CHARGING_STATION_TYPE)) {
            throw new AuthenticationException("You do not have the permission to update a new charging station type");
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
            'name' => ['string', 'max:255'],
            'current' => [Rule::enum(Current::class)],
            'level' => ['bail', 'integer', 'exists:station_levels,level'],
            'power' => ['numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'The station type name must be a string.',
            'name.max' => 'The station type name may not be greater than 255 characters.',
            'current.enum' => 'The current type must be either AC or DC.',
            'level.integer' => 'The station level must be an integer.',
            'level.exists' => 'The selected station level does not exist.',
            'power.numeric' => 'The power output must be a number.',
        ];
    }
}
