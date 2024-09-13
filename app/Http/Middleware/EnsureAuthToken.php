<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureAuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!$request->bearerToken()) {
            $response=[
                'meta' => [
                    'success' => false,
                    'code' => 401,
                    'message' => "No Bearer Token Found"
                ],
                'data'    => null
            ];
            return response()->json($response, 401);
        }

        if (!Auth::guard('sanctum')->check()) {
            $response=[
                'meta' => [
                    'success' => false,
                    'code' => 401,
                    'message' => "Unauthorized"
                ],
                'data'    => null
            ];
            return response()->json($response,401);
        }


        return $next($request);
    }
}