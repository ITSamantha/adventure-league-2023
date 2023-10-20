<?php

namespace App\Http\Controllers\InsuranceRequest;

use App\Facades\Transformer;
use App\Http\Requests\InsuranceRequest\InsuranceRequestIndexRequest;
use App\Http\Requests\InsuranceRequest\InsuranceRequestSaveRequest;
use App\Http\Requests\InsuranceRequest\InsuranceRequestShowRequest;
use App\Http\Requests\InsuranceRequest\InsuranceRequestUpdateStatusRequest;
use App\Http\Responses\ApiResponse;
use App\Transformers\InsuranceRequestTransformer;
use App\UseCases\InsuranceRequest\InsuranceRequestIndexUseCase;
use App\UseCases\InsuranceRequest\InsuranceRequestSaveUseCase;
use App\UseCases\InsuranceRequest\InsuranceRequestShowUseCase;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InsuranceRequestController
{
    public function index(InsuranceRequestIndexRequest $request, InsuranceRequestIndexUseCase $case): JsonResponse
    {
        $iRequests = $case($request);

        return ApiResponse::success(
            Transformer::transform($iRequests, InsuranceRequestTransformer::class)
        );
    }

    public function show(InsuranceRequestShowRequest $request, InsuranceRequestShowUseCase $case): JsonResponse
    {
        try {
            $iRequest = $case($request);
        } catch (NotFoundHttpException $e) {
            return ApiResponse::notFound();
        }

        return ApiResponse::success(
            Transformer::transform($iRequest, InsuranceRequestTransformer::class)
        );
    }

    public function store(InsuranceRequestSaveRequest $request, InsuranceRequestSaveUseCase $case): JsonResponse
    {
        $data = $case($request);

        return ApiResponse::success(
            Transformer::transform($data, InsuranceRequestTransformer::class)
        );
    }

    public function updateStatus(InsuranceRequestUpdateStatusRequest $request): JsonResponse
    {
        $case($request);

        return ApiResponse::success([
            'success' => true,
        ]);
    }
}