<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classe;
use App\Models\SeanceCours;
use App\Models\Etudiant;
use App\Models\Presence;
use App\Models\AnneeAcademique;
use App\Models\Role;
use App\Models\ParentModel;

class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord de l'administrateur.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Statistiques pour les cartes du tableau de bord
        $usersCount = User::count();

        // Récupérer l'année académique actuelle
        $anneeActuelle = AnneeAcademique::where('est_actuelle', true)->first();

        // Compter les classes actives de l'année en cours
        $classesCount = $anneeActuelle
            ? Classe::where('id_annee_academique', $anneeActuelle->id)->count()
            : 0;

        // Compter les séances de cours planifiées
        $coursCount = $anneeActuelle
            ? SeanceCours::join('classes', 'seances_cours.id_classe', '=', 'classes.id')
                ->whereDate('date_seance', '>=', now())
                ->where('classes.id_annee_academique', $anneeActuelle->id)
                ->count()
            : 0;

        // Compter les étudiants "droppés"
        $droppedStudentsCount = Etudiant::where('est_actif', false)->count();

        return view('dashboard.admin.dashboard', compact(
            'usersCount',
            'classesCount',
            'coursCount',
            'droppedStudentsCount'
        ));
    }

    /**
     * Affiche la liste des parents
     */
    public function parents()
    {
        $parents = ParentModel::with('user')->get();
        return view('dashboard.admin.parents', compact('parents'));
    }

    /**
     * Affiche les enfants d'un parent et permet d'en associer de nouveaux
     */
    public function parentEnfants(ParentModel $parent)
    {
        $parent->load(['user', 'etudiants.user', 'etudiants.classe']);
        $etudiants = Etudiant::with(['user', 'classe'])->get();

        return view('dashboard.admin.parent-enfants', compact('parent', 'etudiants'));
    }

    /**
     * Associe un enfant à un parent
     */
    public function associateEnfant(Request $request, ParentModel $parent)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id'
        ]);

        // Vérifier si l'association n'existe pas déjà
        if (!$parent->etudiants()->where('id_etudiant', $request->etudiant_id)->exists()) {
            $parent->etudiants()->attach($request->etudiant_id);
            return back()->with('success', 'Enfant associé avec succès !');
        }

        return back()->with('error', 'Cet enfant est déjà associé à ce parent.');
    }

    /**
     * Retire un enfant d'un parent
     */
    public function removeEnfant(ParentModel $parent, Etudiant $etudiant)
    {
        $parent->etudiants()->detach($etudiant->id);
        return back()->with('success', 'Association supprimée avec succès !');
    }
}
