<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Foundation\Http\FormRequest;

class StationTypeRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo(Permissions::REGISTER_CHARGING_STATION_TYPE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'current' => ['required', Rule::enum(Current::class)],
            'level' => ['bail', 'required', 'integer', 'exists:station_levels,level'],
            'power' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The station type name is required.',
            'name.string' => 'The station type name must be a string.',
            'name.max' => 'The station type name may not be greater than 255 characters.',
            'current.required' => 'The current type is required.',
            'current.enum' => 'The current type must be either AC or DC.',
            'level.required' => 'The station level is required.',
            'level.integer' => 'The station level must be an integer.',
            'level.exists' => 'The selected station level does not exist.',
            'power.required' => 'The power output is required.',
            'power.numeric' => 'The power output must be a number.',
        ];
    }
}
