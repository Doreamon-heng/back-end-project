<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Throwable;

abstract class Controller
{
    protected function handleException(Throwable $e, string $message = 'An unexpected error occurred', int $status = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => config('app.debug') ? $e->getMessage() : null,
        ], $status);
    }
}
