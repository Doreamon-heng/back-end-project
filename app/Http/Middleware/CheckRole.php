<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }else{
                return response()->json(['message' => 'Authorized'], 200);
        }
        return $next($request);
    }

}
