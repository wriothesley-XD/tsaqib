<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Cegah script jahat (XSS) - sesuaikan domain kalau pakai CDN/font eksternal
        $response->headers->set('Content-Security-Policy', 
    "default-src 'self'; ".
    "script-src 'self' 'unsafe-inline'; ".
    "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; ".
    "font-src 'self' https://fonts.gstatic.com; ".
    "img-src 'self' data: https:; ".
    "connect-src 'self'; ".
    "frame-ancestors 'self';"
);

        // Paksa selalu pakai HTTPS
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        // Kontrol info yang dikirim saat klik link keluar
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Matikan akses fitur browser yang gak dipakai
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}