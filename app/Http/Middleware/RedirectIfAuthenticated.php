<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): mixed
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirection basée sur le rôle
                if ($user->coordinateur) {
                    return redirect('/coordinateur/dashboard');
                } elseif ($user->enseignant) {
                    return redirect('/enseignants/dashboard');
                } elseif ($user->etudiant) {
                    return redirect('/etudiant/dashboard');
                } elseif ($user->parent) {
                    return redirect('/parent/dashboard');
                }

                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}
