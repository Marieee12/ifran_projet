@extends('layouts.app')
@section('content')
<div class="bg-gray-100 min-h-screen p-8 flex items-center justify-center ml-64">
    <div class="max-w-5xl w-full bg-white rounded-xl shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Liste des Utilisateurs</h1>
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr>
                    <th class="py-3 px-4 border-b text-center text-gray-600">Nom</th>
                    <th class="py-3 px-4 border-b text-center text-gray-600">Email</th>
                    <th class="py-3 px-4 border-b text-center text-gray-600">Rôle</th>
                    <th class="py-3 px-4 border-b text-center text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b text-center">{{ $user->nom ?? $user->name }}</td>
                    <td class="py-2 px-4 border-b text-center">{{ $user->email }}</td>
                    <td class="py-2 px-4 border-b text-center">{{ $user->role->nom_role ?? 'Non défini' }}</td>
                    <td class="py-2 px-4 border-b text-center">
                        <a href="{{ route('dashboard.utilisateur.edit', $user) }}" class="text-blue-500 hover:underline mr-2"><i class="fas fa-edit"></i> Modifier</a>
                        <form action="{{ route('dashboard.utilisateur.destroy', $user) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline border-0 bg-transparent cursor-pointer"><i class="fas fa-trash"></i> Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-4 px-4 text-center text-gray-500">Aucun utilisateur trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
