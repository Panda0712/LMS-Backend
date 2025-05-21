<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',  // Thêm dòng này
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // CORS handling is now built into Laravel 12
        // Đảm bảo middleware CORS luôn hoạt động
        $middleware->alias([
            'cors' => \App\Http\Middleware\Cors::class,
        ]);
        
        $middleware->group('api', [
            \App\Http\Middleware\Cors::class,
        ]);

        // Thêm middleware xử lý OPTIONS request
        $middleware->prepend(\App\Http\Middleware\HandleCorsOptions::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();