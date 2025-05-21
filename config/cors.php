<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5174','http://localhost:5173',"http://127.0.0.1:8000","https://fla-dev-lms.vercel.app"],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['X-Requested-With', 'Content-Type', 'Accept', 'Authorization', 'X-CSRF-TOKEN'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];