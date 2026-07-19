<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $roleName = null;

        if ($user->relationLoaded('role') || $user->relationLoaded('roles')) {
            $roleName = optional($user->role)->name;
        }

        if (!$roleName && $user->role_id) {
            $roleName = optional($user->role()->first())->name;
        }

        if (strtolower((string) $roleName) !== 'user' && strtolower((string) $roleName) !== 'User') {
            return response()->json([
                'message' => 'Access denied'
            ], 403);
        }

        return $next($request);
    }
}
