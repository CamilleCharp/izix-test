<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class ChargingSessionEndRequest extends ChargingSessionRequests
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->hasValidToken();
    }

    public function failedAuthorization() {
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
            //
        ];
    }
}
