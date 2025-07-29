@extends('layouts.app')

@section('content')
<div class="flex-1 overflow-y-auto bg-gray-100">
    <header class="bg-white shadow-sm">
        <div class="px-6 py-4">
            <h1 class="text-2xl font-bold text-gray-800">Tableau de Bord Administrateur</h1>
        </div>
    </header>

    <div class="px-6 py-8">
        <!-- Vue d'Ensemble Rapide -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Utilisateurs Totaux</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $usersCount ?? 3 }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Classes Actives</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $classesCount ?? 1 }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-school text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Séances Planifiées</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">{{ $coursCount ?? 0 }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Étudiants "Droppés"</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ $droppedStudentsCount ?? 0 }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-user-slash text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accès Rapide aux Gestions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Ajouter un Nouvel Utilisateur -->
            <a href="{{ route('dashboard.utilisateur.create') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-blue-100 p-4 rounded-full mb-4 group-hover:bg-blue-200 transition-colors duration-200">
                        <i class="fas fa-user-plus text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Ajouter un Utilisateur</h3>
                    <p class="text-sm text-gray-600">Créer un nouveau compte utilisateur</p>
                </div>
            </a>

            <!-- Gérer les Années Académiques -->
            <a href="{{ route('annees_academiques.index') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-purple-100 p-4 rounded-full mb-4 group-hover:bg-purple-200 transition-colors duration-200">
                        <i class="fas fa-calendar-alt text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Gérer les Années</h3>
                    <p class="text-sm text-gray-600">Configurer les années académiques</p>
                </div>
            </a>

            <!-- Gérer les Filières et Niveaux -->
            <a href="{{ route('filieres.index') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-green-100 p-4 rounded-full mb-4 group-hover:bg-green-200 transition-colors duration-200">
                        <i class="fas fa-sitemap text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Filières & Niveaux</h3>
                    <p class="text-sm text-gray-600">Gérer la structure académique</p>
                </div>
            </a>

            <!-- Gérer les Matières -->
            <a href="{{ route('matieres.index') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-yellow-100 p-4 rounded-full mb-4 group-hover:bg-yellow-200 transition-colors duration-200">
                        <i class="fas fa-book text-2xl text-yellow-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Gérer les Matières</h3>
                    <p class="text-sm text-gray-600">Configurer les matières enseignées</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
