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
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Enregistrer les présences
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
