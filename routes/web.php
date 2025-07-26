<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoordinateurController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
// });
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
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

    // Parents
    Route::resource('parents', App\Http\Controllers\ParentController::class);

    // Enseignants
    Route::resource('enseignants', App\Http\Controllers\EnseignantController::class);

    // Étudiants
    Route::resource('etudiants', App\Http\Controllers\EtudiantController::class);
});

// Route::middleware(['auth', 'role:Admin'])->prefix('dashboard')->group(function () {

// });

Route::middleware(['auth', 'role:Coordinateur'])->prefix('coordinateur')->group(function () {
    Route::get('/', [App\Http\Controllers\CoordinateurController::class, 'index'])->name('coordinateur.index');
    Route::get('/creer-cours', [App\Http\Controllers\CoordinateurController::class, 'creerCours'])->name('coordinateur.creer_cours');
    Route::get('/justifications', [App\Http\Controllers\CoordinateurController::class, 'justifications'])->name('coordinateur.justifications');
    Route::get('/absences', [App\Http\Controllers\CoordinateurController::class, 'absences'])->name('coordinateur.absences');
    Route::get('/classes', [App\Http\Controllers\CoordinateurController::class, 'classes'])->name('coordinateur.classes');
    Route::get('/emploi-temps', [App\Http\Controllers\CoordinateurController::class, 'emploiTemps'])->name('coordinateur.emploi_temps');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
