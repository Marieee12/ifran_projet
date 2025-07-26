<nav class="bg-white h-screen w-64 shadow-lg fixed top-0 left-0 z-30 flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-center h-20 border-b">
            <span class="text-2xl font-bold text-blue-700">IFRAN TRACK</span>
        </div>
        <ul class="mt-8">
            <li class="mb-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center py-3 px-8 text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-colors">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Tableau de bord
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('dashboard.utilisateur.liste') }}" class="flex items-center py-3 px-8 text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-colors">
                    <i class="fas fa-users mr-3"></i>
                    Utilisateurs
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('etudiants.index') }}" class="flex items-center py-3 px-8 text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-colors">
                    <i class="fas fa-user-graduate mr-3"></i>
                    Étudiants
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('enseignants.index') }}" class="flex items-center py-3 px-8 text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-colors">
                    <i class="fas fa-chalkboard-teacher mr-3"></i>
                    Enseignants
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('parents.index') }}" class="flex items-center py-3 px-8 text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-colors">
                    <i class="fas fa-book mr-3"></i>
                    Parents
                </a>
            </li>
            <li class="mb-2">
                <a href="" class="flex items-center py-3 px-8 text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-colors">
                    <i class="fas fa-book mr-3"></i>
                    Coordinateurs
                </a>
            </li>
            <li class="mb-2">
                <a href="{{route('matieres.index')}}" class="flex items-center py-3 px-8 text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-colors">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Matières
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="flex items-center py-3 px-8 text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-colors">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Année Académique
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="flex items-center py-3 px-8 text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-colors">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Semestres
                </a>
            </li>
        </ul>
    </div>
    <div class="mb-8 px-8">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center py-3 px-4 text-gray-700 hover:bg-red-100 hover:text-red-600 transition-colors rounded">
                <i class="fas fa-sign-out-alt mr-3"></i>
                Déconnexion
            </button>
        </form>
    </div>
</nav>
