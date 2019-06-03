<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class TokenAuthMiddleware extends BaseMiddleware
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
        try {
            JWTAuth::parseToken()->authenticate();
            return $next($request);
        } catch (TokenExpiredException $e) {
            try {
                $reToken = JWTAuth::refresh(JWTAuth::getToken());
                JWTAuth::setToken($reToken)->toUser();
                $request->headers->set('Authorization', 'Bearer ' . $reToken);
            } catch (JWTException $e) {
                return response()->json([
                    'result' => 1,
                    'message' => $e->getMessage()
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'result' => 1,
                'message' => $e->getMessage()
            ], 401);
        }
        return $this->setAuthenticationHeader($next($request), $reToken);
    }
}
