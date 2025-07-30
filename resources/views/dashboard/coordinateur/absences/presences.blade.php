@extends('layouts.app')

@section('content')
<div class="flex-1 overflow-y-auto bg-gray-100">
    <!-- En-tête -->
    <header class="bg-white shadow-sm">
        <div class="px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Feuille de Présence</h1>
                <p class="text-gray-600 mt-1">
                    {{ $cours->matiere->nom_matiere ?? 'N/A' }} - {{ $cours->classe->nom_classe_complet ?? 'N/A' }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ \Carbon\Carbon::parse($cours->date_seance)->format('d/m/Y') }} de {{ $cours->heure_debut }} à {{ $cours->heure_fin }}
                    @if($cours->salle) - {{ $cours->salle }} @endif
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('coordinateur.cours.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour aux cours
                </a>
            </div>
        </div>
    </header>

    <div class="px-6 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($etudiants->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun étudiant dans cette classe</h3>
                <p class="text-gray-500">Veuillez d'abord ajouter des étudiants à cette classe.</p>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Liste des étudiants ({{ $etudiants->count() }})
                    </h3>
                    <div class="flex space-x-2">
                        <button type="button" onclick="marquerTousPresents()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm">
                            <i class="fas fa-check-double mr-1"></i>Tous présents
                        </button>
                        <button type="button" onclick="marquerTousAbsents()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm">
                            <i class="fas fa-times mr-1"></i>Tous absents
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('coordinateur.cours.presences.store', $cours) }}">
                    @csrf

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Étudiant
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Présence
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut actuel
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($etudiants as $index => $etudiant)
                                    @php
                                        $presence = $presences->get($etudiant->id);
                                        $estPresent = $presence ? $presence->present : true; // Par défaut présent
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">
                                                            {{ substr($etudiant->user->prenom, 0, 1) }}{{ substr($etudiant->user->nom, 0, 1) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $etudiant->user->prenom }} {{ $etudiant->user->nom }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $etudiant->user->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center space-x-4">
                                                <label class="inline-flex items-center">
                                                    <input type="radio"
                                                           name="presences[{{ $etudiant->id }}]"
                                                           value="1"
                                                           {{ $estPresent ? 'checked' : '' }}
                                                           class="form-radio h-4 w-4 text-green-600 transition duration-150 ease-in-out">
                                                    <span class="ml-2 text-sm text-green-700 font-medium">Présent</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio"
                                                           name="presences[{{ $etudiant->id }}]"
                                                           value="0"
                                                           {{ !$estPresent ? 'checked' : '' }}
                                                           class="form-radio h-4 w-4 text-red-600 transition duration-150 ease-in-out">
                                                    <span class="ml-2 text-sm text-red-700 font-medium">Absent</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($presence)
                                                @if($presence->present)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        <i class="fas fa-check mr-1"></i>Présent
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        <i class="fas fa-times mr-1"></i>Absent
                                                        @if($presence->justifie)
                                                            <span class="ml-1 text-xs">(Justifié)</span>
                                                        @endif
                                                    </span>
                                                @endif
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    <i class="fas fa-question mr-1"></i>Non marqué
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Cliquez sur "Enregistrer" pour sauvegarder les présences
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                            <i class="fas fa-save mr-2"></i>Enregistrer les présences
                        </button>
                    </div>
                </form>
            </div>

            <!-- Résumé rapide -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total étudiants</p>
                            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $etudiants->count() }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Présents actuellement</p>
                            <p class="text-2xl font-bold text-green-600 mt-1" id="count-presents">
                                {{ $presences->where('present', true)->count() }}
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Absents actuellement</p>
                            <p class="text-2xl font-bold text-red-600 mt-1" id="count-absents">
                                {{ $presences->where('present', false)->count() }}
                            </p>
                        </div>
                        <div class="bg-red-100 p-3 rounded-full">
                            <i class="fas fa-times text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function marquerTousPresents() {
        const radiosPresent = document.querySelectorAll('input[type="radio"][value="1"]');
        radiosPresent.forEach(radio => {
            radio.checked = true;
        });
        updateCounts();
    }

    function marquerTousAbsents() {
        const radiosAbsent = document.querySelectorAll('input[type="radio"][value="0"]');
        radiosAbsent.forEach(radio => {
            radio.checked = true;
        });
        updateCounts();
    }

    function updateCounts() {
        const totalEtudiants = {{ $etudiants->count() }};
        const presentsChecked = document.querySelectorAll('input[type="radio"][value="1"]:checked').length;
        const absentsChecked = document.querySelectorAll('input[type="radio"][value="0"]:checked').length;

        document.getElementById('count-presents').textContent = presentsChecked;
        document.getElementById('count-absents').textContent = absentsChecked;
    }

    // Mettre à jour les compteurs quand on change les radios
    document.addEventListener('change', function(e) {
        if (e.target.type === 'radio' && e.target.name.startsWith('presences[')) {
            updateCounts();
        }
    });
</script>
@endsection
