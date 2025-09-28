@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-gray-800">Détails de la Séance</h1>
        <a href="{{ route('enseignant.presences') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour aux présences
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations de la séance</h2>
                <div class="space-y-3">
                    <p><span class="font-medium">Matière :</span> {{ $seance->matiere->nom_matiere }}</p>
                    <p><span class="font-medium">Classe :</span> {{ $seance->classe->nom_classe }}</p>
                    <p><span class="font-medium">Date :</span> {{ \Carbon\Carbon::parse($seance->date_seance)->format('d/m/Y') }}</p>
                    <p><span class="font-medium">Horaire :</span> {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}</p>
                    <p><span class="font-medium">Type de cours :</span> {{ $seance->type_cours }}</p>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Statistiques de présence</h2>
                <div class="space-y-3">
                    <p><span class="font-medium">Total étudiants :</span> {{ $totalEtudiants }}</p>
                    <p><span class="font-medium">Présents :</span> {{ $statsPresence['presents'] }}</p>
                    <p><span class="font-medium">Absents :</span> {{ $statsPresence['absents'] }}</p>
                    <p><span class="font-medium">Retards :</span> {{ $statsPresence['retards'] }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Liste des présences</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure d'enregistrement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Justification</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($presences as $presence)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $presence->etudiant->user->nom }} {{ $presence->etudiant->user->prenom }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $presence->statut_presence === 'present' ? 'bg-green-100 text-green-800' :
                                       ($presence->statut_presence === 'absent' ? 'bg-red-100 text-red-800' :
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($presence->statut_presence) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $presence->date_saisie ? \Carbon\Carbon::parse($presence->date_saisie)->format('d/m/Y H:i') : 'Non enregistré' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($presence->justificationAbsence)
                                    <span class="text-blue-600">Justifié</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
