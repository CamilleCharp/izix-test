<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Foundation\Http\FormRequest;

class ChargingSessionMonitorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $canViewOwnSessions = $this->user()->hasPermissionTo(Permissions::VIEW_CHARGING_SESSIONS);
        $canViewAllSessions = $this->user()->hasPermissionTo(Permissions::VIEW_EXTERNAL_CHARGING_SESSIONS);
        
        if($canViewAllSessions) {
            return true;
        }

        if($canViewOwnSessions) {
            return $this->route('session')->vehicle()->first()->owner()->firstOrFail()->is($this->user());
        }

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
}
