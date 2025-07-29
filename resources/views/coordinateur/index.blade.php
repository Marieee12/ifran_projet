@extends('layouts.app')
@section('content')
<div class="bg-gray-100 min-h-screen p-8 flex flex-col items-center justify-center">
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl p-8">
        <h1 class="text-3xl font-bold text-blue-800 mb-2">Tableau de bord Coordinateur</h1>
        <p class="mb-8 text-gray-600">Bienvenue sur l'espace coordinateur. Retrouvez ici toutes vos actions principales.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('coordinateur.classes') }}" class="bg-blue-100 hover:bg-blue-200 rounded-xl p-6 flex flex-col items-center shadow transition">
                <i class="fas fa-users text-3xl text-blue-600 mb-2"></i>
                <span class="font-semibold text-lg">Mes Classes</span>
            </a>
            <a href="{{ route('coordinateur.absences') }}" class="bg-red-100 hover:bg-red-200 rounded-xl p-6 flex flex-col items-center shadow transition">
                <i class="fas fa-user-times text-3xl text-red-600 mb-2"></i>
                <span class="font-semibold text-lg">Absences</span>
            </a>
            <a href="{{ route('coordinateur.emploi_temps') }}" class="bg-green-100 hover:bg-green-200 rounded-xl p-6 flex flex-col items-center shadow transition">
                <i class="fas fa-calendar-alt text-3xl text-green-600 mb-2"></i>
                <span class="font-semibold text-lg">Emploi du temps</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('coordinateur.creer_cours') }}" class="bg-yellow-100 hover:bg-yellow-200 rounded-xl p-6 flex flex-col items-center shadow transition">
                <i class="fas fa-plus-circle text-3xl text-yellow-600 mb-2"></i>
                <span class="font-semibold text-lg">Créer un cours</span>
            </a>
            <a href="{{ route('coordinateur.justifications') }}" class="bg-purple-100 hover:bg-purple-200 rounded-xl p-6 flex flex-col items-center shadow transition">
                <i class="fas fa-file-alt text-3xl text-purple-600 mb-2"></i>
                <span class="font-semibold text-lg">Justifications</span>
            </a>
        </div>
    </div>
</div>
@endsection
