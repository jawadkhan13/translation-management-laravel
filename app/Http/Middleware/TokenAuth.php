<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validToken = config('auth.api_token');

        $header = $request->header('Authorization');

        if (!$header || $header !== "Bearer {$validToken}") {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
