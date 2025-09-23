<nav class="bg-white h-screen w-64 shadow-lg fixed top-0 left-0 z-30 flex flex-col justify-between">
    <div>
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 bg-blue-600">
            <span class="text-white text-xl font-semibold">IFRAN TRACK</span>
        </div>

        <!-- Navigation basée sur le rôle -->
        @if(auth()->check())
            @if(auth()->user()->role_id === 1) {{-- Admin --}}
                <!-- Menu Administrateur -->
                <div class="flex-1 px-4 py-6 space-y-1">
                    <div class="mb-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Principal</p>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-home mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>

                    <div class="mb-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Gestion des Utilisateurs</p>
                        <a href="{{ route('dashboard.utilisateur.liste') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-users mr-3"></i>
                            <span>Tous les Utilisateurs</span>
                        </a>
                        <a href="{{ route('parents.assign.form') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-users mr-3"></i>
                            <span>Assignations</span>
                        </a>

                        <a href="{{ route('etudiants.assign.form') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-user-graduate w-6"></i>
                            <span>Assigner Étudiant à Classe/Niveau</span>
                        </a>

                        <a href="{{ route('dashboard.utilisateur.create') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-user-plus mr-3"></i>
                            <span>Ajouter Utilisateur</span>
                        </a>
                    </div>

                    <div class="mb-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Configuration Académique</p>
                        <a href="{{ route('annees_academiques.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-calendar-alt mr-3"></i>
                            <span>Années Académiques</span>
                        </a>
                        <a href="{{ route('filieres.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-sitemap mr-3"></i>
                            <span>Filières</span>
                        </a>
                        <a href="{{ route('niveaux_etude.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-layer-group mr-3"></i>
                            <span>Niveaux d'Étude</span>
                        </a>
                        <a href="{{ route('matieres.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-book mr-3"></i>
                            <span>Matières</span>
                        </a>
                    </div>

            @elseif(auth()->user()->role_id === 2) {{-- Coordinateur --}}
                <!-- Menu Coordinateur -->
                <div class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('coordinateur.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-home mr-3"></i>
                        <span>Tableau de bord</span>
                    </a>
                    <a href="{{ route('coordinateur.cours.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-book mr-3"></i>
                        <span>Gestion des cours</span>
                    </a>
                    <a href="{{ route('coordinateur.emploi_temps.agenda') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        <span>Emploi du temps</span>
                    </a>

                    <!-- Section Absences -->
                    <div class="mb-2 mt-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Gestion des Absences</p>
                    </div>
                    <a href="{{ route('coordinateur.absences.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        <span>Tableau de bord absences</span>
                    </a>
                    <a href="{{ route('coordinateur.absences.statistiques') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-chart-bar mr-3"></i>
                        <span>Statistiques d'absences</span>
                    </a>
                    <a href="{{ route('coordinateur.justifications') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-file-alt mr-3"></i>
                        <span>Justifications</span>
                    </a>
                    <a href="{{ route('coordinateur.creer_cours') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-plus mr-3"></i>
                        <span>Créer un cours</span>
                    </a>
                </div>

            @elseif(auth()->user()->role_id === 3) {{-- Enseignant --}}
                <!-- Menu Enseignant -->
                <div class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('enseignant.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-home mr-3"></i>
                        <span>Tableau de bord</span>
                    </a>
                    <a href="{{ route('enseignant.emploi_temps') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        <span>Mon Emploi du Temps</span>
                    </a>
                    <a href="{{ route('enseignant.cours') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-book mr-3"></i>
                        <span>Mes Cours</span>
                    </a>
                    <a href="{{ route('enseignant.presences') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        <span>Feuille de présence</span>
                    </a>
                </div>

            @elseif(auth()->user()->role_id === 5) {{-- Parent --}}
                <!-- Menu Parent -->
                <div class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('parent.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-home mr-3"></i>
                        <span>Tableau de bord</span>
                    </a>
                    <a href="{{ route('parent.enfants') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-child mr-3"></i>
                        <span>Mes Enfants</span>
                    </a>
                    <a href="{{ route('parent.absences') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-calendar-times mr-3"></i>
                        <span>Absences</span>
                    </a>
                    <a href="{{ route('parent.emploi_temps') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-calendar mr-3"></i>
                        <span>Emploi du temps</span>
                    </a>
                </div>

            @elseif(auth()->user()->role_id === 4) {{-- Étudiant --}}
                <!-- Menu Étudiant -->
                <div class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('etudiant.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-home mr-3"></i>
                        <span>Tableau de bord</span>
                    </a>
                    <a href="{{ route('etudiant.absences') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-calendar-times mr-3"></i>
                        <span>Mes Absences</span>
                    </a>
                    <a href="{{ route('etudiant.emploi_temps') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        <span>Emploi du temps</span>
                    </a>
                </div>
            @endif
        @endif

    <!-- Profil et Déconnexion -->
    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0">
                <i class="fas fa-user-circle text-2xl text-gray-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-700">{{ Auth::user()->nom ?? 'Utilisateur' }}</p>
                <p class="text-xs text-gray-500">
                    @if(auth()->user()->role_id === 1)
                        Administrateur
                    @elseif(auth()->user()->role_id === 2)
                        Coordinateur
                    @elseif(auth()->user()->role_id === 3)
                        Enseignant
                    @elseif(auth()->user()->role_id === 4)
                        Étudiant
                    @elseif(auth()->user()->role_id === 5)
                        Parent
                    @endif
                </p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg">
                <i class="fas fa-sign-out-alt mr-3"></i>
                <span>Se déconnecter</span>
            </button>
        </form>
    </div>
</div>
</nav>
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Semestres
                </a>
            </li>
        </ul>
    </div>
</nav>
