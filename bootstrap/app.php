<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\AdminOwnsData;
use App\Http\Middleware\NoCache;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\CheckUserBlocked;

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
            'no.cache' => \App\Http\Middleware\NoCache::class,
            'superadmin' => \App\Http\Middleware\SuperAdminMiddleware::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\CheckUserBlocked::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
