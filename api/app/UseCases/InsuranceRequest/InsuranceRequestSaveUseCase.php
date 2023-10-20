<?php

namespace App\UseCases\InsuranceRequest;

use App\Http\Requests\InsuranceRequest\InsuranceRequestSaveRequest;
use App\Models\InsuranceRequest;
use App\Models\InsuranceRequestStatus;

class InsuranceRequestSaveUseCase
{
    public function __invoke(InsuranceRequestSaveRequest $request): InsuranceRequest
    {
        $insuranceRequest = new InsuranceRequest();

        $user = $request->getRequestUser();

        $insuranceRequest->user_id = $user->id;
        $insuranceRequest->insurance_request_status_id = InsuranceRequestStatus::PENDING;
        $insuranceRequest->insurance_object_type_id = $request->input('object_type_id');
        $insuranceRequest->comment = $request->input('comment');

        $insuranceRequest->save();

        $insuranceRequest->refresh();

        return $insuranceRequest;
    }
}