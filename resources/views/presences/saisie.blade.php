@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Saisie des présences</h2>

        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-lg mb-2">Informations du cours</h3>
                <p><span class="font-medium">Date :</span> {{ $seance->date_seance->format('d/m/Y') }}</p>
                <p><span class="font-medium">Horaire :</span> {{ $seance->heure_debut }} - {{ $seance->heure_fin }}</p>
                <p><span class="font-medium">Matière :</span> {{ $seance->matiere->nom_matiere }}</p>
                <p><span class="font-medium">Classe :</span> {{ $seance->classe->nom_classe }}</p>
                @if($seance->enseignant)
                <p><span class="font-medium">Enseignant :</span> {{ $seance->enseignant->user->nom }} {{ $seance->enseignant->user->prenom }}</p>
                @endif
            </div>
        </div>

        <form action="{{ route('presences.store', ['seance' => $seance->id]) }}" method="POST" class="space-y-4">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Étudiant</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Présence</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Commentaire</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($etudiants as $etudiant)
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 font-medium text-gray-900">
                                    {{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-center">
                                <select name="presences[{{ $etudiant->id }}][statut]"
                                        class="form-select rounded-md shadow-sm mt-1 block w-full"
                                        required>
                                    <option value="present" {{ isset($presences[$etudiant->id]) && $presences[$etudiant->id]->statut === 'present' ? 'selected' : '' }}>
                                        Présent
                                    </option>
                                    <option value="absent" {{ isset($presences[$etudiant->id]) && $presences[$etudiant->id]->statut === 'absent' ? 'selected' : '' }}>
                                        Absent
                                    </option>
                                    <option value="retard" {{ isset($presences[$etudiant->id]) && $presences[$etudiant->id]->statut === 'retard' ? 'selected' : '' }}>
                                        Retard
                                    </option>
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <input type="text"
                                       name="presences[{{ $etudiant->id }}][commentaire]"
                                       value="{{ isset($presences[$etudiant->id]) ? $presences[$etudiant->id]->commentaire : '' }}"
                                       class="form-input rounded-md shadow-sm mt-1 block w-full"
                                       placeholder="Commentaire optionnel">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-6 space-x-4">
                <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Annuler
                </a>
                <button type="button" onclick="confirmSubmit()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Enregistrer les présences
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de confirmation -->
<div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full" style="z-index: 100;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Confirmation de la saisie</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Êtes-vous sûr de vouloir enregistrer ces présences ?
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmButton" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Confirmer
                </button>
                <button id="cancelButton" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function confirmSubmit() {
        // Afficher le modal
        document.getElementById('confirmationModal').classList.remove('hidden');
    }

    // Gérer le bouton de confirmation
    document.getElementById('confirmButton').addEventListener('click', function() {
        // Soumettre le formulaire
        document.querySelector('form').submit();
    });

    // Gérer le bouton d'annulation
    document.getElementById('cancelButton').addEventListener('click', function() {
        // Cacher le modal
        document.getElementById('confirmationModal').classList.add('hidden');
    });

    // Fermer le modal si on clique en dehors
    document.getElementById('confirmationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
</script>
@endsection
