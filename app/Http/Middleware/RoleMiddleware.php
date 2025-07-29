<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->user();

        if (!$user || !$user->role) {
            return $this->unauthorized($request);
        }

        // Convertir le nom du rôle en minuscules pour la comparaison
        $userRole = strtolower($user->role->nom_role);
        $requiredRole = strtolower($role);

        // Log pour le débogage
        Log::info('Vérification du rôle:', [
            'utilisateur' => $user->nom_utilisateur,
            'role_utilisateur' => $userRole,
            'role_requis' => $requiredRole
        ]);

        if ($userRole !== $requiredRole) {
            Log::warning('Accès refusé - Rôle utilisateur: ' . $userRole . ', Rôle requis: ' . $requiredRole);
            return $this->unauthorized($request);
        }

        return $next($request);
    }

    private function unauthorized($request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }
        return redirect()->route('welcome')->with('error', 'Accès non autorisé.');
    }
}
