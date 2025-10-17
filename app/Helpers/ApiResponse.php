<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Return a success response with i18n message
     */
    public static function success(string $messageKey, array $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'message' => [
                'key' => $messageKey,
                'text' => __("messages.{$messageKey}")
            ],
            'status' => 'success',
            'data' => $data
        ], $statusCode);
    }

    /**
     * Return an error response with i18n message
     */
    public static function error(string $messageKey, array $data = [], int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'message' => [
                'key' => $messageKey,
                'text' => __("messages.{$messageKey}")
            ],
            'status' => 'error',
            'data' => $data
        ], $statusCode);
    }

    /**
     * Return a validation error response
     */
    public static function validationError(array $errors, string $messageKey = 'validation.failed'): JsonResponse
    {
        return response()->json([
            'message' => [
                'key' => $messageKey,
                'text' => __("messages.{$messageKey}")
            ],
            'status' => 'error',
            'errors' => $errors
        ], 422);
    }

    /**
     * Return a simple boolean response for admin checks
     */
    public static function boolean(bool $value, string $messageKey): JsonResponse
    {
        return response()->json([
            'result' => $value,
            'message' => [
                'key' => $messageKey,
                'text' => __("messages.{$messageKey}")
            ]
        ], 200);
    }
}
