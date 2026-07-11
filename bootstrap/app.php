<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registering your custom middleware aliases for Oppasabuy
        $middleware->alias([
            'verified.seller' => \App\Http\Middleware\EnsureSellerIsVerified::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            // Updated to match your specific middleware class name
            'ensure.video.intro' => \App\Http\Middleware\EnsureVideoIntroUploaded::class,
            
            // Added alias for checking if a seller application is still pending
            'seller.verified' => \App\Http\Middleware\CheckSellerStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();