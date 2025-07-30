@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-file-alt text-orange-500 mr-3"></i>
                        Justifications en Attente
                    </h1>
                    <p class="text-gray-600">Validation des justifications d'absences</p>
                </div>
                <a href="{{ route('coordinateur.absences.dashboard') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour au tableau de bord
                </a>
            </div>
        </div>

        @if($justifications->count() > 0)
            <div class="space-y-6">
                @foreach($justifications as $justification)
                    <div class="bg-white rounded-lg shadow-sm border">
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Informations de l'étudiant et du cours -->
                                <div class="lg:col-span-2">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-xl font-semibold text-blue-600">
                                                {{ substr($justification->etudiant->user->prenom, 0, 1) }}{{ substr($justification->etudiant->user->nom, 0, 1) }}
                                            </span>
                                        </div>

                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                                {{ $justification->etudiant->user->prenom }} {{ $justification->etudiant->user->nom }}
                                            </h3>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                                <div>
                                                    <p><strong>Classe :</strong> {{ $justification->presence->seanceCours->classe->nom_classe_complet ?? 'N/A' }}</p>
                                                    <p><strong>Matière :</strong> {{ $justification->presence->seanceCours->matiere->nom_matiere ?? 'N/A' }}</p>
                                                </div>
                                                <div>
                                                    <p><strong>Date d'absence :</strong> {{ $justification->presence->created_at->format('d/m/Y H:i') }}</p>
                                                    <p><strong>Justification soumise :</strong> {{ $justification->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Motif de la justification -->
                                    <div class="mt-6">
                                        <h4 class="text-lg font-medium text-gray-900 mb-3">Motif de l'absence</h4>
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                            <p class="text-gray-700">{{ $justification->motif }}</p>
                                        </div>
                                    </div>

                                    <!-- Pièce justificative -->
                                    @if($justification->piece_justificative)
                                        <div class="mt-4">
                                            <h4 class="text-lg font-medium text-gray-900 mb-3">Pièce justificative</h4>
                                            <div class="flex items-center space-x-3">
                                                <i class="fas fa-paperclip text-gray-500"></i>
                                                <a href="{{ Storage::url($justification->piece_justificative) }}"
                                                   target="_blank"
                                                   class="text-blue-600 hover:text-blue-800 underline">
                                                    Voir la pièce jointe
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions de validation -->
                                <div class="lg:col-span-1">
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <h4 class="text-lg font-medium text-gray-900 mb-4">Actions</h4>

                                        <form method="POST" action="{{ route('coordinateur.justifications.traiter', $justification) }}"
                                              class="space-y-4">
                                            @csrf

                                            <!-- Commentaire optionnel -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Commentaire (optionnel)
                                                </label>
                                                <textarea name="commentaire"
                                                          rows="3"
                                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                                          placeholder="Ajouter un commentaire..."></textarea>
                                            </div>

                                            <!-- Boutons d'action -->
                                            <div class="space-y-2">
                                                <button type="submit"
                                                        name="action"
                                                        value="valider"
                                                        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir valider cette justification ?')">
                                                    <i class="fas fa-check mr-2"></i>Valider
                                                </button>

                                                <button type="submit"
                                                        name="action"
                                                        value="refuser"
                                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir refuser cette justification ?')">
                                                    <i class="fas fa-times mr-2"></i>Refuser
                                                </button>
                                            </div>
                                        </form>

                                        <!-- Informations sur le délai -->
                                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <div class="flex items-center">
                                                <i class="fas fa-clock text-yellow-600 mr-2"></i>
                                                <span class="text-sm text-yellow-800">
                                                    Soumise il y a {{ $justification->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $justifications->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-check-circle text-green-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Aucune justification en attente</h3>
                    <p class="text-gray-500 mb-6">Toutes les justifications ont été traitées.</p>
                    <a href="{{ route('coordinateur.absences.dashboard') }}"
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Retour au tableau de bord
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
