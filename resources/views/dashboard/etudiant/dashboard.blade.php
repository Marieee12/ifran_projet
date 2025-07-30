@extends('layouts.dashboard')

@section('title', 'Dashboard Étudiant')

@section('content')
<div class="space-y-6">
    <!-- Header avec informations utilisateur -->
    <div class="bg-gradient-to-r from-blue-600 to-red-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Bonjour, {{ $etudiant->prenom }} ! 👋</h1>
                <p class="text-blue-100 mt-2">Bienvenue sur votre dashboard étudiant</p>
                <p class="text-sm text-blue-200">{{ now()->format('l j F Y') }}</p>
            </div>
            <div class="text-right">
                <div class="bg-white/20 rounded-lg p-4">
                    <div class="text-2xl font-bold">{{ $stats['taux_presence'] }}%</div>
                    <div class="text-sm">Taux de présence</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total séances -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $stats['total_seances'] }}</h3>
                    <p class="text-gray-600 text-sm">Total séances</p>
                </div>
            </div>
        </div>

        <!-- Présences -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $stats['presences'] }}</h3>
                    <p class="text-gray-600 text-sm">Présences</p>
                </div>
            </div>
        </div>

        <!-- Absences -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $stats['absences'] }}</h3>
                    <p class="text-gray-600 text-sm">Absences</p>
                </div>
            </div>
        </div>

        <!-- Retards -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $stats['retards'] }}</h3>
                    <p class="text-gray-600 text-sm">Retards</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique et Prochains cours -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Graphique des présences -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Évolution des présences</h3>
            <div class="h-64">
                <canvas id="presenceChart"></canvas>
            </div>
        </div>

        <!-- Prochains cours -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Prochains cours</h3>
            <div class="space-y-3">
                @forelse($prochainsCours as $cours)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-blue-600 font-bold text-sm">{{ Carbon\Carbon::parse($cours->date_seance)->format('d') }}</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $cours->matiere->nom_matiere ?? 'Matière' }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ Carbon\Carbon::parse($cours->date_seance)->format('D j M') }}
                                à {{ Carbon\Carbon::parse($cours->heure_debut)->format('H:i') }}
                            </p>
                            @if($cours->enseignant && $cours->enseignant->user)
                                <p class="text-xs text-gray-500">{{ $cours->enseignant->user->prenom }} {{ $cours->enseignant->user->nom }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p>Aucun cours prévu cette semaine</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-4">
                <a href="{{ route('etudiant.emploi_temps') }}" class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Voir l'emploi du temps complet
                </a>
            </div>
        </div>
    </div>

    <!-- Actions rapides et dernières absences -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Actions rapides -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Actions rapides</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('etudiant.absences') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Mes absences</span>
                </a>
                <a href="{{ route('etudiant.emploi_temps') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Planning</span>
                </a>
            </div>
        </div>

        <!-- Dernières absences -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Dernières absences</h3>
            <div class="space-y-3">
                @forelse($dernieresAbsences as $absence)
                    <div class="flex items-center p-3 bg-red-50 rounded-lg">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $absence->seanceCours->matiere->nom_matiere ?? 'Matière' }}</h4>
                            <p class="text-sm text-gray-600">{{ Carbon\Carbon::parse($absence->date_saisie)->format('d/m/Y') }}</p>
                            @if($absence->justificationAbsence)
                                <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Justifiée</span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Non justifiée</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>Aucune absence récente </p>
                    </div>
                @endforelse
            </div>
            @if($dernieresAbsences->count() > 0)
                <div class="mt-4">
                    <a href="{{ route('etudiant.absences') }}" class="block w-full text-center bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition-colors">
                        Voir toutes les absences
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Taux de présence en cercle -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Taux de présence global</h3>
        <div class="flex justify-center">
            <div class="relative w-48 h-48">
                <canvas id="attendanceChart" width="192" height="192"></canvas>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-800">{{ $stats['taux_presence'] }}%</div>
                        <div class="text-sm text-gray-600">Présence</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4 mt-6 text-center">
            <div>
                <div class="text-lg font-semibold text-green-600">{{ $stats['presences'] }}</div>
                <div class="text-sm text-gray-600">Présences</div>
            </div>
            <div>
                <div class="text-lg font-semibold text-red-600">{{ $stats['absences'] }}</div>
                <div class="text-sm text-gray-600">Absences</div>
            </div>
            <div>
                <div class="text-lg font-semibold text-yellow-600">{{ $stats['retards'] }}</div>
                <div class="text-sm text-gray-600">Retards</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique en ligne pour l'évolution des présences
    const ctx = document.getElementById('presenceChart').getContext('2d');
    const monthlyData = @json($monthlyStats);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [
                {
                    label: 'Présences',
                    data: monthlyData.map(item => item.presences),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Absences',
                    data: monthlyData.map(item => item.absences),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique en donut pour le taux de présence
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceRate = {{ $stats['taux_presence'] }};

    new Chart(attendanceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Présent', 'Absent'],
            datasets: [{
                data: [attendanceRate, 100 - attendanceRate],
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(229, 231, 235)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-graduation-cap text-blue-500 mr-3"></i>
                        Tableau de Bord Étudiant
                    </h1>
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-6 text-gray-600">
                        <p><strong>Nom :</strong> {{ $etudiant->user->prenom }} {{ $etudiant->user->nom }}</p>
                        <p><strong>Classe :</strong> {{ $etudiant->classe->nom_classe_complet ?? 'Non assigné' }}</p>
                        <p><strong>Numéro étudiant :</strong> {{ $etudiant->numero_etudiant ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $stats['taux_presence'] >= 90 ? 'bg-green-100 text-green-800' :
                           ($stats['taux_presence'] >= 75 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        <i class="fas fa-chart-line mr-2"></i>
                        Assiduité : {{ $stats['taux_presence'] }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_seances'] }}</p>
                        <p class="text-gray-600">Total séances</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['presences'] }}</p>
                        <p class="text-gray-600">Présences</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['retards'] }}</p>
                        <p class="text-gray-600">Retards</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['absences'] }}</p>
                        <p class="text-gray-600">Absences</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('etudiant.absences') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-red-100 p-4 rounded-full mb-4 group-hover:bg-red-200 transition-colors duration-200">
                        <i class="fas fa-calendar-times text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Mes Absences</h3>
                    <p class="text-sm text-gray-600">Consulter et justifier mes absences</p>
                </div>
            </a>

            <a href="{{ route('etudiant.emploi_temps') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-blue-100 p-4 rounded-full mb-4 group-hover:bg-blue-200 transition-colors duration-200">
                        <i class="fas fa-calendar-alt text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Emploi du Temps</h3>
                    <p class="text-sm text-gray-600">Consulter mon planning de cours</p>
                </div>
            </a>

            <a href="#" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-green-100 p-4 rounded-full mb-4 group-hover:bg-green-200 transition-colors duration-200">
                        <i class="fas fa-user text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Mon Profil</h3>
                    <p class="text-sm text-gray-600">Modifier mes informations personnelles</p>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Prochaines séances -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-clock text-blue-500 mr-2"></i>
                        Prochaines Séances
                    </h2>
                </div>

                @if($prochainesSeances->count() > 0)
                    <div class="p-6 space-y-4">
                        @foreach($prochainesSeances as $seance)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">{{ $seance->matiere->nom_matiere ?? 'N/A' }}</h3>
                                    <div class="text-sm text-gray-500 mt-1 space-y-1">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar text-xs mr-2"></i>
                                            {{ $seance->date_seance->format('d/m/Y') }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-clock text-xs mr-2"></i>
                                            {{ $seance->heure_debut }} - {{ $seance->heure_fin }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-user text-xs mr-2"></i>
                                            {{ $seance->enseignant->user->prenom ?? 'N/A' }} {{ $seance->enseignant->user->nom ?? '' }}
                                        </div>
                                        @if($seance->salle)
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marker-alt text-xs mr-2"></i>
                                            {{ $seance->salle }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <span class="text-xs px-2 py-1 rounded-full
                                        {{ $seance->type_cours === 'Presentiel' ? 'bg-green-100 text-green-800' :
                                           ($seance->type_cours === 'E-learning' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                                        {{ $seance->type_cours }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <i class="fas fa-calendar text-4xl mb-3"></i>
                        <p>Aucune séance prévue cette semaine</p>
                    </div>
                @endif
            </div>

            <!-- Mes absences récentes -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        Mes Absences Récentes
                    </h2>
                    <a href="{{ route('etudiant.absences') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                @if($mesAbsences->count() > 0)
                    <div class="p-6 space-y-4">
                        @foreach($mesAbsences as $absence)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">{{ $absence->seanceCours->matiere->nom_matiere ?? 'N/A' }}</h3>
                                    <div class="text-sm text-gray-500 mt-1">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar text-xs mr-2"></i>
                                            {{ $absence->seanceCours->date_seance->format('d/m/Y') ?? 'N/A' }}
                                        </div>
                                        <div class="flex items-center mt-1">
                                            <i class="fas fa-user text-xs mr-2"></i>
                                            {{ $absence->seanceCours->enseignant->user->prenom ?? 'N/A' }} {{ $absence->seanceCours->enseignant->user->nom ?? '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4 text-right">
                                    @if($absence->justificationAbsence)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $absence->justificationAbsence->statut === 'validee' ? 'bg-green-100 text-green-800' :
                                               ($absence->justificationAbsence->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($absence->justificationAbsence->statut) }}
                                        </span>
                                    @else
                                        <button class="text-blue-600 hover:text-blue-800 text-sm">
                                            Justifier
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <i class="fas fa-check-circle text-4xl mb-3 text-green-400"></i>
                        <p>Aucune absence récente - Félicitations !</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Séances récentes -->
        @if($seancesRecentes->count() > 0)
        <div class="mt-8 bg-white rounded-lg shadow-sm border">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-history text-gray-500 mr-2"></i>
                    Séances Récentes
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($seancesRecentes as $seance)
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <h3 class="font-medium text-gray-900 mb-2">{{ $seance->matiere->nom_matiere ?? 'N/A' }}</h3>
                            <div class="text-sm text-gray-500 space-y-1">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar text-xs mr-2"></i>
                                    {{ $seance->date_seance->format('d/m/Y') }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-xs mr-2"></i>
                                    {{ $seance->heure_debut }} - {{ $seance->heure_fin }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-user text-xs mr-2"></i>
                                    {{ $seance->enseignant->user->prenom ?? 'N/A' }} {{ $seance->enseignant->user->nom ?? '' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
