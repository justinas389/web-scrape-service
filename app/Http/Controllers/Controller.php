<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * @param  mixed  $result
     * @param  null|string  $message
     *
     * @return JsonResponse
     */
    public function sendSuccess(mixed $result, string|null $message = null): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response);
    }

    /**
     * @param  mixed  $result
     * @param  null|string  $message
     *
     * @return JsonResponse
     */
    public function sendError(mixed $result, string|null $message = null, $status = 500): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        return response()->json($response, $status);
    }
}
