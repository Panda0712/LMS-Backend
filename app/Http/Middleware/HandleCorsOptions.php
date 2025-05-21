<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleCorsOptions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Xử lý OPTIONS pre-flight requests
        if ($request->isMethod('OPTIONS')) {
            $allowedOrigins = env('SANCTUM_STATEFUL_DOMAINS', 'localhost:8080');
            $origins = explode(',', $allowedOrigins);
            $origin = $request->header('Origin');
            
            if ($origin && (in_array($origin, $origins) || in_array('*', $origins))) {
                $headers = [
                    'Access-Control-Allow-Origin' => $origin,
                    'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                    'Access-Control-Allow-Headers' => 'X-Requested-With, Content-Type, X-Token-Auth, Authorization, Accept',
                    'Access-Control-Allow-Credentials' => 'true',
                    'Access-Control-Max-Age' => '86400'
                ];
                
                return response('', 204, $headers);
            }
        }
        
        return $next($request);
    }
}