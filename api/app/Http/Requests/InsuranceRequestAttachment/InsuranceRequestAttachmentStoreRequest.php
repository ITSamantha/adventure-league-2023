<?php

namespace App\Http\Requests\InsuranceRequestAttachment;


use App\Http\Requests\ApiRequest;
use App\Models\Role;

class InsuranceRequestAttachmentStoreRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {return true; //todo
        $user = $this->getRequestUser();

        return $user->hasRole(Role::user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'insurance_request_id' => 'required|integer|exists:insurance_requests,id',
            'insurance_object_file_type' => 'required|integer|exists:insurance_object_file_types,id',
            'links' => 'required_without:text|array',
            'links.*' => 'string',
            'text' => 'required_without:links|string',
            'is_last' => 'required|bool',
        ];
    }
}
