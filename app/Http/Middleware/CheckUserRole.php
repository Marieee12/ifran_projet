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

            // Vérifie si l'utilisateur a le rôle requis
            if ($user->role && $user->role->nom_role === $role) {
                return $next($request);
            }
            return redirect('/')->with('error', 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires.');
        }
    }
