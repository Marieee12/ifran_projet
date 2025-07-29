@extends('layouts.app')

@section('content')
<div class="flex-1 overflow-y-auto bg-gray-100">
    <!-- En-tête -->
    <header class="bg-white shadow-sm">
        <div class="px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Modifier le Cours</h1>
                <p class="text-gray-600 mt-1">Modifiez les informations du cours</p>
            </div>
            <a href="{{ route('coordinateur.cours.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
            </a>
        </div>
    </header>

    <div class="px-6 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow-md p-6">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('coordinateur.cours.update', $cours) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Classe -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-semibold mb-2">Classe</label>
                            <select name="id_classe" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Sélectionner une classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ old('id_classe', $cours->id_classe) == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom_classe_complet }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Matière -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-semibold mb-2">Matière</label>
                            <select name="id_matiere" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Sélectionner une matière</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" {{ old('id_matiere', $cours->id_matiere) == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom_matiere }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Enseignant -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-semibold mb-2">Enseignant</label>
                            <select name="id_enseignant" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Sélectionner un enseignant (optionnel)</option>
                                @foreach($enseignants as $enseignant)
                                    <option value="{{ $enseignant->id }}" {{ old('id_enseignant', $cours->id_enseignant) == $enseignant->id ? 'selected' : '' }}>
                                        {{ $enseignant->user->prenom }} {{ $enseignant->user->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Date du cours</label>
                            <input type="date" name="date_seance" value="{{ old('date_seance', $cours->date_seance) }}"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>

                        <!-- Heure de début -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Heure de début</label>
                            <input type="time" name="heure_debut" value="{{ old('heure_debut', $cours->heure_debut) }}"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>

                        <!-- Heure de fin -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Heure de fin</label>
                            <input type="time" name="heure_fin" value="{{ old('heure_fin', $cours->heure_fin) }}"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>

                        <!-- Salle -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Salle</label>
                            <input type="text" name="salle" value="{{ old('salle', $cours->salle) }}"
                                   placeholder="Ex: Salle A1, Laboratoire Info..."
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-semibold mb-2">Description (optionnel)</label>
                            <textarea name="description" rows="3"
                                      placeholder="Description du cours, objectifs, matériel nécessaire..."
                                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $cours->description) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('coordinateur.cours.index') }}"
                           class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                            Annuler
                        </a>
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-save mr-2"></i>Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
