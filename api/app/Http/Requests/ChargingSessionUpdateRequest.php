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
        return $this->hasValidAPIKey() && $this->hasValidToken();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        \Log::info($this->input('current_battery_percent'));

        return [
            "current_battery_percent" => "required|integer|between:0,100",
            "token" => "required|string|size:64",
        ];
    }

    public function failedAuthorization()
    {
        if(!$this->hasValidAPIKey()) {
            throw new AuthorizationException("API Key is not valid");
        }

        if(!$this->hasValidToken()) {
            throw new AuthorizationException("Token is invalid");
        }

        parent::failedAuthorization();
    }
}
