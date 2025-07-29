@extends('layouts.app')

@section('title', 'Mes Absences')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                @if(Auth::user()->hasRole('Parent'))
                    Absences de {{ $etudiant->user->prenom }} {{ $etudiant->user->nom }}
                @else
                    Mes Absences
                @endif
            </h1>

            @if(Auth::user()->hasRole('Parent') && $etudiants->count() > 1)
                <div>
                    <label class="block text-sm font-medium mb-1">Sélectionner un enfant</label>
                    <select onchange="window.location.href='?etudiant_id=' + this.value"
                            class="border rounded px-3 py-2">
                        @foreach($etudiants as $enfant)
                            <option value="{{ $enfant->id }}" {{ $etudiant->id == $enfant->id ? 'selected' : '' }}>
                                {{ $enfant->user->prenom }} {{ $enfant->user->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <!-- Résumé des absences -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="text-2xl font-bold text-red-600">
                    {{ ($absences['non_justifiees'] ?? collect())->count() }}
                </div>
                <div class="text-sm text-red-600">Absences non justifiées</div>
            </div>

            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <div class="text-2xl font-bold text-orange-600">
                    {{ ($absences['justifiees'] ?? collect())->count() }}
                </div>
                <div class="text-sm text-orange-600">Absences justifiées</div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="text-2xl font-bold text-blue-600">
                    {{ ($absences['non_justifiees'] ?? collect())->count() + ($absences['justifiees'] ?? collect())->count() }}
                </div>
                <div class="text-sm text-blue-600">Total des absences</div>
            </div>
        </div>

        <!-- Absences non justifiées -->
        @if(isset($absences['non_justifiees']) && $absences['non_justifiees']->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-semibold mb-4 text-red-600">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Absences Non Justifiées
                </h2>

                <div class="bg-red-50 border border-red-200 rounded-lg overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-red-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-red-800">Date</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-red-800">Matière</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-red-800">Type de cours</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-red-800">Horaire</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-200">
                            @foreach($absences['non_justifiees'] as $absence)
                                <tr class="hover:bg-red-75">
                                    <td class="px-4 py-3 text-sm">
                                        {{ \Carbon\Carbon::parse($absence->seanceCours->date_seance)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium">
                                        {{ $absence->seanceCours->matiere->nom_matiere }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $absence->seanceCours->type_cours === 'Presentiel' ? 'bg-blue-100 text-blue-800' :
                                               ($absence->seanceCours->type_cours === 'E-learning' ? 'bg-green-100 text-green-800' :
                                                'bg-orange-100 text-orange-800') }}">
                                            {{ $absence->seanceCours->type_cours }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ \Carbon\Carbon::parse($absence->seanceCours->heure_debut)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($absence->seanceCours->heure_fin)->format('H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Absences justifiées -->
        @if(isset($absences['justifiees']) && $absences['justifiees']->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-semibold mb-4 text-orange-600">
                    <i class="fas fa-check-circle mr-2"></i>
                    Absences Justifiées
                </h2>

                <div class="bg-orange-50 border border-orange-200 rounded-lg overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-orange-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-orange-800">Date</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-orange-800">Matière</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-orange-800">Type de cours</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-orange-800">Horaire</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-orange-800">Justification</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-orange-200">
                            @foreach($absences['justifiees'] as $absence)
                                <tr class="hover:bg-orange-75">
                                    <td class="px-4 py-3 text-sm">
                                        {{ \Carbon\Carbon::parse($absence->seanceCours->date_seance)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium">
                                        {{ $absence->seanceCours->matiere->nom_matiere }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $absence->seanceCours->type_cours === 'Presentiel' ? 'bg-blue-100 text-blue-800' :
                                               ($absence->seanceCours->type_cours === 'E-learning' ? 'bg-green-100 text-green-800' :
                                                'bg-orange-100 text-orange-800') }}">
                                            {{ $absence->seanceCours->type_cours }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ \Carbon\Carbon::parse($absence->seanceCours->heure_debut)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($absence->seanceCours->heure_fin)->format('H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($absence->justificationAbsence)
                                            <div class="text-gray-700">
                                                <div class="font-medium">{{ $absence->justificationAbsence->motif }}</div>
                                                @if($absence->justificationAbsence->justification)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ Str::limit($absence->justificationAbsence->justification, 100) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Message si aucune absence -->
        @if((!isset($absences['non_justifiees']) || $absences['non_justifiees']->count() === 0) &&
            (!isset($absences['justifiees']) || $absences['justifiees']->count() === 0))
            <div class="text-center py-12">
                <div class="text-6xl text-green-500 mb-4">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-2">Excellent !</h3>
                <p class="text-gray-600">
                    @if(Auth::user()->hasRole('Parent'))
                        Votre enfant n'a aucune absence enregistrée.
                    @else
                        Vous n'avez aucune absence enregistrée.
                    @endif
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Continuez comme ça ! L'assiduité est importante pour la réussite scolaire.
                </p>
            </div>
        @endif

        <!-- Informations importantes -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="font-semibold text-blue-800 mb-2">
                <i class="fas fa-info-circle mr-2"></i>
                Informations importantes
            </h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Un taux d'absence supérieur à 30% dans une matière entraîne un échec automatique</li>
                <li>• Les absences peuvent être justifiées par le coordinateur pédagogique</li>
                <li>• Pour contester une absence, contactez votre coordinateur pédagogique</li>
                @if(Auth::user()->hasRole('Parent'))
                    <li>• Vous recevez automatiquement une notification en cas d'absence excessive</li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection
