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

    /**
     * @param string $message
     *
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'No telegram ID is present in headers'): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 403);
    }

    public static function notFound($message = 'Not found'): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 404);
    }
}