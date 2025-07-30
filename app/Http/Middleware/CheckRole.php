<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user()) {
            Log::warning('Tentative d\'accès sans authentification');
            return redirect('/login');
        }

        // Mapping des rôles aux role_id
        $roleMap = [
            'admin' => 1,
            'administrateur' => 1,
            'coordinateur' => 2,
            'coordinateur_pedagogique' => 2,
            'enseignant' => 3,
            'parent' => 5,
            'etudiant' => 4
        ];

        $requiredRoleId = $roleMap[strtolower($role)] ?? null;

        // Debug explicite
        if ($role === 'parent') {
            Log::error('DEBUG PARENT MIDDLEWARE', [
                'role_param' => $role,
                'role_lowercase' => strtolower($role),
                'required_role_id' => $requiredRoleId,
                'user_role_id' => $request->user()->role_id,
                'user_role_id_type' => gettype($request->user()->role_id),
                'comparison' => $request->user()->role_id === 5,
                'strict_comparison' => (int)$request->user()->role_id === (int)$requiredRoleId
            ]);
        }

        Log::info('Vérification du rôle', [
            'utilisateur' => $request->user()->email,
            'role_actuel' => $request->user()->role_id,
            'role_requis' => $requiredRoleId,
            'role_param' => $role,
            'route' => $request->route() ? $request->route()->getName() : 'no-route',
            'uri' => $request->path()
        ]);

        if (!$requiredRoleId) {
            Log::error('Rôle non reconnu: ' . $role);
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        if ((int)$request->user()->role_id !== (int)$requiredRoleId) {
            Log::warning('Accès refusé - mauvais rôle', [
                'utilisateur' => $request->user()->email,
                'role_actuel' => $request->user()->role_id,
                'role_requis' => $requiredRoleId
            ]);
            return redirect('/')->with('error', 'Vous n\'avez pas les permissions nécessaires');
        }

        return $next($request);
    }
}
