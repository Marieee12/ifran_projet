@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-calendar-times text-red-500 mr-3"></i>
                        Gestion des Absences
                    </h1>
                    <p class="text-gray-600">Suivi et gestion des absences étudiantes</p>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                    <a href="{{ route('coordinateur.absences.justifications') }}"
                       class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
                        <i class="fas fa-file-alt mr-2"></i>
                        Justifications en attente
                    </a>
                    <a href="{{ route('coordinateur.absences.rapport') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-chart-line mr-2"></i>
                        Rapports
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                    <input type="date" name="date_debut" value="{{ $dateDebut }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                    <input type="date" name="date_fin" value="{{ $dateFin }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Classe</label>
                    <select name="classe_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les classes</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom_classe_complet }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                    <select name="matiere_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les matières</option>
                        @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id }}" {{ $matiereId == $matiere->id ? 'selected' : '' }}>
                                {{ $matiere->nom_matiere }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4 flex space-x-3">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-filter mr-2"></i>Filtrer
                    </button>
                    <a href="{{ route('coordinateur.absences.dashboard') }}"
                       class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                        <i class="fas fa-times mr-2"></i>Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-calendar-times text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_absences'] }}</p>
                        <p class="text-gray-600">Total absences</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['absences_justifiees'] }}</p>
                        <p class="text-gray-600">Justifiées</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <i class="fas fa-exclamation-triangle text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['absences_non_justifiees'] }}</p>
                        <p class="text-gray-600">Non justifiées</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-calendar text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['absences_periode'] }}</p>
                        <p class="text-gray-600">Cette période</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Séances récentes à traiter -->
        @if($seancesRecentes->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-clipboard-check text-blue-500 mr-2"></i>
                    Séances récentes - Marquer les présences
                </h2>
                <p class="text-sm text-gray-600 mt-1">Cliquez sur une séance pour marquer les présences/absences</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($seancesRecentes as $seance)
                        <a href="{{ route('coordinateur.absences.marquer', $seance->id) }}"
                           class="block p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 group">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 group-hover:text-blue-600">
                                        {{ $seance->matiere->nom_matiere ?? 'N/A' }}
                                    </h3>
                                    <p class="text-sm text-gray-600">{{ $seance->classe->nom_classe_complet ?? 'N/A' }}</p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full
                                    {{ $seance->type_cours === 'Presentiel' ? 'bg-green-100 text-green-800' :
                                       ($seance->type_cours === 'E-learning' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                                    {{ $seance->type_cours }}
                                </span>
                            </div>

                            <div class="text-sm text-gray-500 space-y-1">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar text-xs mr-2"></i>
                                    {{ $seance->date_seance->format('d/m/Y') }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-xs mr-2"></i>
                                    {{ $seance->heure_debut }} - {{ $seance->heure_fin }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-user text-xs mr-2"></i>
                                    {{ $seance->enseignant->user->prenom ?? 'N/A' }} {{ $seance->enseignant->user->nom ?? '' }}
                                </div>
                                @if($seance->salle)
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt text-xs mr-2"></i>
                                    {{ $seance->salle }}
                                </div>
                                @endif
                            </div>

                            <div class="mt-3 text-right">
                                <span class="inline-flex items-center text-xs text-blue-600 group-hover:text-blue-700">
                                    Marquer présences <i class="fas fa-arrow-right ml-1"></i>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Liste des absences récentes -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">Absences récentes</h2>
                        <form method="GET" action="{{ route('coordinateur.absences.export') }}" class="inline">
                            @foreach(request()->query() as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <button type="submit" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-download mr-1"></i>Exporter
                            </button>
                        </form>
                    </div>

                    @if($absences->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cours</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($absences as $absence)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">
                                                            {{ substr($absence->etudiant->user->prenom, 0, 1) }}{{ substr($absence->etudiant->user->nom, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $absence->etudiant->user->prenom }} {{ $absence->etudiant->user->nom }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $absence->seanceCours->classe->nom_classe_complet ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $absence->seanceCours->matiere->nom_matiere ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $absence->seanceCours->heure_debut ?? '' }} - {{ $absence->seanceCours->heure_fin ?? '' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $absence->date_saisie->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($absence->justifie)
                                                    @if($absence->justification && $absence->justification->statut === 'validee')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                            <i class="fas fa-check mr-1"></i>Justifiée
                                                        </span>
                                                    @elseif($absence->justification && $absence->justification->statut === 'en_attente')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-clock mr-1"></i>En attente
                                                        </span>
                                                    @elseif($absence->justification && $absence->justification->statut === 'refusee')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                            <i class="fas fa-times mr-1"></i>Refusée
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>Non justifiée
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($absence->justification && $absence->justification->statut === 'en_attente')
                                                    <a href="{{ route('coordinateur.absences.justifications') }}"
                                                       class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $absences->links() }}
                        </div>
                    @else
                        <div class="px-6 py-8 text-center">
                            <i class="fas fa-calendar-check text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune absence trouvée</h3>
                            <p class="text-gray-500">Aucune absence ne correspond aux critères sélectionnés.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Top étudiants absents -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Étudiants les plus absents</h2>
                    </div>

                    @if($etudiantsAbsents->count() > 0)
                        <div class="p-6">
                            @foreach($etudiantsAbsents as $index => $etudiant)
                                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-sm font-bold text-red-600">{{ $index + 1 }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $etudiant->etudiant->user->prenom }} {{ $etudiant->etudiant->user->nom }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-sm font-bold text-red-600">
                                        {{ $etudiant->nombre_absences }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center text-gray-500">
                            Aucune donnée disponible
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
