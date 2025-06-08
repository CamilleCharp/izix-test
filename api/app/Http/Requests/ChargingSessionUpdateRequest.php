<?php

namespace App\Http\Requests;

use App\Models\ChargingSession;
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
        return [
            "current_battery_percent" => "required|integer|between:0,100",
            "token" => "required|string|size:64",
        ];
    }
}
