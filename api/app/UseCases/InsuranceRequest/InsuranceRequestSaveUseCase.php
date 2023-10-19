<?php

namespace App\UseCases\InsuranceRequest;

use App\Http\Requests\InsuranceRequest\InsuranceRequestSaveRequest;
use App\Models\InsuranceRequest;

class InsuranceRequestSaveUseCase
{
    public function __invoke(InsuranceRequestSaveRequest $request): InsuranceRequest
    {
        if ($request->input('id')) {
            $insuranceRequest = InsuranceRequest::query()->find($request->input('id'));
        } else {
            $insuranceRequest = new InsuranceRequest();
        }

        $user = $request->getRequestUser();

        $insuranceRequest->user_id = $user->id;
        $insuranceRequest->insurance_request_status_id = $request->input('status_id');
        $insuranceRequest->insurance_object_type_id = $request->input('object_type_id');
        $insuranceRequest->comment = $request->input('comment');

        $insuranceRequest->save();

        $insuranceRequest->refresh();

        return $insuranceRequest;
    }
}