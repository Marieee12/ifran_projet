<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFRAN TRACK - Dashboard Enseignant</title>

         @vite(['resources/css/app.css', 'resources/js/app.js'])
         <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .calendar-day-pink {
            background-color: #f8c8dc; /* A light pink */
        }
        .calendar-day-orange {
            background-color: #fca580; /* A light orange */
        }
        .calendar-day-purple {
            background-color: #8b5cf6; /* A medium purple */
            color: white;
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
                <a href="{{ route('enseignant.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg bg-gray-700 text-white font-semibold shadow-md">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    <span>Mon Emploi du Temps</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-check-circle mr-3"></i>
                    <span>Présences</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-book-open mr-3"></i>
                    <span>Mes Prochaines Séances</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-user-times mr-3"></i>
                    <span>Étudiants droppés</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-clipboard-list mr-3"></i>
                    <span>Sessions effectuées</span>
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
                    <h1 class="text-3xl font-bold text-gray-800">DASHBOARD ENSEIGNANT</h1>
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="text-gray-600 hover:text-blue-500 transition-colors duration-200">
                        <i class="fas fa-bell text-2xl"></i>
                    </button>
                    <div class="flex items-center space-x-2">
                        <img src="https://placehold.co/40x40/cccccc/ffffff?text=PF" alt="Avatar Professeur" class="w-10 h-10 rounded-full border-2 border-blue-500">
                        <span class="font-semibold text-gray-700">Nom Prof</span>
                        <i class="fas fa-chevron-down text-gray-500 text-sm"></i>
                    </div>
                </div>
            </header>

            <!-- Contenu du Dashboard - Organisé en grille pour les colonnes -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Colonne Gauche (Contenu principal) -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Carte de Bienvenue -->
                    <section>
                        <div class="bg-gray-800 text-white p-6 rounded-xl shadow-lg text-center">
                            <h2 class="text-2xl font-semibold mb-2">Bienvenue, Professeur</h2>
                            <p class="text-gray-300">Passez une bonne journée</p>
                        </div>
                    </section>

                    <!-- Section Mes Prochaines Séances -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Mes Prochaines Séances</h2>
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <!-- Carte de Séance (Exemple) -->
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 mb-4">
                                <div class="grid grid-cols-2 gap-2 text-gray-700">
                                    <div class="flex items-center"><i class="fas fa-book mr-2 text-blue-500"></i> Matière: <span class="ml-1 font-medium">HTML/CSS</span></div>
                                    <div class="flex items-center"><i class="fas fa-users mr-2 text-green-500"></i> Classe: <span class="ml-1 font-medium">B3Dev</span></div>
                                    <div class="flex items-center"><i class="fas fa-calendar-alt mr-2 text-purple-500"></i> Date: <span class="ml-1 font-medium">22/07/25</span></div>
                                    <div class="flex items-center"><i class="fas fa-clock mr-2 text-yellow-500"></i> Heure: <span class="ml-1 font-medium">09H00 - 12H00</span></div>
                                </div>
                            </div>
                            <!-- Vous pouvez répéter cette structure pour d'autres séances -->
                        </div>
                    </section>

                    <!-- Section Présences -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Présences</h2>
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <p class="text-sm text-gray-500 mb-4">22-07-25</p>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Présent</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Retard</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <!-- Exemple de ligne d'étudiant -->
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Toure Myriam</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                <input type="radio" name="presence_myriam" value="present" class="form-radio text-blue-600">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                <input type="radio" name="presence_myriam" value="retard" class="form-radio text-yellow-600">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                <input type="radio" name="presence_myriam" value="absent" class="form-radio text-red-600">
                                            </td>
                                        </tr>
                                        <!-- Répéter pour d'autres étudiants -->
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Toure Myriam</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                <input type="radio" name="presence_myriam2" value="present" class="form-radio text-blue-600">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                <input type="radio" name="presence_myriam2" value="retard" class="form-radio text-yellow-600">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                <input type="radio" name="presence_myriam2" value="absent" class="form-radio text-red-600">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>

                    <!-- Section Étudiants droppés -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Étudiants droppés</h2>
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <!-- Exemple d'étudiant droppé -->
                            <div class="flex items-center justify-between p-4 mb-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-200 text-blue-800 font-bold rounded-full flex items-center justify-center mr-3">TM</div>
                                    <div>
                                        <p class="text-lg font-semibold text-gray-800">Toure Myriam</p>
                                        <p class="text-sm text-gray-600">HTML/CSS - 70%</p>
                                    </div>
                                </div>
                                <a href="#" class="text-blue-500 hover:underline">Voir Plus</a>
                            </div>
                            <div class="flex items-center justify-between p-4 mb-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-200 text-green-800 font-bold rounded-full flex items-center justify-center mr-3">KN</div>
                                    <div>
                                        <p class="text-lg font-semibold text-gray-800">Kone Noah</p>
                                        <p class="text-sm text-gray-600">Laravel - 50%</p>
                                    </div>
                                </div>
                                <a href="#" class="text-blue-500 hover:underline">Voir Plus</a>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-200 text-purple-800 font-bold rounded-full flex items-center justify-center mr-3">KY</div>
                                    <div>
                                        <p class="text-lg font-semibold text-gray-800">Kone Yoleine</p>
                                        <p class="text-sm text-gray-600">PHP - 60%</p>
                                    </div>
                                </div>
                                <a href="#" class="text-blue-500 hover:underline">Voir Plus</a>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Colonne Droite (Calendrier et Sessions effectuées) -->
                <div class="lg:col-span-1 space-y-8">
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
                                @php
                                    $calendarDays = [
                                        // Juillet 2025 commence un Mardi (jour 2)
                                        // 1er Juillet est un Mardi
                                        ['day' => '1', 'class' => ''], ['day' => '2', 'class' => ''], ['day' => '3', 'class' => ''], ['day' => '4', 'class' => ''], ['day' => '5', 'class' => ''], ['day' => '6', 'class' => ''], ['day' => '7', 'class' => ''],
                                        ['day' => '8', 'class' => ''], ['day' => '9', 'class' => ''], ['day' => '10', 'class' => ''], ['day' => '11', 'class' => ''], ['day' => '12', 'class' => ''], ['day' => '13', 'class' => ''], ['day' => '14', 'class' => ''],
                                        ['day' => '15', 'class' => ''], ['day' => '16', 'class' => ''], ['day' => '17', 'class' => ''], ['day' => '18', 'class' => ''], ['day' => '19', 'class' => ''], ['day' => '20', 'class' => ''], ['day' => '21', 'class' => ''],
                                        ['day' => '22', 'class' => 'calendar-day-pink'], /* Example from image */
                                        ['day' => '23', 'class' => ''],
                                        ['day' => '24', 'class' => 'calendar-day-orange'], /* Example from image */
                                        ['day' => '25', 'class' => 'calendar-day-purple'], /* Example from image */
                                        ['day' => '26', 'class' => ''], ['day' => '27', 'class' => ''], ['day' => '28', 'class' => ''], ['day' => '29', 'class' => ''], ['day' => '30', 'class' => ''], ['day' => '31', 'class' => ''],
                                    ];
                                @endphp
                                @foreach($calendarDays as $day)
                                    <div class="h-10 rounded-lg flex items-center justify-center text-gray-800 font-bold {{ $day['class'] }}">
                                        {{ $day['day'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <!-- Section Sessions effectuées -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex justify-between items-center">
                            Sessions effectuées
                            <a href="#" class="text-blue-500 text-sm font-normal hover:underline">Voir tout</a>
                        </h2>
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <!-- Exemple de session effectuée -->
                            <div class="flex items-center justify-between p-3 mb-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <img src="https://placehold.co/30x30/cccccc/ffffff?text=P" alt="Photo Matière" class="w-8 h-8 rounded-full mr-3">
                                    <div>
                                        <p class="text-md font-semibold text-gray-800">HTML/CSS</p>
                                        <p class="text-xs text-gray-600">17/07 - 22/07</p>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                            <div class="flex items-center justify-between p-3 mb-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <img src="https://placehold.co/30x30/cccccc/ffffff?text=P" alt="Photo Matière" class="w-8 h-8 rounded-full mr-3">
                                    <div>
                                        <p class="text-md font-semibold text-gray-800">Laravel</p>
                                        <p class="text-xs text-gray-600">17/07 - 22/07</p>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                            <div class="flex items-center justify-between p-3 mb-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <img src="https://placehold.co/30x30/cccccc/ffffff?text=P" alt="Photo Matière" class="w-8 h-8 rounded-full mr-3">
                                    <div>
                                        <p class="text-md font-semibold text-gray-800">PHP</p>
                                        <p class="text-xs text-gray-600">17/07 - 22/07</p>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                            <div class="flex items-center justify-between p-3 mb-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <img src="https://placehold.co/30x30/cccccc/ffffff?text=P" alt="Photo Matière" class="w-8 h-8 rounded-full mr-3">
                                    <div>
                                        <p class="text-md font-semibold text-gray-800">Docker</p>
                                        <p class="text-xs text-gray-600">17/07 - 22/07</p>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <img src="https://placehold.co/30x30/cccccc/ffffff?text=P" alt="Photo Matière" class="w-8 h-8 rounded-full mr-3">
                                    <div>
                                        <p class="text-md font-semibold text-gray-800">Docker</p>
                                        <p class="text-xs text-gray-600">17/07 - 22/07</p>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
