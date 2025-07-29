<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoordinateurController;
use App\Http\Controllers\EmploiTempsController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\StatistiqueController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // Si l'utilisateur est connecté, le rediriger directement vers son dashboard spécifique
    if (Auth::check()) {
        $user = Auth::user();
        if ($user && $user->role) {
            switch ($user->role_id) {
                case 1: // Admin
                    return redirect()->route('admin.dashboard');
                case 2: // Coordinateur
                    return redirect()->route('coordinateur.index');
                case 3: // Enseignant
                    return redirect()->route('enseignant.dashboard');
                case 4: // Étudiant
                    return redirect()->route('etudiant.dashboard');
                case 5: // Parent
                    return redirect()->route('parent.dashboard');
                default:
                    Auth::logout();
                    return view('welcome');
            }
        }
    }
    return view('welcome');
})->name('welcome');

// Route de test pour l'admin (à supprimer après tests)
Route::get('/test-admin', function() {
    $user = \App\Models\User::where('email', 'admin@ifran.com')->first();
    if ($user) {
        Auth::login($user);
        return redirect()->route('admin.dashboard');
    }
    return 'Admin non trouvé';
})->name('test.admin');

// Test direct du dashboard admin
Route::get('/test-admin-direct', function() {
    $user = \App\Models\User::where('email', 'admin@ifran.com')->first();
    if ($user) {
        Auth::login($user);
        // Appel direct au contrôleur
        $adminController = new \App\Http\Controllers\AdminController();
        return $adminController->dashboard();
    }
    return 'Admin non trouvé';
})->name('test.admin.direct');

// Test simple du dashboard admin sans middleware
Route::get('/admin-simple', function() {
    $user = \App\Models\User::where('email', 'admin@ifran.com')->first();
    if ($user) {
        Auth::login($user);
        return view('dashboard.admin.dashboard', [
            'usersCount' => \App\Models\User::count(),
            'classesCount' => 0,
            'coursCount' => 0,
            'droppedStudentsCount' => 0
        ]);
    }
    return 'Admin non trouvé';
})->name('admin.simple');

// Test pour le coordinateur
Route::get('/test-coordinateur', function() {
    $user = \App\Models\User::where('email', 'coordinateur@ifran.com')->first();
    if ($user) {
        Auth::login($user);
        return redirect()->route('coordinateur.index');
    }
    return 'Coordinateur non trouvé';
})->name('test.coordinateur');

// Route de debug pour vérifier l'utilisateur connecté
Route::get('/debug-user', function() {
    if (Auth::check()) {
        $user = Auth::user();
        return [
            'user_id' => $user->id,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'role_name' => $user->role ? $user->role->nom_role : 'Pas de rôle',
            'expected_redirect' => route('admin.dashboard')
        ];
    }
    return 'Utilisateur non connecté';
})->name('debug.user');

// Routes pour l'enseignant
Route::middleware(['auth', 'role:enseignant'])->prefix('enseignants')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\EnseignantController::class, 'dashboard'])->name('enseignant.dashboard');
    Route::get('/emploi-temps', [EmploiTempsController::class, 'index'])->name('enseignant.emploi_temps');
    Route::get('/seance/{seance}/presences', [PresenceController::class, 'show'])->name('enseignant.seance.presences');
    Route::post('/seance/{seance}/presences', [PresenceController::class, 'store'])->name('enseignant.seance.presences.store');
    Route::get('/statistiques', [StatistiqueController::class, 'dashboard'])->name('enseignant.statistiques');
});

// Routes pour le coordinateur
Route::middleware(['auth', 'role:coordinateur pédagogique'])->prefix('coordinateur')->group(function () {
    Route::get('/', [CoordinateurController::class, 'index'])->name('coordinateur.index');
    Route::get('/emploi-temps', [EmploiTempsController::class, 'index'])->name('coordinateur.emploi_temps');
    Route::post('/emploi-temps/seance', [EmploiTempsController::class, 'store'])->name('coordinateur.seance.store');
    Route::post('/emploi-temps/seance/{seance}/cancel', [EmploiTempsController::class, 'cancel'])->name('coordinateur.seance.cancel');
    Route::get('/seance/{seance}/presences', [PresenceController::class, 'show'])->name('coordinateur.seance.presences');
    Route::post('/seance/{seance}/presences', [PresenceController::class, 'store'])->name('coordinateur.seance.presences.store');
    Route::post('/presence/{presence}/justify', [PresenceController::class, 'justify'])->name('coordinateur.presence.justify');
    Route::get('/statistiques', [StatistiqueController::class, 'dashboard'])->name('coordinateur.statistiques');
    Route::get('/justifications', [CoordinateurController::class, 'justifications'])->name('coordinateur.justifications');
    Route::post('/justification/{justification}/valider', [CoordinateurController::class, 'validerJustification'])->name('coordinateur.justification.valider');
    Route::post('/justification/{justification}/refuser', [CoordinateurController::class, 'refuserJustification'])->name('coordinateur.justification.refuser');

    // Routes supplémentaires pour le coordinateur
    Route::get('/absences', [CoordinateurController::class, 'absences'])->name('coordinateur.absences');
    Route::get('/creer-cours', [CoordinateurController::class, 'creerCours'])->name('coordinateur.creer_cours');

    // Route pour l'agenda
    Route::get('/agenda', [EmploiTempsController::class, 'agenda'])->name('coordinateur.agenda');

    // Routes CRUD pour les cours
    Route::get('/cours', [CoordinateurController::class, 'listeCours'])->name('coordinateur.cours.index');
    Route::post('/cours', [CoordinateurController::class, 'storeCours'])->name('coordinateur.cours.store');
    Route::get('/cours/{cours}/edit', [CoordinateurController::class, 'editCours'])->name('coordinateur.cours.edit');
    Route::put('/cours/{cours}', [CoordinateurController::class, 'updateCours'])->name('coordinateur.cours.update');
    Route::delete('/cours/{cours}', [CoordinateurController::class, 'deleteCours'])->name('coordinateur.cours.delete');
});

Route::middleware(['auth', 'role:administrateur'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Autres routes admin existantes
    Route::get('/utilisateur/create', [App\Http\Controllers\DashboardUserController::class, 'create'])->name('dashboard.utilisateur.create');
    Route::get('/utilisateur/liste', [App\Http\Controllers\DashboardUserController::class, 'list'])->name('dashboard.utilisateur.liste');
    Route::post('/utilisateur/store', [App\Http\Controllers\DashboardUserController::class, 'store'])->name('dashboard.utilisateur.store');
    Route::get('/utilisateur/{user}/edit', [App\Http\Controllers\DashboardUserController::class, 'edit'])->name('dashboard.utilisateur.edit');
    Route::put('/utilisateur/{user}', [App\Http\Controllers\DashboardUserController::class, 'update'])->name('dashboard.utilisateur.update');
    Route::delete('/utilisateur/{user}', [App\Http\Controllers\DashboardUserController::class, 'destroy'])->name('dashboard.utilisateur.destroy');

    // Filières
    Route::resource('filieres', App\Http\Controllers\FiliereController::class);

    // Matières
    Route::resource('matieres', App\Http\Controllers\MatiereController::class);

    // Années académiques
    Route::resource('annees_academiques', App\Http\Controllers\AnneeAcademiqueController::class);

    // Niveaux d'étude
    Route::resource('niveaux_etude', App\Http\Controllers\NiveauEtudeController::class);

    // Enseignants
    Route::resource('enseignants', App\Http\Controllers\EnseignantController::class);

    // Étudiants
    Route::resource('etudiants', App\Http\Controllers\EtudiantController::class);

    // Coordinateurs
    Route::resource('coordinateurs', App\Http\Controllers\CoordinateurController::class);

    // Parents
    Route::resource('parents', App\Http\Controllers\ParentController::class);
});



// Routes pour l'enseignant

// Route::middleware(['auth', 'role:Admin'])->prefix('dashboard')->group(function () {

// });

// Routes déplacées plus haut

Route::middleware(['auth', 'role:parent'])->prefix('parents')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ParentController::class, 'dashboard'])->name('parent.dashboard');
    Route::get('/emploi-temps', [EmploiTempsController::class, 'index'])->name('parent.emploi_temps');
    Route::get('/absences', [PresenceController::class, 'showAbsences'])->name('parent.absences');
});

Route::middleware(['auth', 'role:étudiant'])->prefix('etudiants')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\EtudiantController::class, 'dashboard'])->name('etudiant.dashboard');
    Route::get('/emploi-temps', [EmploiTempsController::class, 'index'])->name('etudiant.emploi_temps');
    Route::get('/absences', [PresenceController::class, 'showAbsences'])->name('etudiant.absences');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
