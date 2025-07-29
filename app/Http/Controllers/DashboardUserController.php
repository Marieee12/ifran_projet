<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardUserController extends Controller
{
    public function create()
    {
        $roles = Role::all();
        return view('dashboard.create_user', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user = User::create([
            'nom_utilisateur' => strtolower($request->prenom . '.' . $request->nom),
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'est_actif' => true
        ]);

        event(new \Illuminate\Auth\Events\Registered($user));

        return redirect()->route('admin.dashboard')->with('success', 'Utilisateur créé avec succès.');
    }
    public function list()
    {
        $users =User::with('role')->get();
        return view('dashboard.list_users', compact('users'));
    }
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('dashboard.edit_user', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user->update([
            'nom_utilisateur' => strtolower($request->prenom . '.' . $request->nom),
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('dashboard.utilisateur.liste')->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('dashboard.utilisateur.liste')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
