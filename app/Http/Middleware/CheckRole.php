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

        Log::info('Vérification du rôle', [
            'utilisateur' => $request->user()->email,
            'role_actuel' => $request->user()->role_id,
            'role_requis' => $requiredRoleId,
            'route' => $request->route()->getName(),
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
