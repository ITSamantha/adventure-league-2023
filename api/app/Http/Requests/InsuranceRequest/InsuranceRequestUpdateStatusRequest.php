<?php

namespace App\Http\Requests\InsuranceRequest;

use App\Http\Requests\ApiRequest;
use App\Models\Role;

class InsuranceRequestUpdateStatusRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->getRequestUser();

        return $user->hasRole([
            Role::admin,
            Role::moderator,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:insurance_requests,id',
            'status_id' => 'required|integer|exists:insurance_request_statuses,id',
        ];
    }
}
