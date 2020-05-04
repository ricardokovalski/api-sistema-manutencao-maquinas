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
                ], 401);
            }
        } catch (\Exception $exception) {

            if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'status' => 'Token is Invalid'
                ], 401);
            }

            if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'status' => 'Token is Expired'
                ], 401);
            }

            if ($exception instanceof Tymon\JWTAuth\Exceptions\JWTException) {
                return response()->json([
                    'status' => 'Authorization Token not found'
                ], 401);
            }
        }

        return $next($request);
    }
}
