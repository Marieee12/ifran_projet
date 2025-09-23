<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::all();
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom_classe' => 'required|string|max:255',
            'id_niveau' => 'required|exists:niveaux_etude,id',
            'id_filiere' => 'required|exists:filieres,id',
            'annee_academique_id' => 'required|exists:annees_academiques,id'
        ]);

        Classe::create($validatedData);

        return redirect()->route('classes.index')->with('success', 'Classe créée avec succès');
    }

    public function show(Classe $classe)
    {
        return view('classes.show', compact('classe'));
    }

    public function edit(Classe $classe)
    {
        return view('classes.edit', compact('classe'));
    }

    public function update(Request $request, Classe $classe)
    {
        $validatedData = $request->validate([
            'nom_classe' => 'required|string|max:255',
            'id_niveau' => 'required|exists:niveaux_etude,id',
            'id_filiere' => 'required|exists:filieres,id',
            'annee_academique_id' => 'required|exists:annees_academiques,id'
        ]);

        $classe->update($validatedData);

        return redirect()->route('classes.index')->with('success', 'Classe mise à jour avec succès');
    }

    public function destroy(Classe $classe)
    {
        $classe->delete();
        return redirect()->route('classes.index')->with('success', 'Classe supprimée avec succès');
    }
}
