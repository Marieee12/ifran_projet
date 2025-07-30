@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
                <p class="text-sm text-gray-600 mt-1">{{ now()->locale('fr')->isoFormat('dddd, DD MMMM YYYY') }}</p>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="bg-gray-50 min-h-screen">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Message de bienvenue -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Bonjour, Admin</h2>
            <p class="text-gray-600">Prêt à commencer la journée d'administrateur ?</p>
        </div>

        <!-- Vue d'Ensemble Rapide -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Utilisateurs Totaux -->
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">{{ $usersCount ?? 100 }}</p>
                        <p class="text-2xl font-bold mt-1">Gérer les Utilisateurs</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Classes Actives -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">{{ $classesCount ?? 9 }}</p>
                        <p class="text-2xl font-bold mt-1">Gérer les Classes</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-school text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Emploi du Temps -->
            <div class="bg-gradient-to-br from-pink-400 to-pink-500 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-100 text-sm font-medium">20</p>
                        <p class="text-2xl font-bold mt-1">Voir l'Emploi du Temps Global</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Rapports -->
            <div class="bg-gradient-to-br from-red-400 to-red-500 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">10</p>
                        <p class="text-2xl font-bold mt-1">Voir les Rapports d'Assiduité</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-chart-bar text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accès Rapide aux Gestions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Section utilisateurs -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-gray-900 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Gestion des Utilisateurs</h3>
                            <p class="text-sm text-gray-600">Créer et gérer les comptes</p>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('dashboard.utilisateur.create') }}" class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-center transition-colors">
                        Ajouter Utilisateur
                    </a>
                    <a href="{{ route('dashboard.utilisateur.liste') }}" class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-center transition-colors">
                        Voir Tous
                    </a>
                </div>
            </div>

            <!-- Section académique -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-gray-900 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-graduation-cap text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Configuration Académique</h3>
                            <p class="text-sm text-gray-600">Classes, filières et matières</p>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('filieres.index') }}" class="flex-1 bg-purple-50 hover:bg-purple-100 text-purple-700 px-4 py-2 rounded-lg text-center transition-colors">
                        Filières
                    </a>
                    <a href="{{ route('matieres.index') }}" class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-center transition-colors">
                        Matières
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
