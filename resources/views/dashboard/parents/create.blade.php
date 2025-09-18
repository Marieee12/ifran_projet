@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Créer un parent</h2>
    <form action="{{ route('parents.store') }}" method="POST" class="space-y-4">
        @csrf
        <!-- Ajoute ici les champs du parent -->
        <div>
            <label for="telephone" class="block font-medium">Téléphone</label>
            <input type="text" name="telephone" id="telephone" class="border rounded w-full p-2" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Créer le parent</button>
    </form>
</div>
@endsection
