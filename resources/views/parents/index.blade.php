<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFRAN TRACK - Dashboard Parent</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .calendar-day-red {
            background-color: #ef4444;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-gray-100 antialiased">
    <div class="flex h-screen">
        <!-- Barre Latérale (Sidebar) - Design de la maquette -->
        <aside class="w-64 bg-gray-800 text-gray-200 flex flex-col rounded-tr-xl rounded-br-xl shadow-lg">
            <div class="p-6 text-2xl font-bold text-white border-b border-gray-700">
                IFRAN TRACK
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('parent.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg bg-gray-700 text-white font-semibold shadow-md">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-user-times mr-3"></i>
                    <span>Absences</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-file-alt mr-3"></i>
                    <span>Justifications</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    <span>Emploi du temps</span>
                </a>

                <div class="text-xs font-semibold uppercase text-gray-400 mt-6 mb-4 pt-4 border-t border-gray-700">Paramètres</div>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-user-circle mr-3"></i>
                    <span>Mon Profil</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200 w-full text-left">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </nav>
            <div class="p-4 text-center text-sm text-gray-500 border-t border-gray-700">
                &copy; 2025 IFRAN TRACK. Tous droits réservés.
            </div>
        </aside>

        <!-- Contenu Principal du Dashboard -->
        <main class="flex-1 p-8 overflow-y-auto">
            <!-- En-tête de la Page -->
            <header class="flex items-center justify-between bg-white p-6 rounded-xl shadow-md mb-8">
                <div class="flex flex-col">
                    <h1 class="text-3xl font-bold text-gray-800">DASHBOARD PARENT</h1>
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="text-gray-600 hover:text-blue-500 transition-colors duration-200">
                        <i class="fas fa-bell text-2xl"></i>
                    </button>
                    <div class="flex items-center space-x-2">
                        <img src="https://placehold.co/40x40/cccccc/ffffff?text=PR" alt="Avatar Parent" class="w-10 h-10 rounded-full border-2 border-blue-500">
                        <span class="font-semibold text-gray-700">Nom Parent</span>
                        <i class="fas fa-chevron-down text-gray-500 text-sm"></i>
                    </div>
                </div>
            </header>

            <!-- Contenu du Dashboard - Organisé en grille pour les colonnes -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Colonne Gauche (Bienvenue et Calendrier) -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Carte de Bienvenue -->
                    <section>
                        <div class="bg-gray-800 text-white p-6 rounded-xl shadow-lg text-center">
                            <h2 class="text-2xl font-semibold mb-2">Bienvenue, Parent</h2>
                            <p class="text-gray-300">Passez une bonne journée</p>
                        </div>
                    </section>

                    <!-- Calendrier -->
                    <section>
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <div class="flex justify-between items-center mb-4">
                                <button class="text-gray-600 hover:text-gray-800"><i class="fas fa-chevron-left text-xl"></i></button>
                                <h3 class="text-xl font-semibold text-gray-800">Juillet 2025</h3>
                                <button class="text-gray-600 hover:text-gray-800"><i class="fas fa-chevron-right text-xl"></i></button>
                            </div>
                            <div class="grid grid-cols-7 text-center text-sm font-medium text-gray-500 mb-2">
                                <span>Lun</span>
                                <span>Mar</span>
                                <span>Mer</span>
                                <span>Jeu</span>
                                <span>Ven</span>
                                <span>Sam</span>
                                <span>Dim</span>
                            </div>
                            <div class="grid grid-cols-7 gap-2">
                                <!-- Jours vides pour aligner le 1er -->
                                @for ($i = 0; $i < 1; $i++) {{-- Pour aligner le 1er Juillet sur le Mardi --}}
                                    <div></div>
                                @endfor
                                <!-- Jours du mois (placeholders) -->
                                @foreach($calendarDays as $day)
                                    <div class="h-10 rounded-lg flex items-center justify-center text-gray-800 font-bold {{ $day['class'] }}">
                                        {{ $day['day'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <!-- Section Absences (Liste des absences récentes) -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Absences</h2>
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            @forelse($absencesRecentes as $absence)
                                <div class="flex items-center justify-between p-4 mb-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-800">{{ $absence['enfant'] }} - {{ $absence['cours'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $absence['jour'] }} à {{ $absence['horaire'] }}</p>
                                    </div>
                                    <span class="text-red-500 font-semibold">Absent(e)</span>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center">Aucune absence récente à afficher pour vos enfants.</p>
                            @endforelse
                        </div>
                    </section>
                </div>

                <!-- Colonne Droite (Justifications) -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Section Justifications -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Justifications</h2>
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            @forelse($justificationsEnAttente as $justification)
                                <div class="p-4 mb-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-md font-semibold text-gray-800">Enfant: {{ $justification['enfant'] }}</p>
                                    <p class="text-sm text-gray-600">Cours: {{ $justification['cours'] }}</p>
                                    <p class="text-sm text-gray-600">Date: {{ $justification['jour'] }} {{ $justification['horaire'] }}</p>
                                    <p class="text-sm text-gray-600">Statut: <span class="font-semibold text-yellow-600">{{ $justification['statut'] }}</span></p>
                                    <div class="mt-3 text-right">
                                        <a href="#" class="text-blue-500 hover:underline text-sm">Voir Détails</a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center">Aucune justification en attente.</p>
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
