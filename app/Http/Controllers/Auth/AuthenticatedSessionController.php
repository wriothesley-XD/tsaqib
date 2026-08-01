<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Auto redirect: Jika belum pernah memilih komunitas, arahkan ke /select-role
        if ($user && !$user->selected_community) {
            return redirect()->route('select-role');
        }

        // Jika sudah memilih komunitas, langsung masuk ke /komunitas
        return redirect()->route('komunitas');
    }

    /**
     * Destroy an authenticated session.
     * Setelah logout -> REDIRECT KEMBALI KE LANDING FLOATING ISLAND (/)
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
