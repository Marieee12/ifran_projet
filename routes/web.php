<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoordinateurController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EmploiTempsController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\StatistiqueController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Route de test ULTRA SIMPLE pour étudiant
Route::get('/etudiant-simple', function () {
    return '<h1>✅ Dashboard Étudiant - Test Simple</h1><p>Si vous voyez ceci, le serveur Laravel fonctionne parfaitement !</p><p><a href="/login">Se connecter</a></p>';
});

// Route de test pour simuler la connexion
Route::get('/test-login', function () {
    $user = \App\Models\User::where('email', 'konejean@gmail.com')->first();
    if ($user) {
        Auth::login($user);
        return '<h1>✅ Test Connexion Réussie</h1>
               <p>Utilisateur connecté: ' . Auth::user()->email . '</p>
               <p>Role ID: ' . Auth::user()->role_id . '</p>
               <p><a href="/etudiant/dashboard">Aller au Dashboard</a></p>
               <p><a href="/test-dashboard-direct">Dashboard Direct (sans middleware)</a></p>
               <p><a href="/logout">Se déconnecter</a></p>';
    }
    return '<h1>❌ Utilisateur non trouvé</h1>';
});

// Route de test pour parent
Route::get('/test-parent-login', function () {
    $parent = \App\Models\User::where('email', 'parent.test@example.com')->first();
    if ($parent) {
        Auth::login($parent);
        return '<h1>✅ Parent connecté avec succès</h1>
               <p>Utilisateur: ' . Auth::user()->nom_utilisateur . '</p>
               <p>Email: ' . Auth::user()->email . '</p>
               <p>Role: ' . Auth::user()->role->nom_role . '</p>
               <p><a href="/parents/dashboard">Aller au Dashboard Parent</a></p>
               <p><a href="/logout">Se déconnecter</a></p>';
    }
    return '<h1>❌ Utilisateur parent non trouvé</h1>';
});

