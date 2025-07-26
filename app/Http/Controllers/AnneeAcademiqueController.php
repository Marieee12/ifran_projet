<?php

namespace App\Http\Controllers;

use App\Models\AnneeAcademique;
use Illuminate\Http\Request;

class AnneeAcademiqueController extends Controller
{
    public function index()
    {
        $annees = AnneeAcademique::all();
        return view('dashboard.annees_academiques.index', compact('annees'));
    }
    public function create()
    {
        return view('dashboard.annees_academiques.create');
    }
    public function store(Request $request)
    {
        $request->validate(['annee' => 'required|string|max:255']);
        AnneeAcademique::create(['annee' => $request->annee]);
        return redirect()->route('annees_academiques.index')->with('success', 'Année académique ajoutée !');
    }
    public function edit(AnneeAcademique $anneeAcademique)
    {
        return view('dashboard.annees_academiques.edit', compact('anneeAcademique'));
    }
    public function update(Request $request, AnneeAcademique $anneeAcademique)
    {
        $request->validate(['annee' => 'required|string|max:255']);
        $anneeAcademique->update(['annee' => $request->annee]);
        return redirect()->route('annees_academiques.index')->with('success', 'Année académique modifiée !');
    }
    public function destroy(AnneeAcademique $anneeAcademique)
    {
        $anneeAcademique->delete();
        return redirect()->route('annees_academiques.index')->with('success', 'Année académique supprimée !');
    }
}
