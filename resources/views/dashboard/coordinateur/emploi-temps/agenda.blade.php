@extends('layouts.app')

@section('content')
<div class="flex-1 overflow-y-auto bg-gray-100">
    <!-- En-tête -->
    <header class="bg-white shadow-sm">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Agenda des Cours</h1>
                    <p class="text-gray-600 mt-1">Visualisation calendaire des cours programmés</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('coordinateur.cours.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-list mr-2"></i>Liste des cours
                    </a>
                    <a href="{{ route('coordinateur.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="px-6 py-8">
        <!-- Filtres et navigation -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <form method="GET" action="{{ route('coordinateur.agenda') }}" class="flex flex-wrap items-center gap-4">
                <!-- Navigation mois/année -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('coordinateur.agenda', ['mois' => $dateDebut->copy()->subMonth()->month, 'annee' => $dateDebut->copy()->subMonth()->year, 'classe_id' => $classeId]) }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg transition">
                        <i class="fas fa-chevron-left"></i>
                    </a>

                    <div class="flex items-center space-x-2">
                        <select name="mois" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'] as $index => $nomMois)
                                <option value="{{ $index + 1 }}" {{ $mois == ($index + 1) ? 'selected' : '' }}>
                                    {{ $nomMois }}
                                </option>
                            @endforeach
                        </select>

                        <select name="annee" class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @for($year = 2023; $year <= 2030; $year++)
                                <option value="{{ $year }}" {{ $annee == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <a href="{{ route('coordinateur.agenda', ['mois' => $dateDebut->copy()->addMonth()->month, 'annee' => $dateDebut->copy()->addMonth()->year, 'classe_id' => $classeId]) }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg transition">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>

                <!-- Filtre par classe -->
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

                <!-- Boutons -->
                <div class="flex space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                    <a href="{{ route('coordinateur.agenda') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-times mr-2"></i>Effacer
                    </a>
                </div>
            </form>
        </div>

        <!-- Titre du mois -->
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">
                {{ $dateDebut->locale('fr')->isoFormat('MMMM YYYY') }}
            </h2>
        </div>

        <!-- Calendrier -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- En-têtes des jours -->
            <div class="grid grid-cols-7 bg-gray-50 border-b">
                @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $jour)
                    <div class="p-4 text-center font-semibold text-gray-700 border-r last:border-r-0">
                        {{ $jour }}
                    </div>
                @endforeach
            </div>

            <!-- Grille du calendrier -->
            <div class="grid grid-cols-7">
                @foreach($calendrier as $semaine)
                    @foreach($semaine as $jourData)
                        <div class="min-h-[120px] border-r border-b last:border-r-0 p-2 {{ !$jourData['est_dans_le_mois'] ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-50 transition-colors">
                            <!-- Numéro du jour -->
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-medium {{ !$jourData['est_dans_le_mois'] ? 'text-gray-400' : ($jourData['date']->isToday() ? 'text-blue-600 font-bold' : 'text-gray-700') }}">
                                    {{ $jourData['date']->day }}
                                </span>
                                @if($jourData['nombre_cours'] > 0)
                                    <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                        {{ $jourData['nombre_cours'] }}
                                    </span>
                                @endif
                            </div>

                            <!-- Liste des cours -->
                            <div class="space-y-1">
                                @foreach($jourData['cours']->take(3) as $cours)
                                    <div class="bg-blue-100 hover:bg-blue-200 rounded p-1 cursor-pointer transition-colors"
                                         title="Cours de {{ $cours->matiere->nom_matiere ?? 'N/A' }} - {{ $cours->classe->nom_classe_complet ?? 'N/A' }}">
                                        <div class="text-xs font-medium text-blue-800 truncate">
                                            {{ $cours->heure_debut }} - {{ $cours->matiere->nom_matiere ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-blue-600 truncate">
                                            {{ $cours->classe->nom_classe_complet ?? 'N/A' }}
                                        </div>
                                        @if($cours->enseignant?->user)
                                            <div class="text-xs text-blue-500 truncate">
                                                {{ $cours->enseignant->user->prenom }} {{ $cours->enseignant->user->nom }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                                @if($jourData['nombre_cours'] > 3)
                                    <div class="text-xs text-gray-500 text-center">
                                        +{{ $jourData['nombre_cours'] - 3 }} cours
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        <!-- Légende -->
        <div class="mt-6 bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Légende</h3>
            <div class="flex flex-wrap gap-6">
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-blue-600 rounded"></div>
                    <span class="text-sm text-gray-700">Jour avec cours</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-blue-100 border-2 border-blue-600 rounded"></div>
                    <span class="text-sm text-gray-700">Aujourd'hui</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-gray-100 rounded"></div>
                    <span class="text-sm text-gray-700">Autre mois</span>
                </div>
            </div>
        </div>

        <!-- Statistiques du mois -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Cours ce mois</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">
                            {{ $coursParDate->flatten()->count() }}
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Jours avec cours</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">
                            {{ $coursParDate->count() }}
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Classes concernées</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">
                            {{ $coursParDate->flatten()->pluck('id_classe')->unique()->count() }}
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Enseignants</p>
                        <p class="text-2xl font-bold text-orange-600 mt-1">
                            {{ $coursParDate->flatten()->pluck('id_enseignant')->filter()->unique()->count() }}
                        </p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fas fa-chalkboard-teacher text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styles personnalisés pour le calendrier */
    .grid-cols-7 > div:hover {
        transform: scale(1.02);
        transition: transform 0.2s ease-in-out;
    }

    .grid-cols-7 > div {
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection
