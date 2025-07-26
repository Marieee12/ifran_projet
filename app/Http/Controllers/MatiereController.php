<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index()
    {
        $matieres = Matiere::all();
        return view('dashboard.matieres.index', compact('matieres'));
    }
    public function create()
    {
        return view('dashboard.matieres.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nom_matiere' => 'required|string|max:255',
            'code_matiere' => 'required|string|max:20|unique:matieres,code_matiere'
        ]);
        Matiere::create([
            'nom_matiere' => $request->nom_matiere,
            'code_matiere' => $request->code_matiere
        ]);
        return redirect()->route('matieres.index')->with('success', 'Matière ajoutée !');
    }
    public function edit(Matiere $matiere)
    {
        return view('dashboard.matieres.edit', compact('matiere'));
    }
    public function update(Request $request, Matiere $matiere)
    {
        $request->validate([
            'nom_matiere' => 'required|string|max:255',
            'code_matiere' => 'required|string|max:20|unique:matieres,code_matiere,' . $matiere->id
        ]);
        $matiere->update([
            'nom_matiere' => $request->nom_matiere,
            'code_matiere' => $request->code_matiere
        ]);
        return redirect()->route('matieres.index')->with('success', 'Matière modifiée !');
    }
    public function destroy(Matiere $matiere)
    {
        $matiere->delete();
        return redirect()->route('matieres.index')->with('success', 'Matière supprimée !');
    }
}
