<?php

namespace App\Http\Controllers;

use App\Models\AnneeAcademique;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Routing\Controller;

class AnneeAcademiqueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:administrateur');
    }

    public function index()
    {
        $annees = AnneeAcademique::orderBy('date_debut', 'desc')->get();
        return view('dashboard.annees_academiques.index', compact('annees'));
    }

    public function create()
    {
        return view('dashboard.annees_academiques.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_annee' => 'required|string|max:20|unique:annees_academiques',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'est_actuelle' => 'boolean'
        ]);

        // Si la nouvelle année est marquée comme actuelle, mettre toutes les autres à false
        if ($request->est_actuelle) {
            AnneeAcademique::where('est_actuelle', true)->update(['est_actuelle' => false]);
        }

        AnneeAcademique::create([
            'nom_annee' => $request->nom_annee,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'est_actuelle' => $request->est_actuelle ?? false
        ]);

        return redirect()->route('annees_academiques.index')
            ->with('success', 'Année académique ajoutée avec succès !');
    }

    public function edit(AnneeAcademique $anneeAcademique)
    {
        return view('dashboard.annees_academiques.edit', compact('anneeAcademique'));
    }

    public function update(Request $request, AnneeAcademique $anneeAcademique)
    {
        $request->validate([
            'nom_annee' => 'required|string|max:20|unique:annees_academiques,nom_annee,' . $anneeAcademique->id,
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'est_actuelle' => 'boolean'
        ]);

        // Si on marque cette année comme actuelle, désactiver les autres
        if ($request->est_actuelle && !$anneeAcademique->est_actuelle) {
            AnneeAcademique::where('est_actuelle', true)->update(['est_actuelle' => false]);
        }

        $anneeAcademique->update([
            'nom_annee' => $request->nom_annee,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'est_actuelle' => $request->est_actuelle ?? false
        ]);

        return redirect()->route('annees_academiques.index')
            ->with('success', 'Année académique mise à jour avec succès !');
    }

    public function destroy(AnneeAcademique $anneeAcademique)
    {
        if ($anneeAcademique->est_actuelle) {
            return redirect()->route('annees_academiques.index')
                ->with('error', 'Impossible de supprimer l\'année académique actuelle !');
        }

        // Vérifier s'il y a des données liées avant de supprimer
        if ($anneeAcademique->cours()->exists()) {
            return redirect()->route('annees_academiques.index')
                ->with('error', 'Impossible de supprimer cette année académique car elle contient des cours !');
        }

        $anneeAcademique->delete();

        return redirect()->route('annees_academiques.index')
            ->with('success', 'Année académique supprimée avec succès !');
    }
}
