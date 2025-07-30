@extends('layouts.app')
@section('content')
<!-- Header -->
<header class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">Liste des Filières</h1>
            <a href="{{ route('filieres.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>Ajouter une Filière
            </a>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="bg-gray-100">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($filieres as $filiere)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $filiere->nom_filiere }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <a href="{{ route('filieres.edit', $filiere) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                        <i class="fas fa-edit mr-1"></i>Modifier
                                    </a>
                                    <form action="{{ route('filieres.destroy', $filiere) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette filière ?')">
                                            <i class="fas fa-trash mr-1"></i>Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                                Aucune filière trouvée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
