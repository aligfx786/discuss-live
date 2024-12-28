<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'role' => CheckRole::class,
        ]);

        // Alternatively, append middleware to the global stack
        // Appending Middleware: The append method adds the middleware to the global middleware stack, ensuring it runs on every request. Use this method if you want the middleware to be executed for all routes.
        // $middleware->append(CheckRole::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
