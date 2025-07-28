<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoordinateurController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\ParentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// Route pour la page d'accueil (Bienvenue)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Routes protégées par l'authentification et le rôle 'Admin'
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route pour le dashboard administrateur
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Routes pour la gestion des utilisateurs (par l'administrateur)
    // Assurez-vous que DashboardUserController est bien le contrôleur qui gère ces routes
    Route::get('/utilisateur/create', [App\Http\Controllers\DashboardUserController::class, 'create'])->name('dashboard.utilisateur.create');
    Route::get('/utilisateur/liste', [App\Http\Controllers\DashboardUserController::class, 'list'])->name('dashboard.utilisateur.liste');
    Route::post('/utilisateur/store', [App\Http\Controllers\DashboardUserController::class, 'store'])->name('dashboard.utilisateur.store');
    Route::get('/utilisateur/{user}/edit', [App\Http\Controllers\DashboardUserController::class, 'edit'])->name('dashboard.utilisateur.edit');
    Route::put('/utilisateur/{user}', [App\Http\Controllers\DashboardUserController::class, 'update'])->name('dashboard.utilisateur.update');
    Route::delete('/utilisateur/{user}', [App\Http\Controllers\DashboardUserController::class, 'destroy'])->name('dashboard.utilisateur.destroy');

    // Routes de ressources pour la gestion des entités par l'administrateur
    Route::resource('filieres', App\Http\Controllers\FiliereController::class);
    Route::resource('matieres', App\Http\Controllers\MatiereController::class);
    Route::resource('annees_academiques', App\Http\Controllers\AnneeAcademiqueController::class);
    Route::resource('niveaux_etude', App\Http\Controllers\NiveauEtudeController::class);
    Route::resource('parents', App\Http\Controllers\ParentController::class);
    Route::resource('enseignants', App\Http\Controllers\EnseignantController::class); // Note: Ceci est pour la gestion des enseignants par l'admin
    Route::resource('etudiants', App\Http\Controllers\EtudiantController::class);
});

// Routes protégées par l'authentification et le rôle 'Coordinateur'
Route::middleware(['auth', 'role:coordinateur'])->prefix('coordinateur')->group(function () {
    Route::get('/', [CoordinateurController::class, 'index'])->name('coordinateur.index');
    Route::get('/creer-cours', [CoordinateurController::class, 'creerCours'])->name('coordinateur.creer_cours');
    Route::get('/justifications', [CoordinateurController::class, 'justifications'])->name('coordinateur.justifications');
    Route::get('/absences', [CoordinateurController::class, 'absences'])->name('coordinateur.absences');
    Route::get('/classes', [CoordinateurController::class, 'classes'])->name('coordinateur.classes');
    Route::get('/emploi-temps', [CoordinateurController::class, 'emploiTemps'])->name('coordinateur.emploi_temps');
});

// Routes protégées par l'authentification et le rôle 'Enseignant'
Route::middleware(['auth', 'role:enseignant'])->prefix('enseignant')->group(function () {
    // La route pour le dashboard enseignant
    Route::get('/dashboard', [EnseignantController::class, 'index'])->name('enseignant.dashboard');
    // Ajoutez ici d'autres routes spécifiques à l'enseignant, par exemple pour la saisie des présences
});

// Routes protégées par l'authentification et le rôle 'Parent'
Route::middleware(['auth', 'role:parent'])->prefix('parent')->group(function () {
    // La route pour le dashboard parent, pointant vers la méthode index
    Route::get('/dashboard', [ParentController::class, 'index'])->name('parent.dashboard');
});

// Redirection dashboard multi-rôles
// Cette route gère la redirection vers le bon dashboard après connexion
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role) {
        $userRoleName = strtolower($user->role->nom_role);
        if ($userRoleName === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($userRoleName === 'coordinateur') {
            return redirect()->route('coordinateur.index');
        } elseif ($userRoleName === 'enseignant') {
            return redirect()->route('enseignant.dashboard');
        } elseif ($userRoleName === 'parent'){
            return redirect()->route('parent.dashboard');
        }
    }
    // Redirection par défaut si le rôle n'est pas reconnu ou si l'utilisateur n'a pas de rôle
    return redirect('/');
})->name('dashboard');

// Routes de profil utilisateur par défaut (Breeze/UI)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

