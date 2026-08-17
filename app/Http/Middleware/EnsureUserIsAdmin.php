<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menolak akses selain admin — dipasang pada route group admin-panel.
 *
 * Ini pertahanan PERTAMA di lapisan routing: seluruh group tertutup untuk
 * non-admin walau sebuah method controller lupa memanggil checkAdmin().
 * checkAdmin() di AdminController tetap dipertahankan sebagai defense in
 * depth (lapisan kedua) — jangan dihapus.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            abort(401, 'Anda harus login terlebih dahulu.');
        }

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak. Halaman ini hanya dapat diakses oleh Admin.');
        }

        return $next($request);
    }
}
