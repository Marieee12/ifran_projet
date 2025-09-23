<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Cours;
use App\Models\SeanceCours;
use App\Models\Etudiant;
use App\Models\Classe;
use App\Models\JustificationAbsence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsenceController extends Controller
{
    /**
     * Affichage du tableau de bord des absences pour le coordinateur
     */
    public function dashboard(Request $request)
    {
        // Filtres
        $dateDebut = $request->get('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->get('date_fin', now()->endOfMonth()->format('Y-m-d'));
        $classeId = $request->get('classe_id');
        $matiereId = $request->get('matiere_id');

        // Requête de base pour les absences
        $absencesQuery = Presence::where('statut_presence', 'Absent')
            ->with(['seanceCours.matiere', 'seanceCours.classe', 'etudiant.user'])
            ->whereBetween('date_saisie', [$dateDebut, $dateFin]);

        if ($classeId) {
            $absencesQuery->whereHas('seanceCours.classe', function($q) use ($classeId) {
                $q->where('id', $classeId);
            });
        }

        if ($matiereId) {
            $absencesQuery->whereHas('seanceCours.matiere', function($q) use ($matiereId) {
                $q->where('id', $matiereId);
            });
        }        $absences = $absencesQuery->orderBy('date_saisie', 'desc')->paginate(20);

        // Statistiques générales
        $stats = [
            'total_absences' => Presence::where('statut_presence', 'Absent')->count(),
            'absences_justifiees' => Presence::where('statut_presence', 'Absent')
                ->whereHas('justificationAbsence', function($q) {
                    $q->where('statut', 'validee');
                })->count(),
            'absences_non_justifiees' => Presence::where('statut_presence', 'Absent')
                ->whereDoesntHave('justificationAbsence', function($q) {
                    $q->where('statut', 'validee');
                })->count(),
            'absences_periode' => $absencesQuery->count()
        ];

        // Top 10 des étudiants les plus absents
        $etudiantsAbsents = Presence::select('id_etudiant', DB::raw('COUNT(*) as nombre_absences'))
            ->where('statut_presence', 'Absent')
            ->with('etudiant.user')
            ->groupBy('id_etudiant')
            ->orderBy('nombre_absences', 'desc')
            ->limit(10)
            ->get();        // Classes et matières pour les filtres
        $classes = Classe::all();
        $matieres = \App\Models\Matiere::all();

        // Séances de cours récentes pour marquer les présences
        $seancesRecentes = SeanceCours::with(['matiere', 'classe', 'enseignant.user'])
            ->where('date_seance', '>=', now()->subDays(7))
            ->where('date_seance', '<=', now()->addDays(1))
            ->orderBy('date_seance', 'desc')
            ->orderBy('heure_debut', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.coordinateur.absences.dashboard', compact(
            'absences', 'stats', 'etudiantsAbsents', 'classes', 'matieres',
            'dateDebut', 'dateFin', 'classeId', 'matiereId', 'seancesRecentes'
        ));
    }

    /**
     * Marquer les absences pour une séance de cours spécifique
     */
    public function marquerAbsences(Request $request, $seanceId)
    {
        $seance = SeanceCours::with(['classe', 'matiere', 'enseignant.user'])->findOrFail($seanceId);

        // Récupérer tous les étudiants de la classe
        $etudiants = Etudiant::whereHas('classe', function($q) use ($seance) {
            $q->where('id', $seance->id_classe);
        })->with('user')->get();

        // Récupérer les présences existantes pour cette séance
        $presencesExistantes = Presence::where('id_seance_cours', $seance->id)
            ->pluck('statut', 'id_etudiant')
            ->toArray();

        return view('dashboard.coordinateur.absences.marquer', compact('seance', 'etudiants', 'presencesExistantes'));
    }

    /**
     * Enregistrer les présences/absences
     */
    public function enregistrerAbsences(Request $request, $seanceId)
    {
        $seance = SeanceCours::findOrFail($seanceId);

        $request->validate([
            'presences' => 'required|array',
            'presences.*' => 'in:Present,Absent,Retard'
        ]);

        DB::transaction(function() use ($request, $seance) {
            foreach ($request->presences as $etudiantId => $statut) {
                Presence::updateOrCreate(
                    [
                        'id_seance_cours' => $seance->id,
                        'id_etudiant' => $etudiantId
                    ],
                    [
                        'statut_presence' => $statut,
                        'date_saisie' => now(),
                        'saisi_par_id_utilisateur' => Auth::id(),
                        'saisie_dans_delai' => true
                    ]
                );
            }
        });

        return redirect()->route('coordinateur.absences.dashboard')
            ->with('success', 'Présences enregistrées avec succès');
    }    /**
     * Justifier une absence
     */
    public function justifierAbsence(Request $request, Presence $presence)
    {
        $request->validate([
            'motif' => 'required|string|max:1000',
            'piece_justificative' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        DB::transaction(function() use ($request, $presence) {
            // Créer la justification
            $justificationData = [
                'id_presence' => $presence->id,
                'raison_justification' => $request->motif,
                'date_justification' => now(),
                'statut' => 'en_attente'
            ];

            // Gérer le fichier si présent
            if ($request->hasFile('piece_justificative')) {
                $path = $request->file('piece_justificative')->store('justifications', 'public');
                $justificationData['document_justificatif_url'] = $path;
            }

            JustificationAbsence::create($justificationData);

            // Marquer l'absence comme justifiée temporairement
            $presence->update(['justifie' => true]);
        });

        return back()->with('success', 'Justification soumise avec succès');
    }

    /**
     * Valider ou refuser une justification
     */
    public function traiterJustification(Request $request, JustificationAbsence $justification)
    {
        $request->validate([
            'action' => 'required|in:valider,refuser',
            'commentaire' => 'nullable|string|max:500'
        ]);

        DB::transaction(function() use ($request, $justification) {
            $statut = $request->action === 'valider' ? 'validee' : 'refusee';

            $justification->update([
                'statut' => $statut,
                'commentaire_coordinateur' => $request->commentaire,
                'traitee_par' => Auth::id(),
                'date_traitement' => now()
            ]);

            // Mettre à jour le statut de l'absence
            if ($justification->absence) {
                $justification->absence->update([
                    'justifie' => $request->action === 'valider'
                ]);
            }
        });

        $message = $request->action === 'valider' ? 'Justification validée' : 'Justification refusée';
        return back()->with('success', $message);
    }

    /**
     * Liste des justifications en attente
     */
    public function justificationsEnAttente()
    {
        $justifications = JustificationAbsence::where('statut', 'en_attente')
            ->with(['absence.cours.matiere', 'absence.etudiant.user', 'absence.cours.classe'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboard.coordinateur.absences.justifications', compact('justifications'));
    }

    /**
     * Rapport détaillé d'absences
     */
    public function rapport(Request $request)
    {
        $classeId = $request->get('classe_id');
        $dateDebut = $request->get('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->get('date_fin', now()->endOfMonth()->format('Y-m-d'));

        // Statistiques par classe
        $statistiquesClasses = Presence::select('cours.classe_id', DB::raw('COUNT(*) as total_absences'))
            ->join('cours', 'presences.cours_id', '=', 'cours.id')
            ->where('presences.present', false)
            ->whereBetween('presences.created_at', [$dateDebut, $dateFin])
            ->with('cours.classe')
            ->groupBy('cours.classe_id')
            ->get();

        // Statistiques par matière
        $statistiquesMatieres = Presence::select('cours.matiere_id', DB::raw('COUNT(*) as total_absences'))
            ->join('cours', 'presences.cours_id', '=', 'cours.id')
            ->where('presences.present', false)
            ->whereBetween('presences.created_at', [$dateDebut, $dateFin])
            ->with('cours.matiere')
            ->groupBy('cours.matiere_id')
            ->get();

        // Évolution des absences par semaine
        $absencesParSemaine = Presence::select(
                DB::raw('WEEK(created_at) as semaine'),
                DB::raw('YEAR(created_at) as annee'),
                DB::raw('COUNT(*) as nombre_absences')
            )
            ->where('present', false)
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->groupBy('annee', 'semaine')
            ->orderBy('annee', 'semaine')
            ->get();

        $classes = Classe::all();

        return view('dashboard.coordinateur.absences.rapport', compact(
            'statistiquesClasses', 'statistiquesMatieres', 'absencesParSemaine',
            'classes', 'dateDebut', 'dateFin', 'classeId'
        ));
    }

    /**
     * Export des données d'absences
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv'); // csv ou excel
        $dateDebut = $request->get('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->get('date_fin', now()->endOfMonth()->format('Y-m-d'));

        $absences = Presence::where('present', false)
            ->with(['cours.matiere', 'cours.classe', 'etudiant.user', 'justification'])
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->get();

        if ($format === 'csv') {
            return $this->exportCSV($absences, $dateDebut, $dateFin);
        }

        // TODO: Implémenter l'export Excel si besoin
        return $this->exportCSV($absences, $dateDebut, $dateFin);
    }

    /**
     * Export CSV des absences
     */
    private function exportCSV($absences, $dateDebut, $dateFin)
    {
        $filename = "absences_{$dateDebut}_au_{$dateFin}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($absences) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'Date', 'Étudiant', 'Classe', 'Matière', 'Enseignant',
                'Justifié', 'Motif', 'Statut Justification'
            ]);

            // Données
            foreach ($absences as $absence) {
                fputcsv($file, [
                    $absence->created_at->format('d/m/Y H:i'),
                    $absence->etudiant->user->prenom . ' ' . $absence->etudiant->user->nom,
                    $absence->cours->classe->nom_classe_complet ?? 'N/A',
                    $absence->cours->matiere->nom_matiere ?? 'N/A',
                    $absence->cours->enseignant->user->prenom . ' ' . $absence->cours->enseignant->user->nom ?? 'N/A',
                    $absence->justifie ? 'Oui' : 'Non',
                    $absence->justification->motif ?? '',
                    $absence->justification->statut ?? 'Non justifié'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
