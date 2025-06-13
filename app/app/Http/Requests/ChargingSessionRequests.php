<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChargingSessionRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
    protected function hasValidToken(): bool
    {
        return $this->input('token') === $this->route('session')->token;
    }
}
