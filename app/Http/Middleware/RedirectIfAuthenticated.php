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

                // Redirection basée sur le role_id
                switch ($user->role_id) {
                    case 1: // Administrateur
                        return redirect('/admin/dashboard');
                    case 2: // Coordinateur
                        return redirect('/coordinateur/dashboard');
                    case 3: // Enseignant
                        return redirect('/enseignants/dashboard');
                    case 4: // Étudiant
                        return redirect('/etudiant/dashboard');
                    case 5: // Parent
                        return redirect('/parent/dashboard');
                    default:
                        return redirect('/dashboard');
                }
            }
        }

        return $next($request);
    }
}
