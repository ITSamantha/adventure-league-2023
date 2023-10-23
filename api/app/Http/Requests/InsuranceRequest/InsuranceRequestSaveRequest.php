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
            'object_type_id' => 'required|integer|exists:insurance_object_types,id',
            'comment' => 'nullable|string',
        ];
    }
}
