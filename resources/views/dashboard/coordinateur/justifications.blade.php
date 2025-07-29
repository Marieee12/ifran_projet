@extends('layouts.app')

@section('content')
<div class="flex-1 overflow-y-auto bg-gray-100">
    <!-- En-tête -->
    <header class="bg-white shadow-sm">
        <div class="px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Justifications d'Absence</h1>
                <p class="text-gray-600 mt-1">Examiner et valider les justifications d'absence</p>
            </div>
            <a href="{{ route('coordinateur.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour au Dashboard
            </a>
        </div>
    </header>

    <div class="px-6 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">En attente</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $justificationsEnAttente ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Validées</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $justificationsValidees ?? 0 }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $justificationsTotal ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des justifications -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Demandes de Justification</h2>
                <div class="flex space-x-2">
                    <select class="px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente">En attente</option>
                        <option value="validees">Validées</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Étudiant</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Absence</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Justification</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Date demande</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Statut</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($justifications as $justification)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    <div class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">
                                            {{ $justification->etudiant->user->prenom ?? 'N/A' }}
                                            {{ $justification->etudiant->user->nom ?? 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-600">{{ $justification->etudiant->user->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-medium text-gray-800">{{ $justification->absence->cours->matiere->nom_matiere ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($justification->absence->date_absence ?? now())->format('d/m/Y') }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $justification->absence->cours->classe->nom_classe ?? 'N/A' }}
                                </p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-gray-800">{{ Str::limit($justification->raison ?? 'Aucune raison fournie', 50) }}</p>
                                @if($justification->document_justificatif)
                                    <span class="inline-flex items-center mt-1 text-xs text-blue-600">
                                        <i class="fas fa-paperclip mr-1"></i>Document joint
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-gray-800">{{ \Carbon\Carbon::parse($justification->created_at ?? now())->format('d/m/Y H:i') }}</p>
                            </td>
                            <td class="py-3 px-4">
                                @if($justification->justifiee_par_id_coordinateur)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                        <i class="fas fa-check mr-1"></i>Validée
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">
                                        <i class="fas fa-clock mr-1"></i>En attente
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex space-x-2">
                                    @if(!$justification->justifiee_par_id_coordinateur)
                                        <form action="{{ route('coordinateur.justification.valider', $justification) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 transition"
                                                    onclick="return confirm('Valider cette justification ?')">
                                                <i class="fas fa-check mr-1"></i>Valider
                                            </button>
                                        </form>
                                        <form action="{{ route('coordinateur.justification.refuser', $justification) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition"
                                                    onclick="return confirm('Refuser cette justification ?')">
                                                <i class="fas fa-times mr-1"></i>Refuser
                                            </button>
                                        </form>
                                    @endif
                                    <button class="text-blue-600 hover:text-blue-800 transition">
                                        <i class="fas fa-eye mr-1"></i>Détails
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                <i class="fas fa-info-circle text-3xl mb-2"></i>
                                <p>Aucune justification en attente</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($justifications->hasPages())
                <div class="mt-6">
                    {{ $justifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
