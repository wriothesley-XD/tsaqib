<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\SecurityHeaders;

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

        // Alias middleware admin: dipasang di route group admin-panel (web.php)
        // sebagai lapisan pertama otorisasi; checkAdmin() di controller tetap
        // berjalan sebagai defense in depth.
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);

        // Tempelkan header keamanan (CSP, HSTS, dll) ke setiap response
        $middleware->append(SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();