@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow-md p-6 mt-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Modifier la Matière</h1>
    <form method="POST" action="{{ route('matieres.update', $matiere) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nom de la matière</label>
            <input type="text" name="nom_matiere" value="{{ old('nom_matiere', $matiere->nom_matiere) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Code de la matière</label>
            <input type="text" name="code_matiere" value="{{ old('code_matiere', $matiere->code_matiere) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection
