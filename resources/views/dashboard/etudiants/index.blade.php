@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-6 mt-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Liste des Étudiants</h1>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Nom</th>
                <th class="py-2 px-4 border-b">Numéro Étudiant</th>
                <th class="py-2 px-4 border-b">Date de Naissance</th>
                <th class="py-2 px-4 border-b">Adresse</th>
                <th class="py-2 px-4 border-b">Classe</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($etudiants as $etudiant)
            <tr>
                <td class="py-2 px-4 border-b">{{ $etudiant->user->name ?? '-' }}</td>
                <td class="py-2 px-4 border-b">{{ $etudiant->numero_etudiant }}</td>
                <td class="py-2 px-4 border-b">{{ $etudiant->date_naissance?->format('d/m/Y') ?? '-' }}</td>
                <td class="py-2 px-4 border-b">{{ $etudiant->adresse ?? '-' }}</td>
                <td class="py-2 px-4 border-b">{{ $etudiant->classe->nom ?? '-' }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('etudiants.edit', $etudiant->id) }}" class="text-blue-600 hover:underline">Modifier</a>
                    <form action="{{ route('etudiants.destroy', $etudiant->id) }}" method="POST" class="inline">
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
