@extends('layouts.dashboard')

@section('title', 'Mon Emploi du Temps')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Mon Emploi du Temps</h1>
            <p class="text-gray-600 mt-1">Consultez votre planning de cours</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-500">Étudiant</div>
            <div class="font-semibold text-gray-900">{{ $etudiant->nom }} {{ $etudiant->prenom }}</div>
        </div>
    </div>

    <!-- Navigation semaine -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <a href="{{ route('etudiant.emploi_temps', ['semaine' => $dateDebut->copy()->subWeek()->format('Y-m-d')]) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Semaine précédente
            </a>

            <div class="text-center">
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ $dateDebut->locale('fr')->format('d M') }} - {{ $dateFin->locale('fr')->format('d M Y') }}
                </h2>
                <p class="text-sm text-gray-600">Semaine {{ $dateDebut->weekOfYear }}</p>
            </div>

            <a href="{{ route('etudiant.emploi_temps', ['semaine' => $dateDebut->copy()->addWeek()->format('Y-m-d')]) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                Semaine suivante
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Emploi du temps -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-7 gap-0">
            @foreach($emploiTemps as $jour)
                <div class="border-r border-gray-200 last:border-r-0">
                    <!-- En-tête du jour -->
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <div class="text-center">
                            <div class="font-semibold text-gray-900 capitalize">{{ $jour['jour'] }}</div>
                            <div class="text-sm text-gray-600">{{ $jour['date']->format('d/m') }}</div>
                        </div>
                    </div>

                    <!-- Séances du jour -->
                    <div class="min-h-[400px] p-2">
                        @if($jour['seances']->count() > 0)
                            @foreach($jour['seances'] as $seance)
                                <div class="mb-2 p-3 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg hover:bg-blue-100 transition-colors">
                                    <div class="text-xs font-medium text-blue-800">
                                        {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}
                                    </div>
                                    <div class="font-semibold text-gray-900 text-sm mt-1">
                                        {{ $seance->matiere->nom ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        {{ $seance->enseignant->user->nom ?? 'N/A' }}
                                        {{ $seance->enseignant->user->prenom ?? '' }}
                                    </div>
                                    @if($seance->salle)
                                        <div class="text-xs text-gray-500 mt-1">
                                            📍 {{ $seance->salle }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center justify-center h-32 text-gray-400">
                                <div class="text-center">
                                    <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <div class="text-xs">Pas de cours</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="mt-6 flex flex-wrap gap-4">
        <a href="{{ route('etudiant.dashboard') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
            </svg>
            Retour au dashboard
        </a>

        <a href="{{ route('etudiant.absences') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Mes absences
        </a>

        <button onclick="window.print()"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Imprimer
        </button>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    body { print-color-adjust: exact; }
}
</style>
@endsection
