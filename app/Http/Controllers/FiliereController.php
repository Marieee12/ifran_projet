<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    public function index()
    {
        $filieres = Filiere::all();
        return view('dashboard.filieres.index', compact('filieres'));
    }
    public function create()
    {
        return view('dashboard.filieres.create');
    }
    public function store(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:255']);
        Filiere::create(['nom' => $request->nom]);
        return redirect()->route('filieres.index')->with('success', 'Filière ajoutée !');
    }
    public function edit(Filiere $filiere)
    {
        return view('dashboard.filieres.edit', compact('filiere'));
    }
    public function update(Request $request, Filiere $filiere)
    {
        $request->validate(['nom' => 'required|string|max:255']);
        $filiere->update(['nom' => $request->nom]);
        return redirect()->route('filieres.index')->with('success', 'Filière modifiée !');
    }
    public function destroy(Filiere $filiere)
    {
        $filiere->delete();
        return redirect()->route('filieres.index')->with('success', 'Filière supprimée !');
    }
}
