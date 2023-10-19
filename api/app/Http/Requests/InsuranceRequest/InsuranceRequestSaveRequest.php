<?php

namespace App\Http\Requests\InsuranceRequest;

use App\Http\Requests\ApiRequest;

class InsuranceRequestSaveRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !is_null($this->getRequestUser());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:insurance_requests,id',
            'status_id' => 'required|integer|exists:insurance_request_statuses,id',
            'object_type_id' => 'required|integer|exists:insurance_object_types,id',
            'comment' => 'required|string',
        ];
    }
}
