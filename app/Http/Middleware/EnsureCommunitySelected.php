<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Paksa user memilih komunitas/role via /select-role sebelum boleh akses
 * halaman auth lain. User baru (selected_community masih null) di-redirect
 * ke select-role; yang sudah punya pilihan lolos normal.
 */
class EnsureCommunitySelected
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Escape hatch: profil (edit/hapus akun) & logout tetap bisa dijangkau
        // agar user tidak terkunci di halaman select-role selamanya.
        if ($user
            && ! $user->selected_community
            && ! $request->routeIs(['select-role', 'select-role.store', 'profile.edit', 'profile.destroy', 'logout'])) {
            return redirect()->route('select-role');
        }

        return $next($request);
    }
}
