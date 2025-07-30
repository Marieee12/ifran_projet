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
                    <h1 class="text-2xl font-bold text-gray-900">Emploi du Temps</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filtre par enfant et navigation semaine -->
        <div class="mb-6 bg-white shadow rounded-lg p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <!-- Filtre par enfant -->
                @if($enfants->count() > 1)
                    <div class="flex items-center space-x-4">
                        <label for="etudiant_id" class="text-sm font-medium text-gray-700">Enfant:</label>
                        <form method="GET" action="{{ route('parent.emploi_temps') }}" class="flex items-center space-x-2">
                            <select name="etudiant_id" id="etudiant_id" onchange="this.form.submit()" class="block w-64 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Tous mes enfants</option>
                                @foreach($enfants as $enfant)
                                    <option value="{{ $enfant->id }}" {{ $etudiantSelectionne == $enfant->id ? 'selected' : '' }}>
                                        {{ $enfant->user->prenom }} {{ $enfant->user->nom }} - {{ $enfant->classe->nom_classe ?? 'Classe non assignée' }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="semaine" value="{{ $semaine }}">
                        </form>
                    </div>
                @endif

                <!-- Navigation de semaine -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('parent.emploi_temps', array_merge(request()->query(), ['semaine' => $dateDebut->copy()->subWeek()->format('Y-m-d')])) }}"
                       class="p-2 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>

                    <div class="text-center">
                        <div class="text-lg font-medium text-gray-900">
                            {{ $dateDebut->format('d/m/Y') }} - {{ $dateFin->format('d/m/Y') }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Semaine {{ $dateDebut->weekOfYear }}
                        </div>
                    </div>

                    <a href="{{ route('parent.emploi_temps', array_merge(request()->query(), ['semaine' => $dateDebut->copy()->addWeek()->format('Y-m-d')])) }}"
                       class="p-2 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('parent.emploi_temps', array_merge(request()->query(), ['semaine' => now()->startOfWeek()->format('Y-m-d')])) }}"
                       class="px-3 py-2 text-sm font-medium rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                        Cette semaine
                    </a>
                </div>
            </div>
        </div>

        <!-- Planning -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                Heure
                            </th>
                            @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $jour)
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div>{{ $jour }}</div>
                                    <div class="text-xs text-gray-400 normal-case">
                                        {{ $dateDebut->copy()->addDays(array_search($jour, ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']))->format('d/m') }}
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $heures = [];
                            foreach($seances as $seance) {
                                $heures[] = $seance->heure_debut;
                                $heures[] = $seance->heure_fin;
                            }
                            $heures = array_unique($heures);
                            sort($heures);

                            $seancesParJourHeure = [];
                            foreach($seances as $seance) {
                                $jour = $seance->date_seance->dayOfWeek == 0 ? 7 : $seance->date_seance->dayOfWeek; // Dimanche = 7
                                if($jour <= 6) { // Exclure dimanche
                                    $key = $jour . '_' . $seance->heure_debut;
                                    if(!isset($seancesParJourHeure[$key])) {
                                        $seancesParJourHeure[$key] = [];
                                    }
                                    $seancesParJourHeure[$key][] = $seance;
                                }
                            }
                        @endphp

                        @forelse($heures as $heure)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($heure)->format('H:i') }}
                                </td>
                                @for($jour = 1; $jour <= 6; $jour++)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $key = $jour . '_' . $heure;
                                            $seancesJour = $seancesParJourHeure[$key] ?? [];
                                        @endphp

                                        @if(count($seancesJour) > 0)
                                            @foreach($seancesJour as $seance)
                                                <div class="mb-2 last:mb-0">
                                                    <div class="bg-blue-100 border-l-4 border-blue-500 rounded-r-md p-3">
                                                        <div class="flex justify-between items-start">
                                                            <div class="flex-1">
                                                                <h4 class="text-sm font-medium text-blue-900">
                                                                    {{ $seance->matiere->nom_matiere }}
                                                                </h4>
                                                                <p class="text-xs text-blue-700 mt-1">
                                                                    {{ $seance->enseignant->user->prenom }} {{ $seance->enseignant->user->nom }}
                                                                </p>
                                                                <p class="text-xs text-blue-600 mt-1">
                                                                    {{ $seance->classe->nom_classe }}
                                                                </p>
                                                                @if($seance->salle)
                                                                    <p class="text-xs text-blue-600">
                                                                        Salle: {{ $seance->salle }}
                                                                    </p>
                                                                @endif
                                                                <div class="text-xs text-blue-600 mt-1">
                                                                    {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} -
                                                                    {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-gray-400 text-center py-4">
                                                <span class="text-sm">-</span>
                                            </div>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun cours programmé</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        @if($etudiantSelectionne)
                                            Aucun cours programmé pour cet enfant cette semaine.
                                        @else
                                            Aucun cours programmé pour vos enfants cette semaine.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Résumé de la semaine -->
        @if($seances->count() > 0)
            <div class="mt-8 bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Résumé de la semaine</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <div class="text-lg font-medium text-blue-900">{{ $seances->count() }}</div>
                                    <div class="text-sm text-blue-700">Cours total</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <div class="text-lg font-medium text-green-900">{{ $seances->unique('matiere.id')->count() }}</div>
                                    <div class="text-sm text-green-700">Matières</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <div class="text-lg font-medium text-purple-900">{{ $seances->unique('enseignant.id')->count() }}</div>
                                    <div class="text-sm text-purple-700">Enseignants</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
