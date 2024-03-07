<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Organisateur;
use App\Models\Client;
use App\Models\Admin;

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

        // Retrieve the authenticated user
        $user = $request->user();

        // Check if the user is a Organisateur
        $Organisateur = Organisateur::where('user_id', $user->id)->first();
        if ($driver) {
            // Redirect if the user is a Organisateur
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Check if the user is a client
        $client = Client::where('user_id', $user->id)->first();
        if ($client) {
            // Redirect if the user is a client
            return redirect()->intended(RouteServiceProvider::Client);
        }
        $admin = ADMIN::where('user_id', $user->id)->first();
        if ($admin) {
            // Redirect if the user is a ADMIN
            return redirect()->intended(RouteServiceProvider::ADMIN);
        }
        return redirect()->route('/');
 }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}