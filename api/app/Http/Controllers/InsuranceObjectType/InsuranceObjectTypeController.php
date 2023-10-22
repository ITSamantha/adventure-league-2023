<?php

namespace App\Http\Controllers\InsuranceObjectType;

use App\Facades\Transformer;
use App\Http\Requests\ApiRequest;
use App\Http\Responses\ApiResponse;
use App\Models\InsuranceObjectType;
use App\Transformers\InsuranceObjectTypeTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class InsuranceObjectTypeController
{
    public function index(ApiRequest $request): JsonResponse
    {
        /** @var Collection<InsuranceObjectType> $types */
        $types = InsuranceObjectType::query()->get();

        return ApiResponse::success(
            Transformer::transform($types, InsuranceObjectTypeTransformer::class)
        );
    }
}