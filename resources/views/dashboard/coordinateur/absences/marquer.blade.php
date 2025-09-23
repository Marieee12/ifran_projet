@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-clipboard-check text-blue-500 mr-3"></i>
                        Marquer les Présences
                    </h1>
                    <div class="space-y-1">
                        <p class="text-gray-600">
                            <strong>Matière :</strong> {{ $seance->matiere->nom_matiere ?? 'N/A' }}
                        </p>
                        <p class="text-gray-600">
                            <strong>Classe :</strong> {{ $seance->classe->nom_classe_complet ?? 'N/A' }}
                        </p>
                        <p class="text-gray-600">
                            <strong>Date :</strong> {{ $seance->date_seance->format('d/m/Y') ?? 'N/A' }}
                        </p>
                        <p class="text-gray-600">
                            <strong>Horaire :</strong> {{ $seance->heure_debut }} - {{ $seance->heure_fin }}
                        </p>
                        <p class="text-gray-600">
                            <strong>Type :</strong> {{ $seance->type_cours }}
                        </p>
                        <p class="text-gray-600">
                            <strong>Enseignant :</strong> {{ $seance->enseignant->user->prenom ?? 'N/A' }} {{ $seance->enseignant->user->nom ?? 'N/A' }}
                        </p>
                        @if($seance->salle)
                        <p class="text-gray-600">
                            <strong>Salle :</strong> {{ $seance->salle }}
                        </p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('coordinateur.absences.dashboard') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- Formulaire de présences -->
        <div class="bg-white rounded-lg shadow-sm border">
            <form method="POST" action="{{ route('coordinateur.absences.enregistrer', $seance->id) }}" id="presenceForm">
                @csrf

                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">Liste des étudiants</h2>
                        <div class="flex space-x-2">
                            <button type="button" onclick="marquerTousPresents()"
                                    class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">
                                <i class="fas fa-check-double mr-1"></i>Tous présents
                            </button>
                            <button type="button" onclick="marquerTousRetards()"
                                    class="bg-orange-500 text-white px-3 py-1 rounded text-sm hover:bg-orange-600 transition">
                                <i class="fas fa-clock mr-1"></i>Tous en retard
                            </button>
                            <button type="button" onclick="marquerTousAbsents()"
                                    class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition">
                                <i class="fas fa-times-circle mr-1"></i>Tous absents
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div id="presence-counters" class="mb-4 text-sm text-gray-700 font-medium">
                        <span id="counter-present" class="mr-4">Présents : 0</span>
                        <span id="counter-retard" class="mr-4">Retards : 0</span>
                        <span id="counter-absent">Absents : 0</span>
                    </div>

                    @if($etudiants->count() > 0)
                        <div class="space-y-4">
                            @foreach($etudiants as $etudiant)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                            <span class="text-lg font-semibold text-blue-600">
                                                {{ substr($etudiant->user->prenom, 0, 1) }}{{ substr($etudiant->user->nom, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-lg font-medium text-gray-900">
                                                {{ $etudiant->user->prenom }} {{ $etudiant->user->nom }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $etudiant->numero_etudiant }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex space-x-3">
                                        <!-- Présent -->
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio"
                                                   name="presences[{{ $etudiant->id }}]"
                                                   value="Present"
                                                   class="hidden presence-radio"
                                                   {{ (isset($presencesExistantes[$etudiant->id]) && $presencesExistantes[$etudiant->id] === 'Present') ? 'checked' : '' }}>
                                            <div class="presence-option present-option {{ (isset($presencesExistantes[$etudiant->id]) && $presencesExistantes[$etudiant->id] === 'Present') ? 'selected' : '' }}">
                                                <i class="fas fa-check text-lg text-green-600"></i>
                                                <span class="ml-1 text-sm text-green-600">Présent</span>
                                            </div>
                                        </label>

                                        <!-- Retard -->
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio"
                                                   name="presences[{{ $etudiant->id }}]"
                                                   value="Retard"
                                                   class="hidden presence-radio"
                                                   {{ (isset($presencesExistantes[$etudiant->id]) && $presencesExistantes[$etudiant->id] === 'Retard') ? 'checked' : '' }}>
                                            <div class="presence-option retard-option {{ (isset($presencesExistantes[$etudiant->id]) && $presencesExistantes[$etudiant->id] === 'Retard') ? 'selected' : '' }}">
                                                <i class="fas fa-clock text-lg text-orange-600"></i>
                                                <span class="ml-1 text-sm text-orange-600">Retard</span>
                                            </div>
                                        </label>

                                        <!-- Absent -->
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio"
                                                   name="presences[{{ $etudiant->id }}]"
                                                   value="Absent"
                                                   class="hidden presence-radio"
                                                   {{ (isset($presencesExistantes[$etudiant->id]) && $presencesExistantes[$etudiant->id] === 'Absent') ? 'checked' : '' }}>
                                            <div class="presence-option absent-option {{ (isset($presencesExistantes[$etudiant->id]) && $presencesExistantes[$etudiant->id] === 'Absent') ? 'selected' : '' }}">
                                                <i class="fas fa-times text-lg text-red-600"></i>
                                                <span class="ml-1 text-sm text-red-600">Absent</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('coordinateur.absences.dashboard') }}"
                               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition">
                                <i class="fas fa-times mr-2"></i>Annuler
                            </a>
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-save mr-2"></i>Enregistrer les présences
                            </button>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun étudiant trouvé</h3>
                            <p class="text-gray-500">Aucun étudiant n'est inscrit dans cette classe.</p>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.presence-option {
    @apply flex items-center px-4 py-2 border-2 border-gray-300 rounded-lg transition-all duration-200;
}

.present-option {
    @apply text-green-700 border-green-400 bg-green-50 hover:border-green-500 hover:bg-green-100;
}

.present-option.selected {
    @apply border-green-600 bg-green-200 text-green-900;
}

.retard-option {
    @apply text-orange-700 border-orange-400 bg-orange-50 hover:border-orange-500 hover:bg-orange-100;
}

.retard-option.selected {
    @apply border-orange-600 bg-orange-200 text-orange-900;
}

.absent-option {
    @apply text-red-700 border-red-400 bg-red-50 hover:border-red-500 hover:bg-red-100;
}

.absent-option.selected {
    @apply border-red-600 bg-red-200 text-red-900;
}
</style>

<script>
function marquerTousPresents() {
    const presentRadios = document.querySelectorAll('input[value="Present"]');
    presentRadios.forEach(radio => {
        radio.checked = true;
        updateOptionStyle(radio);
    });
    updateCounters();
}

function marquerTousRetards() {
    const retardRadios = document.querySelectorAll('input[value="Retard"]');
    retardRadios.forEach(radio => {
        radio.checked = true;
        updateOptionStyle(radio);
    });
    updateCounters();
}

function marquerTousAbsents() {
    const absentRadios = document.querySelectorAll('input[value="Absent"]');
    absentRadios.forEach(radio => {
        radio.checked = true;
        updateOptionStyle(radio);
    });
    updateCounters();
}

function updateOptionStyle(radio) {
    // Retirer la classe selected de tous les options du même groupe
    const groupName = radio.name;
    const allOptionsInGroup = document.querySelectorAll(`input[name="${groupName}"] + .presence-option`);
    allOptionsInGroup.forEach(option => option.classList.remove('selected'));

    // Ajouter la classe selected à l'option sélectionnée
    const selectedOption = radio.nextElementSibling;
    if (selectedOption) {
        selectedOption.classList.add('selected');
    }
}

// Gérer les clics sur les radios
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('.presence-radio');
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            updateOptionStyle(this);
            updateCounters();
        });
    });

    updateCounters();
});

function updateCounters() {
    const presents = document.querySelectorAll('input[value="Present"]:checked').length;
    const retards = document.querySelectorAll('input[value="Retard"]:checked').length;
    const absents = document.querySelectorAll('input[value="Absent"]:checked').length;
    document.getElementById('counter-present').textContent = `Présents : ${presents}`;
    document.getElementById('counter-retard').textContent = `Retards : ${retards}`;
    document.getElementById('counter-absent').textContent = `Absents : ${absents}`;
}
</script>
@endsection
