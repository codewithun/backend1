<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // ... other code ...
    protected $middleware = [
        // ...
        \App\Http\Middleware\Cors::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            // ... other middleware ...
        ],

        'api' => [
            \Illuminate\Http\Middleware\HandleCors::class,  // Menambahkan middleware CORS
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    // ... rest of the file ...
}
