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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $request->input('role_id'),
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id' => $request->input('role_id'),
        ]);

        return redirect()->route('dashboard.utilisateur.liste')->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('dashboard.utilisateur.liste')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
