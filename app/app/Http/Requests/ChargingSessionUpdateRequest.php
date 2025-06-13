<?php

namespace App\Http\Requests;

use App\Models\ChargingSession;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class ChargingSessionUpdateRequest extends ChargingSessionRequests
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->hasValidToken();
    }

    public function failedAuthorization() {
        if(!$this->hasValidAPIKey()) {
            throw new AuthorizationException("API Key is invalid.");
        }

        if($this->hasValidToken()) {
            throw new AuthorizationException("The given update token is invalid");
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
            "current_battery_percent" => "required|numeric|between:0,100",
            "token" => "required|string|size:64",
        ];
    }

    public function messages(): array
    {
        return [
            'current_battery_percent.required' => 'The current battery (in %) is required',
            'current_battery_percent.numeric' => 'The current battery (in %) must be a number',
            'current_battery_percent.between' => 'The battery cannot go below 0% or 100%',
            'token.required' => 'An update token is required for this operation',
            'token.string' => 'The token must be a string',
            'token.size' => 'The token must be a string consisting of 64 letters' ,
        ];
    }


}
