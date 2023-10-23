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
            'status_id' => $iRequest->insurance_request_status_id,
            'status' => $iRequest->status->name,
            'insurance_object_type_id' => $iRequest->insurance_object_type_id,
            'insurance_object_type_name' => $iRequest->irt->name,
            'comment' => $iRequest->comment,
        ];
    }
}