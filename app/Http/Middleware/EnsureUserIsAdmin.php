<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi akses panel admin untuk role admin & guru — dipasang pada route
 * group admin-panel.
 *
 * Ini pertahanan PERTAMA di lapisan routing: seluruh group tertutup untuk
 * role lain walau sebuah method controller lupa memanggil checkAdmin().
 * checkAdmin() di AdminController tetap dipertahankan sebagai defense in
 * depth (lapisan kedua) — jangan dihapus.
 *
 * Role siswa/umum (+ legacy 'member') DIALIHKAN (bukan 403) sesuai blueprint
 * RBAC. Nilai role: admin | guru | siswa | umum ('member' lama = setara umum).
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            abort(401, 'Anda harus login terlebih dahulu.');
        }

        if (! in_array($user->role, ['admin', 'guru'], true)) {
            return redirect()->route('landing')
                ->with('error', 'Akses ditolak. Panel ini hanya untuk Admin & Guru.');
        }

        return $next($request);
    }
}
