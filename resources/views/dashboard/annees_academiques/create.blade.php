@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow-md p-6 mt-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Ajouter une Année Académique</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('annees_academiques.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nom de l'année académique</label>
            <input type="text" name="nom_annee" value="{{ old('nom_annee') }}"
                   placeholder="Ex: 2024-2025"
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <p class="text-sm text-gray-500 mt-1">Format recommandé : AAAA-AAAA</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Date de début</label>
            <input type="date" name="date_debut" value="{{ old('date_debut') }}"
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Date de fin</label>
            <input type="date" name="date_fin" value="{{ old('date_fin') }}"
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="est_actuelle" value="1" {{ old('est_actuelle') ? 'checked' : '' }}
                       class="mr-2 rounded">
                <span class="text-gray-700">Définir comme année académique actuelle</span>
            </label>
            <p class="text-sm text-gray-500 mt-1">Si cochée, cette année deviendra l'année académique active du système</p>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('annees_academiques.index') }}"
               class="bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-600 transition">
                Annuler
            </a>
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
