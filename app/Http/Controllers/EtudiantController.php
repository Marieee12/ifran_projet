<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Etudiant;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EtudiantController extends Controller
{
    public function index()
    {
        $etudiants = Etudiant::with(['user', 'classe'])->get();
        return view('dashboard.etudiants.index', compact('etudiants'));
    }

    public function create()
    {
        $classes = Classe::all();
        return view('dashboard.etudiants.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'classe_id' => 'required|exists:classes,id',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
            'telephone' => 'required|string',
        ]);

                // Créer l'utilisateur
        $user = User::create([
            'nom_utilisateur' => strtolower($request->prenom . '.' . $request->nom),
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2, // ID du rôle étudiant
            'telephone' => $request->telephone,
            'est_actif' => true,
            'date_creation' => now()
        ]);

        // Créer l'étudiant
        $etudiant = Etudiant::create([
            'user_id' => $user->id,
            'classe_id' => $request->classe_id,
            'date_naissance' => $request->date_naissance,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone
        ]);

        return redirect()->route('etudiants.index')
            ->with('success', 'Étudiant créé avec succès.');
    }

    public function show(Etudiant $etudiant)
    {
        return view('dashboard.etudiants.show', compact('etudiant'));
    }

    public function edit(Etudiant $etudiant)
    {
        $classes = Classe::all();
        return view('dashboard.etudiants.edit', compact('etudiant', 'classes'));
    }

    public function update(Request $request, Etudiant $etudiant)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $etudiant->user_id,
            'classe_id' => 'required|exists:classes,id',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
            'telephone' => 'required|string',
        ]);

        // Mettre à jour l'utilisateur
        $etudiant->user->update([
            'nom_utilisateur' => strtolower($request->prenom . '.' . $request->nom),
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone
        ]);

        // Mettre à jour l'étudiant
        $etudiant->update([
            'classe_id' => $request->classe_id,
            'date_naissance' => $request->date_naissance,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone
        ]);

        return redirect()->route('etudiants.index')
            ->with('success', 'Étudiant mis à jour avec succès.');
    }

    public function destroy(Etudiant $etudiant)
    {
        // Supprimer l'utilisateur (et l'étudiant par cascade)
        $etudiant->user->delete();

        return redirect()->route('etudiants.index')
            ->with('success', 'Étudiant supprimé avec succès.');
    }
}
