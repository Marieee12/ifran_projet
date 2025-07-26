@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-6 mt-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Liste des Parents</h1>
    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
        <thead>
            <tr>
                <th class="py-3 px-4 border-b text-left text-gray-600">Nom</th>
                <th class="py-3 px-4 border-b text-left text-gray-600">Email</th>
                <th class="py-3 px-4 border-b text-left text-gray-600">Rôle</th>
                <th class="py-3 px-4 border-b text-left text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($parents as $parent)
            <tr class="hover:bg-gray-50">
                <td class="py-2 px-4 border-b">{{ $parent->nom }}</td>
                <td class="py-2 px-4 border-b">{{ $parent->user->email ?? '-' }}</td>
                <td class="py-2 px-4 border-b">{{ $parent->user->role->nom_role ?? 'Parent' }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('parents.edit', $parent->id) }}" class="text-blue-500 hover:underline mr-2"><i class="fas fa-edit"></i> Modifier</a>
                    <form action="{{ route('parents.destroy', $parent->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline border-0 bg-transparent cursor-pointer"><i class="fas fa-trash"></i> Supprimer</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-4 px-4 text-center text-gray-500">Aucun parent trouvé.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