// Dashboard étudiant DIRECT sans middleware pour test
Route::get('/test-dashboard-direct', function () {
    $user = \App\Models\User::where('email', 'konejean@gmail.com')->with('etudiant')->first();

    if (!$user || !$user->etudiant) {
        return '<h1>❌ Étudiant non trouvé</h1>';
    }

    $etudiant = $user->etudiant;

    // Statistiques simples
    $totalSeances = \App\Models\SeanceCours::count();
    $totalAbsences = \App\Models\Absence::where('id_etudiant', $etudiant->id)->count();
    $totalPresences = \App\Models\Presence::where('id_etudiant', $etudiant->id)->count();

    return '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Dashboard Étudiant - ' . $user->prenom . ' ' . $user->nom . '</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
            .header { background: #e74c3c; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
            .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
            .stat-card { background: #3498db; color: white; padding: 20px; border-radius: 8px; text-align: center; }
            .nav { margin-bottom: 20px; }
            .nav a { background: #2ecc71; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>🎓 Dashboard Étudiant</h1>
                <h2>Bienvenue, ' . $user->prenom . ' ' . $user->nom . '</h2>
                <p>Email: ' . $user->email . ' | Matricule: ' . $etudiant->numero_etudiant . '</p>
            </div>

            <div class="nav">
                <a href="/test-login">🏠 Accueil</a>
                <a href="/etudiant/absences">📋 Mes Absences</a>
                <a href="/etudiant/emploi-temps">📅 Emploi du Temps</a>
                <a href="/logout">🚪 Déconnexion</a>
            </div>

            <div class="stats">
                <div class="stat-card">
                    <h3>📚 Total Séances</h3>
                    <h2>' . $totalSeances . '</h2>
                </div>
                <div class="stat-card">
                    <h3>❌ Mes Absences</h3>
                    <h2>' . $totalAbsences . '</h2>
                </div>
                <div class="stat-card">
                    <h3>✅ Mes Présences</h3>
                    <h2>' . $totalPresences . '</h2>
                </div>
                <div class="stat-card">
                    <h3>📊 Taux Présence</h3>
                    <h2>' . ($totalSeances > 0 ? round(($totalPresences / $totalSeances) * 100, 1) : 0) . '%</h2>
                </div>
            </div>

            <div style="background: #ecf0f1; padding: 20px; border-radius: 8px;">
                <h3>🔧 Informations de Debug</h3>
                <p><strong>User ID:</strong> ' . $user->id . '</p>
                <p><strong>Étudiant ID:</strong> ' . $etudiant->id . '</p>
                <p><strong>Role ID:</strong> ' . $user->role_id . '</p>
                <p><strong>Classe:</strong> ' . ($etudiant->classe ? $etudiant->classe->nom : 'Non assignée') . '</p>
            </div>
        </div>
    </body>
    </html>';
});

// Route de diagnostic pour comprendre les problèmes d'authentification
Route::get('/debug-auth', function () {
    $output = '<h1>🔍 Diagnostic d\'Authentification</h1>';

    // Check si utilisateur connecté
    if (Auth::check()) {
        $user = Auth::user();
        $output .= '<div style="background: #2ecc71; color: white; padding: 15px; margin: 10px 0; border-radius: 5px;">';
        $output .= '<h3>✅ Utilisateur Connecté</h3>';
        $output .= '<p><strong>ID:</strong> ' . $user->id . '</p>';
        $output .= '<p><strong>Email:</strong> ' . $user->email . '</p>';
        $output .= '<p><strong>Role ID:</strong> ' . $user->role_id . '</p>';
        $output .= '</div>';

        // Check relation étudiant
        if ($user->etudiant) {
            $output .= '<div style="background: #3498db; color: white; padding: 15px; margin: 10px 0; border-radius: 5px;">';
            $output .= '<h3>🎓 Relation Étudiant OK</h3>';
            $output .= '<p><strong>Étudiant ID:</strong> ' . $user->etudiant->id . '</p>';
            $output .= '<p><strong>Numéro:</strong> ' . $user->etudiant->numero_etudiant . '</p>';
            $output .= '</div>';
        } else {
            $output .= '<div style="background: #e74c3c; color: white; padding: 15px; margin: 10px 0; border-radius: 5px;">';
            $output .= '<h3>❌ Relation Étudiant Manquante</h3>';
            $output .= '</div>';
        }
    } else {
        $output .= '<div style="background: #e74c3c; color: white; padding: 15px; margin: 10px 0; border-radius: 5px;">';
        $output .= '<h3>❌ Aucun Utilisateur Connecté</h3>';
        $output .= '</div>';
    }

    // Session info
    $output .= '<div style="background: #9b59b6; color: white; padding: 15px; margin: 10px 0; border-radius: 5px;">';
    $output .= '<h3>🔐 Information Session</h3>';
    $output .= '<p><strong>Session ID:</strong> ' . session()->getId() . '</p>';
    $output .= '<p><strong>Token:</strong> ' . session()->token() . '</p>';
    $output .= '</div>';

    // Actions
    $output .= '<div style="margin: 20px 0;">';
    $output .= '<a href="/test-login" style="background: #2ecc71; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px;">🔑 Se connecter</a>';
    $output .= '<a href="/test-dashboard-direct" style="background: #3498db; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px;">📊 Dashboard Direct</a>';
    $output .= '<a href="/etudiant/dashboard" style="background: #e67e22; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">🎓 Dashboard Middleware</a>';
    $output .= '</div>';

    return $output;
});

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
    Route::get('/dashboard', 'App\Http\Controllers\EnseignantController@dashboard')->name('enseignant.dashboard');
    Route::get('/cours', 'App\Http\Controllers\EnseignantController@cours')->name('enseignant.cours');
    Route::get('/presences', 'App\Http\Controllers\EnseignantController@presences')->name('enseignant.presences');
    Route::get('/emploi-temps', 'App\Http\Controllers\EnseignantController@emploiTemps')->name('enseignant.emploi_temps');
    Route::get('/seance/{seance}/presences', [PresenceController::class, 'show'])->name('enseignant.seance.presences');
    Route::post('/seance/{seance}/presences', [PresenceController::class, 'store'])->name('enseignant.seance.presences.store');
    Route::get('/statistiques', [StatistiqueController::class, 'dashboard'])->name('enseignant.statistiques');
});

// Routes pour le coordinateur
Route::middleware(['auth', 'role:coordinateur pédagogique'])->prefix('coordinateur')->group(function () {
    Route::get('/', [CoordinateurController::class, 'index'])->name('coordinateur.index');
    Route::get('/dashboard', [CoordinateurController::class, 'index'])->name('coordinateur.dashboard');
    Route::get('/emploi-temps', [EmploiTempsController::class, 'index'])->name('coordinateur.emploi_temps');
    Route::post('/emploi-temps/seance', [EmploiTempsController::class, 'store'])->name('coordinateur.seance.store');
    Route::post('/emploi-temps/seance/{seance}/cancel', [EmploiTempsController::class, 'cancel'])->name('coordinateur.seance.cancel');
    Route::get('/seance/{seance}/presences', [PresenceController::class, 'show'])->name('coordinateur.seance.presences');
    Route::post('/seance/{seance}/presences', [PresenceController::class, 'store'])->name('coordinateur.seance.presences.store');
    Route::post('/presence/{presence}/justify', [PresenceController::class, 'justify'])->name('coordinateur.presence.justify');
    Route::get('/statistiques', [StatistiqueController::class, 'dashboard'])->name('coordinateur.statistiques');

    // Statistiques et graphiques détaillées
    Route::get('/statistics', [App\Http\Controllers\StatisticsController::class, 'index'])->name('coordinateur.statistics');
    Route::get('/statistics/data', [App\Http\Controllers\StatisticsController::class, 'getChartData'])->name('coordinateur.statistics.data');

    Route::get('/justifications', [CoordinateurController::class, 'justifications'])->name('coordinateur.justifications');
    Route::post('/justification/{justification}/valider', [CoordinateurController::class, 'validerJustification'])->name('coordinateur.justification.valider');
    Route::post('/justification/{justification}/refuser', [CoordinateurController::class, 'refuserJustification'])->name('coordinateur.justification.refuser');

    // Routes supplémentaires pour le coordinateur
    Route::get('/absences', [CoordinateurController::class, 'absences'])->name('coordinateur.absences');
    Route::get('/absences/statistiques', [CoordinateurController::class, 'statistiquesAbsences'])->name('coordinateur.absences.statistiques');
    Route::post('/absences/export', [CoordinateurController::class, 'exportAbsences'])->name('coordinateur.absences.export');
    Route::get('/cours/{cours}/presences', [CoordinateurController::class, 'voirPresences'])->name('coordinateur.cours.presences');
    Route::post('/cours/{cours}/presences', [CoordinateurController::class, 'marquerPresence'])->name('coordinateur.cours.presences.store');
    Route::get('/creer-cours', [CoordinateurController::class, 'creerCours'])->name('coordinateur.creer_cours');

    // Nouveau système d'absences complet
    Route::get('/absences/dashboard', [App\Http\Controllers\AbsenceController::class, 'dashboard'])->name('coordinateur.absences.dashboard');
    Route::get('/absences/marquer/{seance}', [App\Http\Controllers\AbsenceController::class, 'marquerAbsences'])->name('coordinateur.absences.marquer');
    Route::post('/absences/enregistrer/{seance}', [App\Http\Controllers\AbsenceController::class, 'enregistrerAbsences'])->name('coordinateur.absences.enregistrer');
    Route::get('/absences/justifications', [App\Http\Controllers\AbsenceController::class, 'justificationsEnAttente'])->name('coordinateur.absences.justifications');
    Route::post('/justifications/{justification}/traiter', [App\Http\Controllers\AbsenceController::class, 'traiterJustification'])->name('coordinateur.justifications.traiter');
    Route::get('/absences/rapport', [App\Http\Controllers\AbsenceController::class, 'rapport'])->name('coordinateur.absences.rapport');
    Route::get('/absences/export', [App\Http\Controllers\AbsenceController::class, 'export'])->name('coordinateur.absences.export.get');

    // Routes pour l'agenda/emploi du temps
    Route::get('/agenda', [EmploiTempsController::class, 'agenda'])->name('coordinateur.agenda');
    Route::get('/emploi-temps/agenda', [EmploiTempsController::class, 'agenda'])->name('coordinateur.emploi_temps.agenda');

    // Routes CRUD pour les cours
    Route::get('/cours', [CoordinateurController::class, 'listeCours'])->name('coordinateur.cours.index');
    Route::post('/cours', [CoordinateurController::class, 'storeCours'])->name('coordinateur.cours.store');
    Route::get('/cours/{cours}/edit', [CoordinateurController::class, 'editCours'])->name('coordinateur.cours.edit');
    Route::put('/cours/{cours}', [CoordinateurController::class, 'updateCours'])->name('coordinateur.cours.update');
    Route::delete('/cours/{cours}', [CoordinateurController::class, 'deleteCours'])->name('coordinateur.cours.delete');
});

Route::middleware(['auth', 'role:administrateur'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Gestion des parents et associations
    Route::get('/admin/parents', [AdminController::class, 'parents'])->name('admin.parents');
    Route::get('/admin/parents/{parent}/enfants', [AdminController::class, 'parentEnfants'])->name('admin.parent.enfants');
    Route::post('/admin/parents/{parent}/associate-enfant', [AdminController::class, 'associateEnfant'])->name('admin.parent.associate');
    Route::delete('/admin/parents/{parent}/enfants/{etudiant}', [AdminController::class, 'removeEnfant'])->name('admin.parent.remove-enfant');

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
    Route::resource('annees_academiques', App\Http\Controllers\AnneeAcademiqueController::class)
        ->parameters(['annees_academiques' => 'anneeAcademique']);

    // Niveaux d'étude
    Route::resource('niveaux_etude', App\Http\Controllers\NiveauEtudeController::class)
        ->parameters(['niveaux_etude' => 'niveauEtude']);

    // Enseignants
    Route::resource('enseignants', App\Http\Controllers\EnseignantController::class);

    // Étudiants
    Route::resource('etudiants', App\Http\Controllers\EtudiantController::class);

    // Coordinateurs
    Route::resource('coordinateurs', App\Http\Controllers\CoordinateurController::class);

});



Route::middleware(['auth', 'role:parent'])->prefix('parents')->group(function () {
    Route::get('/test', [App\Http\Controllers\ParentController::class, 'test'])->name('parent.test');
    Route::get('/dashboard', [App\Http\Controllers\ParentController::class, 'dashboard'])->name('parent.dashboard');
    Route::get('/enfants', [App\Http\Controllers\ParentController::class, 'enfants'])->name('parent.enfants');
    Route::get('/absences', [App\Http\Controllers\ParentController::class, 'absences'])->name('parent.absences');
    Route::post('/absences/{absence}/justifier', [App\Http\Controllers\ParentController::class, 'soumettreJustification'])->name('parent.justifier_absence');
    Route::get('/emploi-temps', [App\Http\Controllers\ParentController::class, 'emploiTemps'])->name('parent.emploi_temps');
    Route::get('/notifications', [App\Http\Controllers\ParentController::class, 'notifications'])->name('parent.notifications');
});

// Route de test simple sans middleware
Route::get('/test-etudiant', function() {
    return response()->json([
        'message' => 'Test simple sans middleware',
        'authenticated' => Auth::check(),
        'user' => Auth::user(),
        'timestamp' => now()
    ]);
});

// Route de test pour déboguer le middleware parent
Route::get('/test-parent-middleware', function() {
    return response()->json([
        'message' => 'Test middleware parent',
        'authenticated' => Auth::check(),
        'user' => Auth::user() ? [
            'id' => Auth::user()->id,
            'email' => Auth::user()->email,
            'role_id' => Auth::user()->role_id,
            'role_name' => Auth::user()->role ? Auth::user()->role->nom_role : 'No role'
        ] : null
    ]);
})->middleware(['auth', 'role:parent']);

// Route de test SANS middleware pour tester le ParentController
Route::get('/test-parent-controller', [App\Http\Controllers\ParentController::class, 'test'])->middleware(['auth']);

// Route de test avec prefix parents et middleware
Route::get('/parents/test-prefix', [App\Http\Controllers\ParentController::class, 'test'])->middleware(['auth', 'role:parent']);

// Route de debug pour voir les redirections
Route::get('/debug-auth', function() {
    $user = Auth::user();
    if (!$user) {
        return response()->json(['message' => 'Non authentifié']);
    }

    return response()->json([
        'user_id' => $user->id,
        'email' => $user->email,
        'role_id' => $user->role_id,
        'etudiant_exists' => \App\Models\Etudiant::where('user_id', $user->id)->exists(),
        'etudiant_data' => \App\Models\Etudiant::where('user_id', $user->id)->first()
    ]);
});

// Route de test direct SANS middleware
Route::get('/etudiant-test', [App\Http\Controllers\EtudiantController::class, 'dashboard'])->name('etudiant.test');

// Route pour forcer la connexion étudiant
Route::get('/force-login-etudiant', function() {
    $user = \App\Models\User::where('email', 'konejean@gmail.com')->first();
    if ($user) {
        Auth::login($user);
        return redirect('/etudiant/dashboard');
    }
    return 'Utilisateur non trouvé';
});

// Route pour connecter le nouvel étudiant
Route::get('/force-login-florian', function() {
    $user = \App\Models\User::where('email', 'bangaflorian@gmail.com')->first();
    if ($user) {
        Auth::login($user);
        return redirect('/etudiant/dashboard');
    }
    return 'Utilisateur florian non trouvé';
});

// Route de test du middleware
Route::get('/test-middleware-etudiant', function() {
    $user = Auth::user();
    if (!$user) {
        return 'Non connecté';
    }

    // Test du mapping des rôles comme dans CheckRole
    $roleMap = [
        'admin' => 1,
        'administrateur' => 1,
        'coordinateur' => 2,
        'coordinateur_pedagogique' => 2,
        'enseignant' => 3,
        'parent' => 5,
        'etudiant' => 4
    ];

    $requiredRoleId = $roleMap['etudiant'];

    return response()->json([
        'user_email' => $user->email,
        'user_role_id' => $user->role_id,
        'user_role_id_type' => gettype($user->role_id),
        'required_role_id' => $requiredRoleId,
        'required_role_id_type' => gettype($requiredRoleId),
        'comparison_result' => ($user->role_id === $requiredRoleId),
        'int_comparison_result' => ((int)$user->role_id === (int)$requiredRoleId)
    ]);
});

Route::middleware(['auth', 'role:Étudiant'])->prefix('etudiant')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\EtudiantController::class, 'dashboard'])->name('etudiant.dashboard');
    Route::get('/emploi-temps', [App\Http\Controllers\EtudiantController::class, 'emploiTemps'])->name('etudiant.emploi_temps');
    Route::get('/absences', [App\Http\Controllers\EtudiantController::class, 'mesAbsences'])->name('etudiant.absences');
    Route::post('/justifier-absence/{absence}', [App\Http\Controllers\EtudiantController::class, 'justifierAbsence'])->name('etudiant.justifier-absence');

    // Route de test
    Route::get('/test', function() {
        return response()->json([
            'message' => 'Route étudiant accessible',
            'user' => Auth::user(),
            'etudiant' => \App\Models\Etudiant::where('user_id', Auth::id())->first()
        ]);
    });
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
