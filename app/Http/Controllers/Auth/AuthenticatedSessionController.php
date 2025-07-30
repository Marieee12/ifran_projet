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
        switch ($user->role_id) {
            case 1: // Admin
                return redirect('/admin/dashboard');
            case 2: // Coordinateur
                return redirect('/coordinateur/dashboard');
            case 3: // Enseignant
                return redirect('/enseignants/dashboard');
            case 4: // Étudiant
                return redirect('/etudiant/dashboard');
            case 5: // Parent
                return redirect('/parents/dashboard');
            default:
                return redirect('/')->with('error', 'Rôle non reconnu');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
