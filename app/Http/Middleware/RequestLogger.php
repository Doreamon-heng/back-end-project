<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestLogger
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Request received', [
            'method' => $request->method(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'query' => $request->query(),
        ]);

        $response = $next($request);
        $response->headers->set('X-Request-Logged', 'true');

        return $response;
    }
}
