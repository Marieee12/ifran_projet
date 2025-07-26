@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-6 mt-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Liste des Matières</h1>
    <a href="{{ route('matieres.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg mb-4 inline-block">Ajouter une Matière</a>
    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
        <thead>
            <tr>
                <th class="py-3 px-4 border-b text-left text-gray-600">Nom</th>
                <th class="py-3 px-4 border-b text-left text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matieres as $matiere)
            <tr class="hover:bg-gray-50">
                <td class="py-2 px-4 border-b">{{ $matiere->nom_matiere }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('matieres.edit', $matiere) }}" class="text-blue-500 hover:underline mr-2">Modifier</a>
                    <form action="{{ route('matieres.destroy', $matiere) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline border-0 bg-transparent cursor-pointer">Supprimer</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="py-4 px-4 text-center text-gray-500">Aucune matière trouvée.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
