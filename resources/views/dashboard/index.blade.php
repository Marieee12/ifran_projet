{{-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFRAN TRACK - Dashboard Admin</title>
         @vite(['resources/css/app.css', 'resources/js/app.js'])
         <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head> --}}

@extends('layouts.app')
@section('content')
<div class="bg-gray-100 antialiased flex h-screen">
    <aside class="w-64 bg-gray-800 text-gray-200 flex flex-col rounded-tr-xl rounded-br-xl shadow-lg">
        <div class="p-6 text-2xl font-bold text-white border-b border-gray-700">
            IFRAN TRACK
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <div class="text-xs font-semibold uppercase text-gray-400 mb-4">Menu Principal</div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg bg-gray-700 text-white font-semibold shadow-md">
                <i class="fas fa-tachometer-alt mr-3"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('dashboard.utilisateur.liste') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-users mr-3"></i>
                <span>Liste des Utilisateurs</span>
            </a>
            <a href="{{ route('dashboard.utilisateur.create') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-user-plus mr-3"></i>
                <span>Ajouter Utilisateur</span>
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-user-tag mr-3"></i>
                <span>Gestion Rôles</span>
            </a>
            <a href="{{ route('annees_academiques.create') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-calendar-alt mr-3"></i>
                <span>Gestion Années Académiques</span>
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-graduation-cap mr-3"></i>
                <span>Gestion Niveaux d'Étude</span>
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-sitemap mr-3"></i>
                <span>Gestion Filières</span>
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-book mr-3"></i>
                <span>Gestion Matières</span>
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-chart-bar mr-3"></i>
                <span>Rapports Globaux</span>
            </a>

            <div class="text-xs font-semibold uppercase text-gray-400 mt-6 mb-4 pt-4 border-t border-gray-700">Paramètres</div>
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-user-circle mr-3"></i>
                <span>Mon Profil</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200 w-full text-left">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </nav>
        <div class="p-4 text-center text-sm text-gray-500 border-t border-gray-700">
            &copy; 2025 IFRAN TRACK. Tous droits réservés.
        </div>
    </aside>
    <main class="flex-1 p-8 overflow-y-auto">


            <header class="flex items-center justify-between bg-white p-6 rounded-xl shadow-md mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Tableau de Bord Administrateur</h1>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Rechercher..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button class="text-gray-600 hover:text-blue-500 transition-colors duration-200">
                        <i class="fas fa-bell text-2xl"></i>
                    </button>
                    <div class="flex items-center space-x-2">
                        <img src="https://placehold.co/40x40/cccccc/ffffff?text=AD" alt="Avatar Admin" class="w-10 h-10 rounded-full border-2 border-blue-500">
                        <span class="font-semibold text-gray-700">Admin</span>
                        <i class="fas fa-chevron-down text-gray-500 text-sm"></i>
                    </div>
                </div>
            </header>

            <!-- Section1-->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Vue d'Ensemble Rapide</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Carte1 Utilisateurs Totaux -->
                    <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-105">
                        <div>
                            <p class="text-gray-500 text-sm">Utilisateurs Totaux</p>
                            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $totalUsers }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                    </div>
                    <!-- Carte2 Classes Actives -->
                    <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-105">
                        <div>
                            <p class="text-gray-500 text-sm">Classes Actives</p>
                            <p class="text-3xl font-bold text-green-600 mt-1">{{ $activeClasses }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full text-green-600">
                            <i class="fas fa-chalkboard text-2xl"></i>
                        </div>
                    </div>
                    <!-- Carte3 Séances de Cours Planifiées -->
                    <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-105">
                        <div>
                            <p class="text-gray-500 text-sm">Séances de Cours Planifiées</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $plannedSessions }}</p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full text-yellow-600">
                            <i class="fas fa-calendar-check text-2xl"></i>
                        </div>
                    </div>
                    <!-- Carte4 Étudiants "Droppés" Total -->
                    <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between transition-transform duration-200 hover:scale-105">
                        <div>
                            <p class="text-gray-500 text-sm">Étudiants "Droppés" (Total)</p>
                            <p class="text-3xl font-bold text-red-600 mt-1">{{ $droppedStudents }}</p>
                        </div>
                        <div class="bg-red-100 p-3 rounded-full text-red-600">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section2 Accès Rapide aux Gestions -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Accès Rapide aux Gestions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <a href="{{route('dashboard.utilisateur.create')}}">
                        <button class="bg-blue-500 text-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center text-center transition-colors duration-200 hover:bg-blue-600 hover:shadow-lg">
                            <i class="fas fa-user-plus text-4xl mb-3"></i>
                            <span class="font-semibold text-lg">Ajouter un Nouvel Utilisateur</span>
                        </button>
                    </a>
                    <button class="bg-purple-500 text-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center text-center transition-colors duration-200 hover:bg-purple-600 hover:shadow-lg">
                        <i class="fas fa-calendar-alt text-4xl mb-3"></i>
                        <span class="font-semibold text-lg">Gérer les Années Académiques</span>
                    </button>
                    <button class="bg-teal-500 text-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center text-center transition-colors duration-200 hover:bg-teal-600 hover:shadow-lg">
                        <i class="fas fa-sitemap text-4xl mb-3"></i>
                        <span class="font-semibold text-lg">Gérer les Filières et Niveaux</span>
                    </button>
                    <button class="bg-orange-500 text-white p-6 rounded-xl shadow-md flex flex-col items-center justify-center text-center transition-colors duration-200 hover:bg-orange-600 hover:shadow-lg">
                        <i class="fas fa-book-open text-4xl mb-3"></i>
                        <span class="font-semibold text-lg">Gérer les Matières</span>
                    </button>
                </div>
            </section>

            <!-- Section3 Activités Récentes -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Activités Récentes / Journal</h2>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <ul class="space-y-4">
                        @forelse($recentActivities as $activity)
                            <li class="flex items-start">
                                <i class="fas fa-circle text-blue-400 text-xs mt-2 mr-3"></i>
                                <div>
                                    <p class="text-gray-700">{{ $activity['description'] }}</p>
                                    <p class="text-gray-500 text-sm">{{ $activity['time'] }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="text-gray-500">Aucune activité récente à afficher.</li>
                        @endforelse
                    </ul>
                </div>
            </section>

            <!-- Section4 Alertes et Notifications Système -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Alertes et Notifications Système</h2>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <ul class="space-y-4">
                        @forelse($systemAlerts as $alert)
                            <li class="flex items-start">
                                @if($alert['type'] == 'warning')
                                    <i class="fas fa-exclamation-circle text-yellow-500 text-xl mt-1 mr-3"></i>
                                @elseif($alert['type'] == 'info')
                                    <i class="fas fa-info-circle text-blue-500 text-xl mt-1 mr-3"></i>
                                @elseif($alert['type'] == 'success')
                                    <i class="fas fa-check-circle text-green-500 text-xl mt-1 mr-3"></i>
                                @endif
                                <p class="text-gray-700">{{ $alert['message'] }}</p>
                            </li>
                        @empty
                            <li class="text-gray-500">Aucune alerte système à afficher.</li>
                        @endforelse
                    </ul>
                </div>
            </section>
        </main>
    </div>
</div>
    @endsection
{{-- </body>
</html> --}}


