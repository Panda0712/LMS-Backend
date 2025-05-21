<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);
        
        if ($response instanceof Response) {
            // Lấy allowed origins từ biến môi trường hoặc mặc định
            $allowedOrigins = env('SANCTUM_STATEFUL_DOMAINS', 'localhost:8080');
            $origins = explode(',', $allowedOrigins);
            
            // Lấy origin từ request
            $origin = $request->header('Origin');
            
            // Kiểm tra xem origin có trong danh sách cho phép không
            if ($origin && (in_array($origin, $origins) || in_array('*', $origins))) {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization, Accept');
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
                $response->headers->set('Access-Control-Max-Age', '86400');
            }
        }
        
        return $response;
    }
}