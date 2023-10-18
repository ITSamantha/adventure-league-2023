<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * @param string $message
     * @param array $data
     *
     * @return JsonResponse
     */
    public static function success(array $data = [], string $message = 'success'): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], 200);
    }
}