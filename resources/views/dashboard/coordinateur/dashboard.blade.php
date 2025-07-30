@extends('layouts.app')
@section('content')
<div class="flex-1 overflow-y-auto bg-gray-100">
    <!-- En-tête du tableau de bord -->
    <header class="bg-white shadow-sm">
        <div class="px-6 py-4">
            <h1 class="text-2xl font-bold text-gray-800">Tableau de Bord Coordinateur</h1>
        </div>
    </header>

    <div class="px-6 py-8">
        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Statistiques des absences -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Absences du jour</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ $absencesCount ?? 0 }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-user-times text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Statistiques des justifications en attente -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Justifications en attente</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $justificationsCount ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Cours du jour -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Cours aujourd'hui</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $coursCount ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total des étudiants -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Étudiants</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $etudiantsCount ?? 0 }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-user-graduate text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <a href="{{ route('coordinateur.absences.dashboard') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-red-100 p-4 rounded-full mb-4 group-hover:bg-red-200 transition-colors duration-200">
                        <i class="fas fa-user-clock text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Gestion des Absences</h3>
                    <p class="text-sm text-gray-600">Suivre et gérer les absences des étudiants</p>
                </div>
            </a>

            <a href="{{ route('coordinateur.agenda') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-blue-100 p-4 rounded-full mb-4 group-hover:bg-blue-200 transition-colors duration-200">
                        <i class="fas fa-calendar-alt text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Agenda</h3>
                    <p class="text-sm text-gray-600">Visualiser l'emploi du temps en calendrier</p>
                </div>
            </a>

            <a href="{{ route('coordinateur.cours.index') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-purple-100 p-4 rounded-full mb-4 group-hover:bg-purple-200 transition-colors duration-200">
                        <i class="fas fa-book-open text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Gestion des Cours</h3>
                    <p class="text-sm text-gray-600">Voir et gérer tous les cours</p>
                </div>
            </a>

            <a href="{{ route('coordinateur.creer_cours') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-indigo-100 p-4 rounded-full mb-4 group-hover:bg-indigo-200 transition-colors duration-200">
                        <i class="fas fa-plus-circle text-2xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Créer un Cours</h3>
                    <p class="text-sm text-gray-600">Ajouter et planifier un nouveau cours</p>
                </div>
            </a>

            <a href="{{ route('coordinateur.justifications') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-green-100 p-4 rounded-full mb-4 group-hover:bg-green-200 transition-colors duration-200">
                        <i class="fas fa-file-alt text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Justifications</h3>
                    <p class="text-sm text-gray-600">Gérer les justifications d'absence</p>
                </div>
            </a>
        </div>

        <!-- Liste des cours du jour -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Cours d'aujourd'hui</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Horaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Matière</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enseignant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($coursDuJour ?? [] as $cours)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cours->heure_debut }} - {{ $cours->heure_fin }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cours->classe->nom_classe_complet ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cours->matiere->nom_matiere ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cours->enseignant?->user?->nom ?? 'Non assigné' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $cours->statut === 'En cours' ? 'bg-green-100 text-green-800' :
                                       ($cours->statut === 'À venir' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $cours->statut }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Aucun cours programmé pour aujourd'hui
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Dernières notifications -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Dernières Notifications</h2>
            <div class="space-y-4">
                @forelse($notifications ?? [] as $notification)
                <div class="flex items-start space-x-4 p-4 rounded-lg {{ $notification->type === 'urgent' ? 'bg-red-50' : 'bg-gray-50' }}">
                    <div class="flex-shrink-0">
                        <i class="fas {{ $notification->type === 'urgent' ? 'fa-exclamation-circle text-red-500' : 'fa-info-circle text-blue-500' }} text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $notification->message }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">Aucune notification récente</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
