<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Aplikasi berada di belakang proxy TLS-terminating (Laravel Cloud &
        // reverse proxy lokal). Tanpa trust proxies, request terlihat sebagai
        // http:// sehingga route()/asset() menghasilkan URL absolut http:// di
        // halaman https:// — fetch AJAX (toggle bookmark perpustakaan, dsb.)
        // lalu diblokir browser sebagai mixed content. Menyetujui proxy membuat
        // skema/host mengikuti header X-Forwarded-* dari proxy.
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
