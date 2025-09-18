@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow-lg rounded-2xl p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            Assigner des étudiants à un parent
        </h2>

        <form action="{{ route('parents.assign') }}" method="POST" class="space-y-6">
            @csrf
            <!-- Sélection du parent -->
            <div>
                <label for="parent_id" class="block font-semibold text-gray-700 mb-2">Sélectionner un parent</label>
                <select name="parent_id" id="parent_id"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 transition">
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}">
                            {{ $parent->user->nom_utilisateur ?? 'Parent '.$parent->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Étudiants -->
            <div>
                <label class="block font-semibold text-gray-700 mb-3">Étudiants à associer</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($etudiants as $etudiant)
                        <label class="flex items-center p-3 bg-gray-50 rounded-lg border hover:border-green-400 cursor-pointer transition">
                            <input type="checkbox" name="etudiant_ids[]" value="{{ $etudiant->id }}"
                                class="form-checkbox text-green-600 rounded focus:ring-green-500">
                            <span class="ml-2 text-gray-700">{{ $etudiant->nom }} ({{ $etudiant->user->nom_utilisateur ?? 'Nom inconnu' }})</span>
                        </label>
                    @endforeach
                </div>
                <p class="text-sm text-gray-500 mt-1">Sélectionnez un ou plusieurs étudiants.</p>
            </div>

            <!-- Bouton -->
            <div>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-md transition font-semibold">
                    Assigner
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des assignations -->
    <div class="mt-10 bg-white shadow-lg rounded-2xl p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Assignations actuelles</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="border px-4 py-2 text-left">Parent</th>
                        <th class="border px-4 py-2 text-left">Étudiants associés</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($parents as $parent)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border px-4 py-3 font-medium text-gray-800">
                                {{ $parent->user->nom_utilisateur ?? 'Parent '.$parent->id }}
                            </td>
                            <td class="border px-4 py-3">
                                @if($parent->etudiants->count())
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($parent->etudiants as $etudiant)
                                            <span class="flex items-center bg-green-50 text-green-700 border border-green-200 rounded-full px-3 py-1 text-sm shadow-sm">
                                                {{ $etudiant->nom }} ({{ $etudiant->user->nom_utilisateur ?? 'Nom inconnu' }})
                                                <form action="{{ route('parents.unassign.etudiant', ['parent' => $parent->id, 'etudiant' => $etudiant->id]) }}" method="POST" class="inline ml-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-500 hover:text-red-700 text-lg leading-none"
                                                        title="Désassigner">&times;
                                                    </button>
                                                </form>
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Aucun étudiant assigné</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- TomSelect.js CDN -->
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script>
    new TomSelect('#parent_id', {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },
        searchField: ['text']
    });
</script>
@endsection
