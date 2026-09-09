<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Cegah script jahat (XSS) - sesuaikan domain kalau pakai CDN/font eksternal.
        // style-src wajib memuat CDN ikon yang dipakai theme-head:
        // cdnjs (Font Awesome) + jsdelivr (Tabler Icons) — tanpa ini semua ikon
        // hilang dan tombol berbasis ikon (dropdown, edit/hapus, FAB) tampak mati.
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; ".
            "script-src 'self' 'unsafe-inline'; ".
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; ".
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; ".
            "img-src 'self' data: https:; ".
            "connect-src 'self'; ".
            // frame-src: domain yang BOLEH kita embed lewat <iframe> (beda dari
            // frame-ancestors di bawah, yang ngatur siapa boleh embed KITA).
            // Tanpa ini, frame-src ikut default-src 'self' → semua iframe ke Heyzine/
            // Google Drive diblokir browser sendiri (bukan diblokir Heyzine/Edge).
            // Tambah domain lain di sini kalau nanti pakai provider flipbook/PDF lain.
            "frame-src 'self' https://heyzine.com https://*.heyzine.com https://drive.google.com https://docs.google.com; ".
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
