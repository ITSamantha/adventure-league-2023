<?php

namespace App\Http\Requests\InsuranceObjectFileType;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class InsuranceObjectFileTypeShowRequest extends ApiRequest
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
            'insurance_object_id' => 'required|integer|exists:insurance_object_types,id',
        ];
    }
}
