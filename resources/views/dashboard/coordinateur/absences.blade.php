@extends('layouts.app')

@section('content')
<div class="flex-1 overflow-y-auto bg-gray-100">
    <!-- En-tête -->
    <header class="bg-white shadow-sm">
        <div class="px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Gestion des Absences</h1>
                <p class="text-gray-600 mt-1">Suivi et gestion des absences des étudiants</p>
            </div>
            <a href="{{ route('coordinateur.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour au Dashboard
            </a>
        </div>
    </header>

    <div class="px-6 py-8">
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Absences Aujourd'hui</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ $absencesToday ?? 0 }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-calendar-times text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Absences cette semaine</p>
                        <p class="text-2xl font-bold text-orange-600 mt-1">{{ $absencesWeek ?? 0 }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fas fa-calendar-week text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Justifiées</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $absencesJustified ?? 0 }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Non justifiées</p>
                        <p class="text-2xl font-bold text-gray-600 mt-1">{{ $absencesNotJustified ?? 0 }}</p>
                    </div>
                    <div class="bg-gray-100 p-3 rounded-full">
                        <i class="fas fa-question-circle text-gray-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des absences -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Liste des Absences</h2>
                <div class="flex space-x-2">
                    <select class="px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Toutes les classes</option>
                        <!-- Ici on ajouterait les classes dynamiquement -->
                    </select>
                    <select class="px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="justifie">Justifiées</option>
                        <option value="non_justifie">Non justifiées</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Étudiant</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Classe</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Cours</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Statut</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absences as $absence)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    <div class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">
                                            {{ $absence->etudiant->user->prenom ?? 'N/A' }}
                                            {{ $absence->etudiant->user->nom ?? 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-600">{{ $absence->etudiant->user->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                    {{ $absence->cours->classe->nom_classe ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-medium text-gray-800">{{ $absence->cours->matiere->nom_matiere ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $absence->cours->heure_debut ?? 'N/A' }} - {{ $absence->cours->heure_fin ?? 'N/A' }}
                                </p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-gray-800">{{ \Carbon\Carbon::parse($absence->date_absence)->format('d/m/Y') }}</p>
                            </td>
                            <td class="py-3 px-4">
                                @if($absence->justifie)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                        <i class="fas fa-check mr-1"></i>Justifiée
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">
                                        <i class="fas fa-times mr-1"></i>Non justifiée
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex space-x-2">
                                    @if(!$absence->justifie)
                                        <button class="text-green-600 hover:text-green-800 transition">
                                            <i class="fas fa-check mr-1"></i>Justifier
                                        </button>
                                    @endif
                                    <button class="text-blue-600 hover:text-blue-800 transition">
                                        <i class="fas fa-eye mr-1"></i>Détails
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                <i class="fas fa-info-circle text-3xl mb-2"></i>
                                <p>Aucune absence enregistrée</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($absences->hasPages())
                <div class="mt-6">
                    {{ $absences->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
