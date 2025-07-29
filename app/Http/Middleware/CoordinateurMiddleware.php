<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coordinateur;

class CoordinateurMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Coordinateur::where('user_id', Auth::id())->exists()) {
            return redirect()->route('login')->with('error', 'Accès non autorisé.');
        }

        return $next($request);
    }
}
