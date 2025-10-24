<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\AdminOwnsData;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SuperAdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // daftar alias middleware
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'admin.owns' => \App\Http\Middleware\AdminOwnsData::class,
            'superadmin' => \App\Http\Middleware\SuperAdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
