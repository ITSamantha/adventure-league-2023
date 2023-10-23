<?php

namespace App\UseCases\InsuranceRequest;

use App\Http\Requests\InsuranceRequest\InsuranceRequestUpdateStatusRequest;
use App\Models\InsuranceRequest;

class InsuranceRequestUpdateStatusUseCase
{
    public function __invoke(InsuranceRequestUpdateStatusRequest $request)
    {
        /** @var InsuranceRequest $iRequest */
        $iRequest = InsuranceRequest::query()->find($request->input('id'));

        $iRequest->updateStatus($request->input('status_id'));
    }
}