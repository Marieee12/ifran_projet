@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-3xl font-semibold text-gray-800 mb-8">Tableau de Bord Enseignant</h1>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total des Cours</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $totalCours }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Cours à Venir</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $coursAVenir }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('enseignant.presences') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-purple-100 p-4 rounded-full mb-4 group-hover:bg-purple-200 transition-colors duration-200">
                    <i class="fas fa-clipboard-check text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Feuille de Présence</h3>
                <p class="text-sm text-gray-600">Marquer les présences pour vos cours d'aujourd'hui</p>
            </div>
        </a>

        <a href="{{ route('enseignant.cours') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-indigo-100 p-4 rounded-full mb-4 group-hover:bg-indigo-200 transition-colors duration-200">
                    <i class="fas fa-book-reader text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Mes Cours</h3>
                <p class="text-sm text-gray-600">Voir la liste de tous vos cours</p>
            </div>
        </a>
    </div>
</div>
@endsection
