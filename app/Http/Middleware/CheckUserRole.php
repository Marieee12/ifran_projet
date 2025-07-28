<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Gère une requête entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Le nom du rôle requis (ex: 'Admin', 'Enseignant')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Vérifie si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Récupère l'utilisateur authentifié
        $user = Auth::user();

        // Vérifie si l'utilisateur a son rôle et compare en ignorant la casse
        // en convertissant les deux chaînes en minuscules avant la comparaison.
        if ($user->role && strtolower($user->role->nom_role) === strtolower($role)) {
            return $next($request);
        }

        // Si l'utilisateur n'a pas le rôle requis, redirige ou renvoie une erreur 403
        return redirect('/')->with('error', 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires.');
    }
}
