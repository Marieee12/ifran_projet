@extends('layouts.app')

@section('title', 'Emploi du Temps')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Emploi du Temps</h1>

            <div class="flex space-x-4">
                <!-- Navigation semaine -->
                <div class="flex items-center space-x-2">
                    <a href="?semaine={{ \Carbon\Carbon::parse($semaine)->subWeek()->format('Y-m-d') }}"
                       class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        ← Semaine précédente
                    </a>
                    <span class="font-medium">
                        Semaine du {{ \Carbon\Carbon::parse($semaine)->startOfWeek()->format('d/m/Y') }}
                    </span>
                    <a href="?semaine={{ \Carbon\Carbon::parse($semaine)->addWeek()->format('Y-m-d') }}"
                       class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Semaine suivante →
                    </a>
                </div>

                @if(Auth::user()->hasRole('Coordinateur Pédagogique'))
                    <button onclick="openCreateSeanceModal()"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Créer une séance
                    </button>
                @endif
            </div>
        </div>

        <!-- Grille emploi du temps -->
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2 w-20">Heure</th>
                        <th class="border border-gray-300 px-4 py-2">Lundi</th>
                        <th class="border border-gray-300 px-4 py-2">Mardi</th>
                        <th class="border border-gray-300 px-4 py-2">Mercredi</th>
                        <th class="border border-gray-300 px-4 py-2">Jeudi</th>
                        <th class="border border-gray-300 px-4 py-2">Vendredi</th>
                        <th class="border border-gray-300 px-4 py-2">Samedi</th>
                    </tr>
                </thead>
                <tbody>
                    @for($heure = 8; $heure <= 18; $heure++)
                        <tr>
                            <td class="border border-gray-300 px-2 py-4 text-center font-medium bg-gray-50">
                                {{ sprintf('%02d:00', $heure) }}
                            </td>
                            @for($jour = 0; $jour < 6; $jour++)
                                @php
                                    $dateJour = \Carbon\Carbon::parse($semaine)->startOfWeek()->addDays($jour);
                                    $seancesJour = $seances->filter(function($seance) use ($dateJour, $heure) {
                                        $dateSeance = \Carbon\Carbon::parse($seance->date_seance);
                                        $heureDebut = \Carbon\Carbon::parse($seance->heure_debut)->hour;
                                        return $dateSeance->isSameDay($dateJour) && $heureDebut == $heure;
                                    });
                                @endphp
                                <td class="border border-gray-300 px-2 py-4 align-top min-h-20">
                                    @foreach($seancesJour as $seance)
                                        <div class="mb-2 p-2 rounded text-xs
                                            {{ $seance->type_cours === 'Presentiel' ? 'bg-blue-100 border-l-4 border-blue-500' :
                                               ($seance->type_cours === 'E-learning' ? 'bg-green-100 border-l-4 border-green-500' :
                                                'bg-orange-100 border-l-4 border-orange-500') }}
                                            {{ $seance->est_annulee ? 'opacity-50 line-through' : '' }}">

                                            <div class="font-semibold">{{ $seance->matiere->nom_matiere ?? 'Matière' }}</div>
                                            <div class="text-gray-600">{{ $seance->classe->nom_classe ?? 'Classe' }}</div>
                                            <div class="text-gray-500">
                                                {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}
                                            </div>

                                            @if($seance->type_cours === 'Presentiel' && $seance->enseignant)
                                                <div class="text-gray-500 text-xs">
                                                    {{ $seance->enseignant->user->prenom ?? '' }} {{ $seance->enseignant->user->nom ?? '' }}
                                                </div>
                                            @endif

                                            @if($seance->salle)
                                                <div class="text-gray-500 text-xs">Salle: {{ $seance->salle }}</div>
                                            @endif

                                            <div class="mt-1 flex space-x-1">
                                                @if(Auth::user()->canManagePresences())
                                                    @php
                                                        $canManage = false;
                                                        if (Auth::user()->hasRole('Coordinateur Pédagogique') && in_array($seance->type_cours, ['E-learning', 'Workshop'])) {
                                                            $canManage = true;
                                                        } elseif (Auth::user()->hasRole('Enseignant') && $seance->type_cours === 'Presentiel' && $seance->id_enseignant === Auth::user()->enseignant?->id) {
                                                            $canManage = true;
                                                        } elseif (Auth::user()->hasRole('Administrateur')) {
                                                            $canManage = true;
                                                        }
                                                    @endphp

                                                    @if($canManage && !$seance->est_annulee)
                                                        <a href="{{ route(Auth::user()->hasRole('Coordinateur Pédagogique') ? 'coordinateur.seance.presences' : 'enseignant.seance.presences', $seance) }}"
                                                           class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                                            Présences
                                                        </a>
                                                    @endif
                                                @endif

                                                @if(Auth::user()->hasRole('Coordinateur Pédagogique') && !$seance->est_annulee)
                                                    <button onclick="openCancelSeanceModal({{ $seance->id }})"
                                                            class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                                        Annuler
                                                    </button>
                                                @endif
                                            </div>

                                            @if($seance->est_annulee)
                                                <div class="text-red-500 text-xs mt-1">
                                                    Annulée: {{ $seance->raison_annulation }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Légende -->
        <div class="mt-4 flex justify-center space-x-6 text-sm">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-blue-100 border-l-4 border-blue-500"></div>
                <span>Présentiel</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-green-100 border-l-4 border-green-500"></div>
                <span>E-learning</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-orange-100 border-l-4 border-orange-500"></div>
                <span>Workshop</span>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->hasRole('Coordinateur Pédagogique'))
<!-- Modal pour créer une séance -->
<div id="createSeanceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-96 max-w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Créer une nouvelle séance</h3>
            <form action="{{ route('coordinateur.seance.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <!-- Les champs du formulaire seront ajoutés ici -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Type de cours</label>
                        <select name="type_cours" class="w-full border rounded px-3 py-2" required>
                            <option value="">Sélectionner...</option>
                            <option value="E-learning">E-learning</option>
                            <option value="Workshop">Workshop</option>
                        </select>
                    </div>
                    <!-- Autres champs... -->
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeCreateSeanceModal()"
                            class="px-4 py-2 text-gray-600 border rounded hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
function openCreateSeanceModal() {
    document.getElementById('createSeanceModal').classList.remove('hidden');
}

function closeCreateSeanceModal() {
    document.getElementById('createSeanceModal').classList.add('hidden');
}

function openCancelSeanceModal(seanceId) {
    // Implémenter la modal d'annulation
    if (confirm('Êtes-vous sûr de vouloir annuler cette séance ?')) {
        // Rediriger vers le formulaire d'annulation
        // À implémenter
    }
}
</script>
@endsection
