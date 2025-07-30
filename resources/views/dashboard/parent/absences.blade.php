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
                    <h1 class="text-2xl font-bold text-gray-900">Suivi des Absences</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filtre par enfant -->
        @if($enfants->count() > 1)
            <div class="mb-6">
                <div class="bg-white shadow rounded-lg p-4">
                    <form method="GET" action="{{ route('parent.absences') }}" class="flex items-center space-x-4">
                        <label for="etudiant_id" class="text-sm font-medium text-gray-700">Filtrer par enfant:</label>
                        <select name="etudiant_id" id="etudiant_id" class="block w-64 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous mes enfants</option>
                            @foreach($enfants as $enfant)
                                <option value="{{ $enfant->id }}" {{ $etudiantSelectionne == $enfant->id ? 'selected' : '' }}>
                                    {{ $enfant->user->prenom }} {{ $enfant->user->nom }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Filtrer
                        </button>
                        @if($etudiantSelectionne)
                            <a href="{{ route('parent.absences') }}" class="text-sm text-gray-500 hover:text-gray-700">Voir tous</a>
                        @endif
                    </form>
                </div>
            </div>
        @endif

        <!-- Liste des absences -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Absences
                    @if($etudiantSelectionne)
                        - {{ $enfants->find($etudiantSelectionne)->user->prenom ?? 'Enfant sélectionné' }}
                    @endif
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Consultez et justifiez les absences de vos enfants.
                </p>
            </div>

            @if($absences->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($absences as $absence)
                        <li class="px-4 py-6 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Avatar de l'enfant -->
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-semibold text-red-600">
                                            {{ substr($absence->etudiant->user->prenom, 0, 1) }}{{ substr($absence->etudiant->user->nom, 0, 1) }}
                                        </span>
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <h4 class="text-base font-medium text-gray-900">
                                                {{ $absence->etudiant->user->prenom }} {{ $absence->etudiant->user->nom }}
                                            </h4>
                                            @if($absence->justificationAbsence)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $absence->justificationAbsence->statut === 'validee' ? 'bg-green-100 text-green-800' :
                                                       ($absence->justificationAbsence->statut === 'refusee' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($absence->justificationAbsence->statut) }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Non justifiée
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-1 grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">Matière:</span> {{ $absence->seanceCours->matiere->nom_matiere }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Enseignant:</span> {{ $absence->seanceCours->enseignant->user->prenom }} {{ $absence->seanceCours->enseignant->user->nom }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Date:</span> {{ $absence->date_saisie->format('d/m/Y à H:i') }}
                                            </div>
                                        </div>

                                        @if($absence->justificationAbsence && $absence->justificationAbsence->raison_justification)
                                            <div class="mt-2 p-3 bg-gray-50 rounded-md">
                                                <p class="text-sm text-gray-700">
                                                    <span class="font-medium">Justification:</span> {{ $absence->justificationAbsence->raison_justification }}
                                                </p>
                                                @if($absence->justificationAbsence->document_justificatif_url)
                                                    <a href="{{ Storage::url($absence->justificationAbsence->document_justificatif_url) }}"
                                                       target="_blank"
                                                       class="inline-flex items-center mt-1 text-xs text-blue-600 hover:text-blue-500">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                        </svg>
                                                        Voir le document justificatif
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex-shrink-0">
                                    @if(!$absence->justificationAbsence)
                                        <button onclick="openJustificationModal({{ $absence->id }})"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Justifier
                                        </button>
                                    @elseif($absence->justificationAbsence->statut === 'en_attente')
                                        <span class="inline-flex items-center px-3 py-2 text-sm text-yellow-700 bg-yellow-100 rounded-md">
                                            En attente de validation
                                        </span>
                                    @elseif($absence->justificationAbsence->statut === 'validee')
                                        <span class="inline-flex items-center px-3 py-2 text-sm text-green-700 bg-green-100 rounded-md">
                                            Justifiée
                                        </span>
                                    @else
                                        <button onclick="openJustificationModal({{ $absence->id }})"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                            Nouvelle justification
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    {{ $absences->appends(request()->query())->links() }}
                </div>
            @else
                <!-- État vide -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune absence trouvée</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($etudiantSelectionne)
                            Cet enfant n'a aucune absence enregistrée.
                        @else
                            Vos enfants n'ont aucune absence enregistrée.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de justification -->
<div id="justificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Justifier l'absence</h3>
            <form id="justificationForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="raison_justification" class="block text-sm font-medium text-gray-700 mb-2">
                        Raison de l'absence *
                    </label>
                    <textarea id="raison_justification"
                              name="raison_justification"
                              rows="4"
                              required
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Expliquez la raison de l'absence..."></textarea>
                </div>

                <div class="mb-6">
                    <label for="document_justificatif" class="block text-sm font-medium text-gray-700 mb-2">
                        Document justificatif (optionnel)
                    </label>
                    <input type="file"
                           id="document_justificatif"
                           name="document_justificatif"
                           accept=".pdf,.jpg,.jpeg,.png"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                    <p class="mt-1 text-xs text-gray-500">PDF, JPG, JPEG, PNG (max. 2MB)</p>
                </div>

                <div class="flex space-x-3">
                    <button type="submit"
                            class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Soumettre
                    </button>
                    <button type="button"
                            onclick="closeJustificationModal()"
                            class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openJustificationModal(absenceId) {
    const modal = document.getElementById('justificationModal');
    const form = document.getElementById('justificationForm');

    // Définir l'action du formulaire
    form.action = `/parent/absences/${absenceId}/justifier`;

    // Réinitialiser le formulaire
    form.reset();

    // Afficher le modal
    modal.classList.remove('hidden');
}

function closeJustificationModal() {
    const modal = document.getElementById('justificationModal');
    modal.classList.add('hidden');
}

// Fermer le modal en cliquant en dehors
window.onclick = function(event) {
    const modal = document.getElementById('justificationModal');
    if (event.target === modal) {
        modal.classList.add('hidden');
    }
}
</script>
@endsection
