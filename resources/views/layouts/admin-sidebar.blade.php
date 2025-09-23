<!-- Sidebar -->
<div class="w-64 bg-slate-800 shadow-md">
    <div class="flex flex-col h-full">
        <!-- Logo/Brand -->
        <div class="flex items-center justify-center h-16 bg-slate-900">
            <span class="text-white text-xl font-semibold">IFRAN TRACK</span>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6">
            <div class="space-y-1">
                <!-- Menu Principal -->
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                    Menu Principal
                </p>
                <a href="{{ route('admin.dashboard') }}"
                    class="group flex items-center px-3 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700 text-white' : '' }}">
                    <i class="fas fa-home w-6"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Gestion des Utilisateurs -->
            <div class="mt-8">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                    Gestion des Utilisateurs
                </p>
                <div class="space-y-1">
                    <a href="{{ route('dashboard.utilisateur.liste') }}"
                        class="group flex items-center px-3 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white {{ request()->routeIs('dashboard.utilisateur.*') ? 'bg-slate-700 text-white' : '' }}">
                        <i class="fas fa-users w-6"></i>
                        <span>Liste des Utilisateurs</span>
                    </a>
                    <a href="{{ route('dashboard.utilisateur.create') }}"
                        class="group flex items-center px-3 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white">
                        <i class="fas fa-user-plus w-6"></i>
                        <span>Ajouter un Utilisateur</span>
                    </a>
                </div>
            </div>

            <!-- Configuration Académique -->
            <div class="mt-8">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                    Configuration Académique
                </p>
                <div class="space-y-1">
                    <a href="{{ route('annees_academiques.index') }}"
                        class="group flex items-center px-3 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white {{ request()->routeIs('annees_academiques.*') ? 'bg-slate-700 text-white' : '' }}">
                        <i class="fas fa-calendar-alt w-6"></i>
                        <span>Années Académiques</span>
                    </a>
                    <a href="{{ route('filieres.index') }}"
                        class="group flex items-center px-3 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white {{ request()->routeIs('filieres.*') ? 'bg-slate-700 text-white' : '' }}">
                        <i class="fas fa-sitemap w-6"></i>
                        <span>Filières</span>
                    </a>
                    <a href="{{ route('matieres.index') }}"
                        class="group flex items-center px-3 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white {{ request()->routeIs('matieres.*') ? 'bg-slate-700 text-white' : '' }}">
                        <i class="fas fa-book w-6"></i>
                        <span>Matières</span>
                    </a>
                </div>
            </div>

        <!-- User Profile & Logout -->
        <div class="p-4 border-t border-slate-700">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-user-shield text-2xl text-slate-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ Auth::user()->nom ?? 'Administrateur' }}</p>
                    <p class="text-xs text-slate-400">Admin</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="w-full flex items-center px-3 py-2 text-slate-300 hover:bg-slate-700 hover:text-white rounded-md">
                    <i class="fas fa-sign-out-alt w-6"></i>
                    <span>Se déconnecter</span>
                </button>
            </form>
        </div>
    </div>
</div>
