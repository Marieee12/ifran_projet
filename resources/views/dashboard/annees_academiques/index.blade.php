@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto bg-white rounded-xl shadow-md p-6 mt-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Liste des Années Académiques</h1>
        <a href="{{ route('annees_academiques.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>Ajouter une Année Académique
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
        <thead>
            <tr>
                <th class="py-3 px-4 border-b text-left text-gray-600">Année</th>
                <th class="py-3 px-4 border-b text-left text-gray-600">Date début</th>
                <th class="py-3 px-4 border-b text-left text-gray-600">Date fin</th>
                <th class="py-3 px-4 border-b text-left text-gray-600">Statut</th>
                <th class="py-3 px-4 border-b text-left text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($annees as $annee)
            <tr class="hover:bg-gray-50">
                <td class="py-2 px-4 border-b font-semibold">{{ $annee->nom_annee }}</td>
                <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($annee->date_debut)->format('d/m/Y') }}</td>
                <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($annee->date_fin)->format('d/m/Y') }}</td>
                <td class="py-2 px-4 border-b">
                    @if($annee->est_actuelle)
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Actuelle</span>
                    @else
                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Inactive</span>
                    @endif
                </td>
                <td class="py-2 px-4 border-b">
                    <div class="flex space-x-2">
                        <a href="{{ route('annees_academiques.edit', $annee) }}"
                           class="text-blue-500 hover:text-blue-700 transition">
                            <i class="fas fa-edit mr-1"></i>Modifier
                        </a>
                        @if(!$annee->est_actuelle)
                            <form action="{{ route('annees_academiques.destroy', $annee) }}" method="POST"
                                  class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette année académique ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                    <i class="fas fa-trash mr-1"></i>Supprimer
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400">
                                <i class="fas fa-lock mr-1"></i>Année actuelle
                            </span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-4 px-4 text-center text-gray-500">Aucune année académique trouvée.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
