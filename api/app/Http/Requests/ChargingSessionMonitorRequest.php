<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Auth\Access\AuthorizationException;
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

    public function failedAuthorization() {
        if(!$this->user->hasPermissionTo(Permissions::VIEW_CHARGING_SESSIONS)) {
            throw new AuthorizationException("You do not have the permission to view charging sessions.");
        }
        
        if(!$this->route('session')->vehicle()->first()->owner()->firstOrFail()->is($this->user())) {
            if(!$this->user->hasPermissionTo(Permissions::VIEW_EXTERNAL_CHARGING_SESSIONS)) {
                throw new AuthorizationException('You are not authorized to view session other than yours');
            }
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
