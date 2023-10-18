<?php

namespace App\Http\Controllers\Auth;

use App\Facades\Transformer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\ApiResponse;
use App\Transformers\UserTransformer;
use App\UseCases\Auth\RegisterUseCase;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @param RegisterUseCase $case
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request, RegisterUseCase $case): JsonResponse
    {
        $user = $case($request);

        return ApiResponse::success(
            Transformer::transform($user, UserTransformer::class)
        );
    }
}