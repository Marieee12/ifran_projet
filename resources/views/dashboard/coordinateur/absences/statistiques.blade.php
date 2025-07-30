@extends('layouts.app')

@section('content')
<div class="flex-1 overflow-y-auto bg-gray-100">
    <!-- En-tête -->
    <header class="bg-white shadow-sm">
        <div class="px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Statistiques des Absences</h1>
                <p class="text-gray-600 mt-1">Analyse détaillée des absences par période</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-print mr-2"></i>Imprimer
                </button>
                <a href="{{ route('coordinateur.absences') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour aux absences
                </a>
            </div>
        </div>
    </header>

    <div class="px-6 py-8">
        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <form method="GET" action="{{ route('coordinateur.absences.statistiques') }}" class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Du:</label>
                    <input type="date" name="date_debut" value="{{ $dateDebut }}"
                           class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Au:</label>
                    <input type="date" name="date_fin" value="{{ $dateFin }}"
                           class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Classe:</label>
                    <select name="classe_id" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Toutes les classes</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom_classe_complet }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>

                <a href="{{ route('coordinateur.absences.statistiques') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-times mr-2"></i>Réinitialiser
                </a>
            </form>
        </div>

        <!-- Statistiques générales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Absences</p>
                        <p class="text-3xl font-bold text-red-600 mt-1">{{ $stats['total_absences'] }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-user-times text-red-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Justifiées</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['absences_justifiees'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $stats['total_absences'] > 0 ? round(($stats['absences_justifiees'] / $stats['total_absences']) * 100, 1) : 0 }}%
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Non Justifiées</p>
                        <p class="text-3xl font-bold text-orange-600 mt-1">{{ $stats['absences_non_justifiees'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $stats['total_absences'] > 0 ? round(($stats['absences_non_justifiees'] / $stats['total_absences']) * 100, 1) : 0 }}%
                        </p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fas fa-exclamation-triangle text-orange-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Étudiants Concernés</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['etudiants_absents'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques et analyses -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Absences par classe -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                    Absences par Classe
                </h3>
                @if($stats['absences_par_classe']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['absences_par_classe'] as $classe => $count)
                            @php
                                $pourcentage = $stats['total_absences'] > 0 ? ($count / $stats['total_absences']) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $classe ?: 'Non définie' }}</span>
                                    <span class="text-sm text-gray-600">{{ $count }} ({{ round($pourcentage, 1) }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucune donnée disponible</p>
                @endif
            </div>

            <!-- Absences par matière -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-book text-purple-600 mr-2"></i>
                    Absences par Matière
                </h3>
                @if($stats['absences_par_matiere']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['absences_par_matiere'] as $matiere => $count)
                            @php
                                $pourcentage = $stats['total_absences'] > 0 ? ($count / $stats['total_absences']) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $matiere ?: 'Non définie' }}</span>
                                    <span class="text-sm text-gray-600">{{ $count }} ({{ round($pourcentage, 1) }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucune donnée disponible</p>
                @endif
            </div>
        </div>

        <!-- Timeline des absences -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                Évolution des Absences
            </h3>
            @if($stats['absences_par_jour']->count() > 0)
                <div class="overflow-x-auto">
                    <div class="flex items-end space-x-2 min-w-full" style="height: 200px;">
                        @php
                            $maxAbsences = $stats['absences_par_jour']->max();
                        @endphp
                        @foreach($stats['absences_par_jour'] as $date => $count)
                            @php
                                $hauteur = $maxAbsences > 0 ? ($count / $maxAbsences) * 160 : 0;
                                $dateFormatee = \Carbon\Carbon::parse($date)->format('d/m');
                            @endphp
                            <div class="flex flex-col items-center">
                                <div class="bg-blue-600 rounded-t"
                                     style="width: 20px; height: {{ $hauteur }}px; min-height: 4px;"
                                     title="{{ $count }} absence(s) le {{ $dateFormatee }}">
                                </div>
                                <span class="text-xs text-gray-600 mt-1 transform -rotate-45 origin-left">
                                    {{ $dateFormatee }}
                                </span>
                                <span class="text-xs font-medium text-gray-800">
                                    {{ $count }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Aucune donnée disponible pour cette période</p>
            @endif
        </div>

        <!-- Actions d'export -->
        <div class="mt-8 bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-download text-indigo-600 mr-2"></i>
                Exporter les Données
            </h3>
            <div class="flex flex-wrap gap-4">
                <form method="POST" action="{{ route('coordinateur.absences.export') }}" class="inline">
                    @csrf
                    <input type="hidden" name="date_debut" value="{{ $dateDebut }}">
                    <input type="hidden" name="date_fin" value="{{ $dateFin }}">
                    <input type="hidden" name="classe_id" value="{{ $classeId }}">
                    <input type="hidden" name="format" value="csv">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-file-csv mr-2"></i>Exporter en CSV
                    </button>
                </form>

                <form method="POST" action="{{ route('coordinateur.absences.export') }}" class="inline">
                    @csrf
                    <input type="hidden" name="date_debut" value="{{ $dateDebut }}">
                    <input type="hidden" name="date_fin" value="{{ $dateFin }}">
                    <input type="hidden" name="classe_id" value="{{ $classeId }}">
                    <input type="hidden" name="format" value="excel">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-file-excel mr-2"></i>Exporter en Excel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }

        body {
            font-size: 12px;
        }

        .bg-white {
            background: white !important;
        }

        .shadow-sm {
            box-shadow: none !important;
        }
    }
</style>
@endsection
