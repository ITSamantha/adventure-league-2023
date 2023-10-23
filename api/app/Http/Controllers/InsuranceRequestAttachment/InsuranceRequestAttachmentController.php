<?php

namespace App\Http\Controllers\InsuranceRequestAttachment;

use App\Facades\Transformer;
use App\Http\Requests\InsuranceRequestAttachment\InsuranceRequestAttachmentStoreRequest;
use App\Http\Responses\ApiResponse;
use App\Transformers\InsuranceRequestAttachmentTransformer;
use App\UseCases\InsuranceRequestAttachment\InsuranceRequestAttachmentStoreUseCase;
use Illuminate\Http\JsonResponse;

class InsuranceRequestAttachmentController
{
    public function store(
        InsuranceRequestAttachmentStoreRequest $request,
        InsuranceRequestAttachmentStoreUseCase $case
    ): JsonResponse
    {
        $data = $case($request);
        return response()->json($data);
//        if (!$data['success']) {
//            return Apiresponse::unprocessable($data['message'], $data['errors'] ?? []);
//        }

//        return ApiResponse::success(
//            Transformer::transform($data['data'], InsuranceRequestAttachmentTransformer::class)
//        );
    }
}