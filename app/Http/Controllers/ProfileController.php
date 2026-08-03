<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan form edit profile milik user yang login.
     */
    public function edit(Request $request): View
    {
        $userPosts = Post::where('user_id', $request->user()->id)->latest()->get();

        return view('profile.edit', [
            'user' => $request->user(),
            'userPosts' => $userPosts,
        ]);
    }

    /**
     * Update data profile (name, email, bio) milik user yang login.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],
            'bio' => ['nullable', 'string', 'max:500'],
        ]);

        $request->user()->fill($request->only('name', 'email', 'bio'));

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Hapus akun user yang login (Open Recruitment tetap jalan karena
     * user_id di registrations nullable, jadi ini tidak merusak data pendaftaran lama).
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
