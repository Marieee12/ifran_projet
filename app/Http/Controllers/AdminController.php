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
}
