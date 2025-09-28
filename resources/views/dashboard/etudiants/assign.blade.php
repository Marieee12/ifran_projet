@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Assigner un étudiant à une classe et un niveau</h2>
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('etudiants.assign') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="etudiant_id" class="block font-medium">Étudiant</label>
            <select name="etudiant_id" id="etudiant_id" class="border rounded w-full p-2" required>
                @foreach($etudiants as $etudiant)
                    <option value="{{ $etudiant->id }}">
                        {{ $etudiant->user->nom ?? 'Nom inconnu' }} {{ $etudiant->user->prenom ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="niveau_id" class="block font-medium">Niveau d'Étude</label>
            <select name="niveau_id" id="niveau_id" class="border rounded w-full p-2" required>
                <option value="">Sélectionnez un niveau</option>
                @foreach($niveaux as $niveau)
                    <option value="{{ $niveau->id }}">{{ str_replace(['Niveau ', 'Année '], '', $niveau->nom_niveau) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="filiere_id" class="block font-medium">Filière</label>
            <select name="filiere_id" id="filiere_id" class="border rounded w-full p-2" required>
                <option value="">Sélectionnez une filière</option>
                @foreach($filieres as $filiere)
                    <option value="{{ $filiere->id }}">{{ $filiere->nom_filiere }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Assigner
        </button>
    </form>

            <div class="mt-8">
            <h3 class="text-xl font-semibold mb-2">Répartition des filières par niveau</h3>
            @foreach($niveaux as $niveau)
                <div class="mb-6 p-4 border rounded bg-gray-50">
                    <h4 class="font-bold text-blue-700 mb-2">{{ str_replace(['Niveau ', 'Année '], '', $niveau->nom_niveau) }}</h4>
                    @php
                        $classesNiveau = $classesParNiveau[$niveau->id] ?? [];
                        $filieres = collect($classesNiveau)->groupBy(function($classe) {
                            return $classe->filiere->nom_filiere ?? 'Filière inconnue';
                        });
                    @endphp
                    @foreach($filieres as $nomFiliere => $classesFiliere)
                        <div class="mb-6 p-4 border rounded bg-white shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-lg text-blue-800">{{ $nomFiliere }}</span>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                                    {{ $classesFiliere->sum(function($classe) { return $classe->etudiants->count(); }) }} étudiant(s)
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($classesFiliere->pluck('etudiants')->flatten() as $etudiant)
                                    <span class="inline-flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="10"/></svg>
                                        {{ $etudiant->user->nom ?? 'Nom inconnu' }} {{ $etudiant->user->prenom ?? '' }}
                                    </span>
                                @endforeach
                                @if($classesFiliere->pluck('etudiants')->flatten()->isEmpty())
                                    <span class="italic text-gray-500">Aucun étudiant</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    @if($filieres->isEmpty())
                        <p class="italic text-gray-500">Aucune filière pour ce niveau.</p>
                    @endif
                </div>
            @endforeach
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const niveauSelect = document.getElementById('niveau_id');
    const filiereSelect = document.getElementById('filiere_id');
    const filieres = @json($filieres);

    // Fonction pour mettre à jour les filières
    function updateFilieres(niveauId) {
        // Réinitialiser le select des filières
        filiereSelect.innerHTML = '<option value="">Sélectionnez une filière</option>';

        if (!niveauId) {
            filiereSelect.innerHTML = '<option value="">Sélectionnez d\'abord un niveau</option>';
            filiereSelect.disabled = true;
            return;
        }

        // Activer le select et ajouter les filières
        filiereSelect.disabled = false;
        filieres.forEach(filiere => {
            const option = document.createElement('option');
            option.value = filiere.id;
            option.textContent = filiere.nom_filiere;
            filiereSelect.appendChild(option);
        });
    }

    // Initialiser l'état désactivé
    filiereSelect.disabled = true;

    // Gérer le changement de niveau
    niveauSelect.addEventListener('change', function() {
        updateFilieres(this.value);
    });
});
</script>
@endpush
@endsection
