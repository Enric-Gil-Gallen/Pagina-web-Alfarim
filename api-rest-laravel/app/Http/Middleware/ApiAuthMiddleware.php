<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\JwtAuth;

class ApiAuthMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token, true);
        
        
        if ($checkToken) {
            return $next($request);
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Error usuario no esta identificado'
            ];
        }

        return response()->json($data, $data['code']);
    }

}
