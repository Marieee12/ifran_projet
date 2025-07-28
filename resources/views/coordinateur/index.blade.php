<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFRAN TRACK - Dashboard Coordinateur</title>
         @vite(['resources/css/app.css', 'resources/js/app.js'])
         <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 antialiased">
    <div class="flex h-screen">
        <!-- Barre Latérale (Sidebar) - Inspirée de la maquette -->
        <aside class="w-64 bg-gray-800 text-gray-200 flex flex-col rounded-tr-xl rounded-br-xl shadow-lg">
            <div class="p-6 text-2xl font-bold text-white border-b border-gray-700">
                IFRAN TRACK
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <div class="text-xs font-semibold uppercase text-gray-400 mb-4">Menu Principal</div>
                <a href="{{ route('coordinateur.index') }}" class="flex items-center px-4 py-2 rounded-lg bg-gray-700 text-white font-semibold shadow-md">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('coordinateur.classes') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-users mr-3"></i>
                    <span>Liste des classes</span>
                </a>
                <a href="{{ route('coordinateur.emploi_temps') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    <span>Emploi du temps</span>
                </a>
                <a href="{{ route('coordinateur.absences') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-user-times mr-3"></i>
                    <span>Absences</span>
                </a>
                <a href="{{ route('coordinateur.justifications') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-file-alt mr-3"></i>
                    <span>Justifications</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Statistiques</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-book mr-3"></i>
                    <span>Séances</span>
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
                    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="text-gray-600 hover:text-blue-500 transition-colors duration-200">
                        <i class="fas fa-bell text-2xl"></i>
                    </button>
                    <div class="flex items-center space-x-2">
                        <img src="https://placehold.co/40x40/cccccc/ffffff?text=CO" alt="Avatar Coordinateur" class="w-10 h-10 rounded-full border-2 border-blue-500">
                        <span class="font-semibold text-gray-700">Coordinateur</span>
                        <i class="fas fa-chevron-down text-gray-500 text-sm"></i>
                    </div>
                </div>
            </header>

            <!-- Carte de Bienvenue -->
            <section class="mb-8">
                <div class="bg-gray-800 text-white p-6 rounded-xl shadow-lg text-center">
                    <h2 class="text-2xl font-semibold mb-2">Bienvenue, coordinateur</h2>
                    <p class="text-gray-300">Passez une bonne journée</p>
                </div>
            </section>

            <!-- Section Statistiques Rapides (les 4 blocs gris de la maquette) -->
            <section class="mb-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Bloc 1 -->
                    <div class="bg-gray-200 h-32 rounded-xl shadow-md flex items-center justify-center text-gray-600">
                        <!-- Contenu statistique ici -->
                        <p>Statistique 1</p>
                    </div>
                    <!-- Bloc 2 -->
                    <div class="bg-gray-200 h-32 rounded-xl shadow-md flex items-center justify-center text-gray-600">
                        <!-- Contenu statistique ici -->
                        <p>Statistique 2</p>
                    </div>
                    <!-- Bloc 3 -->
                    <div class="bg-gray-200 h-32 rounded-xl shadow-md flex items-center justify-center text-gray-600">
                        <!-- Contenu statistique ici -->
                        <p>Statistique 3</p>
                    </div>
                    <!-- Bloc 4 -->
                    <div class="bg-gray-200 h-32 rounded-xl shadow-md flex items-center justify-center text-gray-600">
                        <!-- Contenu statistique ici -->
                        <p>Statistique 4</p>
                    </div>
                </div>
            </section>

            <!-- Section Calendrier (Placeholder visuel) -->
            <section class="mb-8">
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
                        <!-- Jours du mois (placeholders) -->
                        @php
                            $days = [
                                ['day' => '21', 'color' => 'bg-gray-200'],
                                ['day' => '22', 'color' => 'bg-gray-200'],
                                ['day' => '23', 'color' => 'bg-gray-200'],
                                ['day' => '24', 'color' => 'bg-pink-300'], // Exemple de couleur
                                ['day' => '25', 'color' => 'bg-orange-400'], // Exemple de couleur
                                ['day' => '26', 'color' => 'bg-purple-600'], // Exemple de couleur
                                ['day' => '27', 'color' => 'bg-gray-200'],
                                ['day' => '28', 'color' => 'bg-gray-200'],
                                // ... ajoutez plus de jours si nécessaire
                            ];
                        @endphp
                        @foreach($days as $day)
                            <div class="h-16 rounded-lg flex items-center justify-center text-gray-800 font-bold {{ $day['color'] }}">
                                {{ $day['day'] }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Vos boutons d'accès rapide originaux (déplacés ici ou intégrés à la sidebar) -->
            <!-- Si vous voulez les garder comme des cartes distinctes, vous pouvez les mettre ici -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Accès Rapide</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                    <a href="{{ route('coordinateur.creer_cours') }}" class="bg-yellow-100 hover:bg-yellow-200 rounded-xl p-6 flex flex-col items-center shadow transition">
                        <i class="fas fa-plus-circle text-3xl text-yellow-600 mb-2"></i>
                        <span class="font-semibold text-lg">Créer un cours</span>
                    </a>
                    <a href="{{ route('coordinateur.justifications') }}" class="bg-purple-100 hover:bg-purple-200 rounded-xl p-6 flex flex-col items-center shadow transition">
                        <i class="fas fa-file-alt text-3xl text-purple-600 mb-2"></i>
                        <span class="font-semibold text-lg">Justifications</span>
                    </a>
                </div>
            </section>

        </main>
    </div>
</body>
</html>
