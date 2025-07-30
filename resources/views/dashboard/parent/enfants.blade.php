@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('parent.dashboard') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">Mes Enfants</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($enfants->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($enfants as $enfant)
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-6">
                            <!-- Photo et nom de l'enfant -->
                            <div class="flex items-center mb-6">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-2xl font-semibold text-blue-600">
                                        {{ substr($enfant->user->prenom, 0, 1) }}{{ substr($enfant->user->nom, 0, 1) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $enfant->user->prenom }} {{ $enfant->user->nom }}
                                    </h3>
                                    <p class="text-sm text-gray-500">{{ $enfant->user->email }}</p>
                                </div>
                            </div>

                            <!-- Informations académiques -->
                            <div class="border-t border-gray-200 pt-4">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Classe</dt>
                                        <dd class="text-sm text-gray-900">
                                            {{ $enfant->classe->nom_classe_complet ?? 'Non assignée' }}
                                        </dd>
                                    </div>
                                    @if($enfant->classe && $enfant->classe->filiere)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Filière</dt>
                                            <dd class="text-sm text-gray-900">{{ $enfant->classe->filiere->nom_filiere }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Niveau</dt>
                                            <dd class="text-sm text-gray-900">{{ $enfant->classe->filiere->niveauEtude->nom_niveau ?? 'N/A' }}</dd>
                                        </div>
                                    @endif
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Numéro étudiant</dt>
                                        <dd class="text-sm text-gray-900">{{ $enfant->numero_etudiant ?? 'N/A' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Statistiques d'absence -->
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="text-center">
                                        <span class="block text-2xl font-bold text-red-600">{{ $enfant->totalAbsences }}</span>
                                        <span class="block text-xs text-gray-500">Total absences</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="block text-2xl font-bold text-orange-600">{{ $enfant->absencesNonJustifiees }}</span>
                                        <span class="block text-xs text-gray-500">Non justifiées</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex space-x-3">
                                    <a href="{{ route('parent.emploi_temps', ['etudiant_id' => $enfant->id]) }}"
                                       class="flex-1 bg-blue-600 text-white text-center px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Emploi du temps
                                    </a>
                                    <a href="{{ route('parent.absences', ['etudiant_id' => $enfant->id]) }}"
                                       class="flex-1 bg-red-600 text-white text-center px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Absences
                                    </a>
                                </div>
                            </div>

                            <!-- Dernière communication -->
                            @if($enfant->derniereCommunication)
                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <div class="bg-blue-50 rounded-md p-3">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h4 class="text-sm font-medium text-blue-800">Dernière communication</h4>
                                                <p class="text-sm text-blue-700 mt-1">{{ $enfant->derniereCommunication->message }}</p>
                                                <p class="text-xs text-blue-600 mt-1">{{ $enfant->derniereCommunication->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- État vide -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun enfant trouvé</h3>
                <p class="mt-1 text-sm text-gray-500">Contactez l'administration pour associer vos enfants à votre compte parent.</p>
                <div class="mt-6">
                    <a href="{{ route('parent.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Retour au tableau de bord
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
