<?php

namespace App\Http\Controllers;

use App\Models\NiveauEtude;
use Illuminate\Http\Request;

class NiveauEtudeController extends Controller
{
    public function index()
    {
        $niveaux = NiveauEtude::all();
        return view('dashboard.niveaux_etude.index', compact('niveaux'));
    }
    public function create()
    {
        return view('dashboard.niveaux_etude.create');
    }
    public function store(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:255']);
        NiveauEtude::create(['nom' => $request->nom]);
        return redirect()->route('niveaux_etude.index')->with('success', 'Niveau d\'étude ajouté !');
    }
    public function edit(NiveauEtude $niveauEtude)
    {
        return view('dashboard.niveaux_etude.edit', compact('niveauEtude'));
    }
    public function update(Request $request, NiveauEtude $niveauEtude)
    {
        $request->validate(['nom' => 'required|string|max:255']);
        $niveauEtude->update(['nom' => $request->nom]);
        return redirect()->route('niveaux_etude.index')->with('success', 'Niveau d\'étude modifié !');
    }
    public function destroy(NiveauEtude $niveauEtude)
    {
        $niveauEtude->delete();
        return redirect()->route('niveaux_etude.index')->with('success', 'Niveau d\'étude supprimé !');
    }
}
