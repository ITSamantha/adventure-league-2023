<?php

namespace App\Transformers;

use App\Models\InsuranceRequest;

class InsuranceRequestTransformer extends AbstractTransformer
{
    public function transform(InsuranceRequest $iRequest): array
    {
        return [
            'id' => $iRequest->id,
            'user_id' => $iRequest->user_id,
            'insurance_request_status_id' => $iRequest->insurance_request_status_id,
            'insurance_object_type_id' => $iRequest->insurance_object_type_id,
            'comment' => $iRequest->comment,
        ];
    }
}