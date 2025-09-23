<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\Coordinateur;
use App\Models\ParentModel;
use App\Models\Classe;
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
        ], [
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre compte.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.'
        ]);

        try {
            // Vérifier si l'email existe déjà
            if (User::where('email', $request->email)->exists()) {
                return back()
                    ->withInput()
                    ->withErrors(['email' => 'Cette adresse email est déjà utilisée.']);
            }

            $user = User::create([
                'nom_utilisateur' => strtolower($request->prenom . '.' . $request->nom),
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'est_actif' => true,
                'date_creation' => now()
            ]);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Erreur lors de la création de l\'utilisateur. Veuillez réessayer.']);
        }

        // Créer automatiquement le profil selon le rôle
        $role = Role::find($request->role_id);
        if ($role) {
            switch ($role->nom_role) {
                case 'Étudiant':
                    // Récupérer la première classe disponible ou créer une classe par défaut
                    $premiereClasse = Classe::first();
                    if (!$premiereClasse) {
                        // Si aucune classe n'existe, en créer une par défaut
                        $premiereClasse = Classe::create([
                            'nom_classe_complet' => 'Classe par défaut',
                            'filiere_id' => 1, // Supposons qu'il existe une filière avec ID 1
                            'niveau_etude_id' => 1, // Supposons qu'il existe un niveau avec ID 1
                            'annee_academique_id' => 1 // Supposons qu'il existe une année académique avec ID 1
                        ]);
                    }

                    Etudiant::create([
                        'user_id' => $user->id,
                        'classe_id' => $premiereClasse->id,
                        'date_naissance' => '2000-01-01', // Date par défaut
                        'adresse' => 'Adresse à compléter',
                        'telephone' => '0000000000' // Téléphone par défaut
                    ]);
                    break;

                case 'Enseignant':
                    \App\Models\Enseignant::create([
                        'user_id' => $user->id,
                        'specialite' => 'À définir'
                    ]);
                    break;

                case 'Coordinateur Pédagogique':
                    \App\Models\Coordinateur::create([
                        'user_id' => $user->id,
                        'fonction' => 'Coordinateur Pédagogique'
                    ]);
                    break;

                case 'Parent':
                    \App\Models\ParentModel::create([
                        'user_id' => $user->id,
                        'telephone' => '0000000000'
                    ]);
                    break;
            }
        }

        event(new \Illuminate\Auth\Events\Registered($user));

        return redirect()->route('admin.dashboard')->with('success', 'Utilisateur créé avec succès.');
    }

    public function list()
    {
        $users = User::with('role')->get();
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
