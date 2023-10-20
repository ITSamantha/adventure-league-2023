<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    /**
     * @param array|LengthAwarePaginator|null $data
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function success(array|LengthAwarePaginator|null $data = [], string $message = 'success'): JsonResponse
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