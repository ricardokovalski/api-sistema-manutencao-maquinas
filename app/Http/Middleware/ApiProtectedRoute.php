<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class ApiProtectedRoute extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try
        {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'User not found'
                ], 404);
            }
        } catch (\Exception $exception) {

            if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'status' => 'Token is Invalid'
                ], 404);
            }

            if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'status' => 'Token is Expired'
                ], 404);
            }

            if ($exception instanceof Tymon\JWTAuth\Exceptions\JWTException) {
                return response()->json([
                    'status' => 'Authorization Token not found'
                ], 404);
            }
        }

        return $next($request);
    }
}
