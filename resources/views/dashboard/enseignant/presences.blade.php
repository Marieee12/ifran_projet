@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-gray-800">Feuille de Présence</h1>
        <a href="{{ route('enseignant.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour au tableau de bord
        </a>
    </div>

    @if($seances->count() > 0)
        <div class="grid gap-6">
            @foreach($seances as $seance)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">{{ $seance->matiere->nom_matiere }}</h3>
                            <p class="text-gray-600">Classe: {{ $seance->classe->nom_classe }}</p>
                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                Aujourd'hui
                            </span>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('enseignant.seance.presences', $seance->id) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center">
                            <i class="fas fa-clipboard-check mr-2"></i>
                            Marquer les présences
                        </a>

                        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg transition-colors duration-200 flex items-center">
                            <i class="fas fa-eye mr-2"></i>
                            Voir les détails
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-calendar-times text-6xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Aucun cours aujourd'hui</h3>
            <p class="text-gray-600">Vous n'avez pas de cours programmés pour aujourd'hui.</p>
            <a href="{{ route('enseignant.cours') }}"
               class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                Voir tous mes cours
            </a>
        </div>
    @endif
</div>
@endsection
