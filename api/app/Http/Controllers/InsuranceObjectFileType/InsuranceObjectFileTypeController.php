<?php

namespace App\Http\Controllers\InsuranceObjectFileType;

use App\Facades\Transformer;
use App\Http\Controllers\Controller;
use App\Http\Requests\InsuranceObjectFileType\InsuranceObjectFileTypeShowRequest;
use App\Http\Responses\ApiResponse;
use App\Transformers\InsuranceObjectFileTypeTransformer;
use App\UseCases\InsuranceObjectFileType\InsuranceObjectFileTypeShowUseCase;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InsuranceObjectFileTypeController extends Controller
{
    public function getByInsuranceObjectType(
        InsuranceObjectFileTypeShowRequest $request,
        InsuranceObjectFileTypeShowUseCase $case
    ): JsonResponse
    {
        try {
            $ioft = $case($request);
        } catch (NotFoundHttpException $e) {
            return ApiResponse::notFound();
        }

        return ApiResponse::success(
            Transformer::transform($ioft, InsuranceObjectFileTypeTransformer::class)
        );
    }
}