@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-6 mt-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Liste des Enseignants</h1>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Nom</th>
                <th class="py-2 px-4 border-b">Prénom</th>
                <th class="py-2 px-4 border-b">Email</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enseignants as $enseignant)
            <tr>
                <td class="py-2 px-4 border-b">{{ $enseignant->nom }}</td>
                <td class="py-2 px-4 border-b">{{ $enseignant->prenom }}</td>
                <td class="py-2 px-4 border-b">{{ $enseignant->email }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('enseignants.edit', $enseignant->id) }}" class="text-blue-600 hover:underline">Modifier</a>
                    <form action="{{ route('enseignants.destroy', $enseignant->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
