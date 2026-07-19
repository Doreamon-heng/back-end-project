<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $roleName = null;

        if ($user->relationLoaded('role') || $user->relationLoaded('roles')) {
            $roleName = optional($user->role)->name;
        }

        if (!$roleName && $user->role_id) {
            $roleName = optional($user->role()->first())->name;
        }

        if (strtolower((string) $roleName) !== 'admin' && strtolower((string) $roleName) !== 'Admin') {
            return response()->json([
                'message' => 'Access denied'
            ], 403);
        }

        return $next($request);
    }
}