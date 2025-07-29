@extends('layouts.app')

@section('title', 'Dashboard Statistiques')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Tableau de Bord - Équipe Pédagogique
            </h1>

            <!-- Filtres -->
            <div class="flex space-x-4">
                @if($classes->count() > 0)
                <select name="classe_id" class="border rounded px-3 py-2" onchange="updateFilters()">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>
                            {{ $classe->nom_classe }}
                        </option>
                    @endforeach
                </select>
                @endif

                <select name="periode" class="border rounded px-3 py-2" onchange="updateFilters()">
                    <option value="current_month" {{ $periode == 'current_month' ? 'selected' : '' }}>Ce mois</option>
                    <option value="current_trimester" {{ $periode == 'current_trimester' ? 'selected' : '' }}>Ce trimestre</option>
                    <option value="current_year" {{ $periode == 'current_year' ? 'selected' : '' }}>Cette année</option>
                </select>
            </div>
        </div>

        <!-- Graphique 1: Taux de présence par étudiant -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Taux de Présence par Étudiant</h2>
            <div class="bg-gray-50 p-4 rounded">
                @if($tauxPresenceEtudiants->count() > 0)
                    <div class="space-y-2">
                        @foreach($tauxPresenceEtudiants as $etudiant)
                            <div class="flex items-center justify-between p-3 bg-white rounded border">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 rounded" style="background-color: {{ $etudiant->couleur === 'green' ? '#10B981' : ($etudiant->couleur === 'orange' ? '#F59E0B' : '#EF4444') }}"></div>
                                    <span class="font-medium">{{ $etudiant->prenom }} {{ $etudiant->nom }}</span>
                                    <span class="text-sm text-gray-500">({{ $etudiant->nom_classe }})</span>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-lg">{{ $etudiant->taux_presence }}%</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $etudiant->presents }} P, {{ $etudiant->retards }} R, {{ $etudiant->absents }} A
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucune donnée disponible pour cette période</p>
                @endif
            </div>
        </div>

        <!-- Graphique 2: Taux de présence par classe -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Taux de Présence par Classe</h2>
            <div class="bg-gray-50 p-4 rounded">
                @if($tauxPresenceClasses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($tauxPresenceClasses as $classe)
                            <div class="bg-white p-4 rounded border text-center">
                                <h3 class="font-semibold text-lg">{{ $classe->nom_classe }}</h3>
                                <div class="text-3xl font-bold mt-2 {{ $classe->taux_presence >= 85 ? 'text-green-500' : ($classe->taux_presence >= 70 ? 'text-orange-500' : 'text-red-500') }}">
                                    {{ $classe->taux_presence }}%
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    {{ $classe->presences_effectives }} / {{ $classe->total_presences }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucune donnée disponible pour cette période</p>
                @endif
            </div>
        </div>

        <!-- Graphique 3: Volume de cours dispensés -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Volume de Cours Dispensés</h2>
            <div class="bg-gray-50 p-4 rounded">
                @if($volumeCoursDispenses->count() > 0)
                    @foreach($volumeCoursDispenses as $nomClasse => $coursParType)
                        <div class="mb-6 bg-white p-4 rounded border">
                            <h3 class="font-semibold text-lg mb-3">{{ $nomClasse }}</h3>
                            <div class="grid grid-cols-3 gap-4">
                                @foreach(['Presentiel', 'E-learning', 'Workshop'] as $type)
                                    @php
                                        $cours = $coursParType->where('type_cours', $type)->first();
                                    @endphp
                                    <div class="text-center p-3 bg-gray-100 rounded">
                                        <div class="text-sm text-gray-600">{{ $type }}</div>
                                        <div class="text-xl font-bold">{{ $cours ? number_format($cours->heures_total, 1) : 0 }}h</div>
                                        <div class="text-sm text-gray-500">{{ $cours ? $cours->nombre_seances : 0 }} séances</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-center py-8">Aucune donnée disponible pour cette période</p>
                @endif
            </div>
        </div>

        <!-- Légende des couleurs -->
        <div class="bg-blue-50 p-4 rounded border-l-4 border-blue-500">
            <h3 class="font-semibold mb-2">Légende des couleurs</h3>
            <div class="flex flex-wrap gap-4 text-sm">
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-green-500 rounded"></div>
                    <span>Taux ≥ 85% (Bon)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-orange-500 rounded"></div>
                    <span>Taux 70-84% (Moyen)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-red-500 rounded"></div>
                    <span>Taux < 70% (Préoccupant)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateFilters() {
    const classeSelect = document.querySelector('select[name="classe_id"]');
    const periodeSelect = document.querySelector('select[name="periode"]');

    const params = new URLSearchParams();
    if (classeSelect && classeSelect.value) params.set('classe_id', classeSelect.value);
    if (periodeSelect && periodeSelect.value) params.set('periode', periodeSelect.value);

    window.location.href = window.location.pathname + '?' + params.toString();
}
</script>
@endsection
