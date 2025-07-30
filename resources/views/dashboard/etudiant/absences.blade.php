@extends('layouts.dashboard')

@section('title', 'Mes Absences')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Mes Absences</h1>
            <p class="text-gray-600 mt-1">Consultez et justifiez vos absences</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-500">Étudiant</div>
            <div class="font-semibold text-gray-900">{{ $etudiant->nom }} {{ $etudiant->prenom }}</div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $stats['total_absences'] }}</h3>
                    <p class="text-sm text-gray-600">Total Absences</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $stats['absences_justifiees'] }}</h3>
                    <p class="text-sm text-gray-600">Justifiées</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $stats['absences_en_attente'] }}</h3>
                    <p class="text-sm text-gray-600">En Attente</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gray-100">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $stats['absences_non_justifiees'] }}</h3>
                    <p class="text-sm text-gray-600">Non Justifiées</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtres</h3>
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                <input type="date" id="date_debut" name="date_debut" value="{{ $dateDebut }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                <input type="date" id="date_fin" name="date_fin" value="{{ $dateFin }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select id="statut" name="statut"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="justifiee" {{ $statut === 'justifiee' ? 'selected' : '' }}>Justifiées</option>
                    <option value="en_attente" {{ $statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="non_justifiee" {{ $statut === 'non_justifiee' ? 'selected' : '' }}>Non justifiées</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des absences -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Mes Absences</h3>
        </div>

        @if($absences->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matière</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enseignant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($absences as $absence)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($absence->date_saisie)->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $absence->seanceCours->matiere->nom ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $absence->seanceCours->enseignant->user->nom ?? 'N/A' }}
                                        {{ $absence->seanceCours->enseignant->user->prenom ?? '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($absence->seanceCours->heure_debut)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($absence->seanceCours->heure_fin)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($absence->justificationAbsence)
                                        @if($absence->justificationAbsence->statut === 'validee')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Justifiée
                                            </span>
                                        @elseif($absence->justificationAbsence->statut === 'en_attente')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                En attente
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Rejetée
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Non justifiée
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if(!$absence->justificationAbsence)
                                        <button onclick="openJustificationModal({{ $absence->id }})"
                                                class="text-blue-600 hover:text-blue-900">
                                            Justifier
                                        </button>
                                    @elseif($absence->justificationAbsence->statut === 'en_attente')
                                        <span class="text-gray-500">En cours</span>
                                    @elseif($absence->justificationAbsence->statut === 'validee')
                                        <button onclick="showJustificationDetails({{ $absence->justificationAbsence->id }})"
                                                class="text-green-600 hover:text-green-900">
                                            Voir détails
                                        </button>
                                    @else
                                        <button onclick="showJustificationDetails({{ $absence->justificationAbsence->id }})"
                                                class="text-red-600 hover:text-red-900">
                                            Voir motif refus
                                        </button>
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
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune absence trouvée</h3>
                <p class="mt-1 text-sm text-gray-500">Vous n'avez aucune absence pour la période sélectionnée.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal de justification -->
<div id="justificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Justifier l'absence</h3>
            <form id="justificationForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="raison_justification" class="block text-sm font-medium text-gray-700 mb-2">
                        Raison de l'absence *
                    </label>
                    <textarea id="raison_justification" name="raison_justification" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Expliquez la raison de votre absence..." required></textarea>
                </div>
                <div class="mb-4">
                    <label for="document_justificatif" class="block text-sm font-medium text-gray-700 mb-2">
                        Document justificatif (optionnel)
                    </label>
                    <input type="file" id="document_justificatif" name="document_justificatif"
                           accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, JPG, PNG (max 2MB)</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeJustificationModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Soumettre
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
    form.action = `{{ url('etudiant/justifier-absence') }}/${absenceId}`;
    modal.classList.remove('hidden');
}

function closeJustificationModal() {
    const modal = document.getElementById('justificationModal');
    modal.classList.add('hidden');

    // Reset form
    const form = document.getElementById('justificationForm');
    form.reset();
}

function showJustificationDetails(justificationId) {
    // Ici vous pouvez ajouter la logique pour afficher les détails de la justification
    alert('Fonctionnalité de détails à implémenter');
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('justificationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeJustificationModal();
    }
});
</script>
@endsection
