<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles supplémentaires -->
    <style>
        .sidebar {
            min-height: 100vh;
            background: #161853 0%;
        }
        .main-content {
            background-color: #f8fafc;
            min-height: 100vh;
        }
        .nav-link {
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 sidebar shadow-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center mr-3">
                        <span class="text-purple-600 font-bold">🎓</span>
                    </div>
                    <h2 class="text-white text-xl font-bold">Dashboard</h2>
                </div>
            </div>

            <nav class="mt-8">
                @if(Auth::user()->role->nom_role === 'Étudiant')
                    <!-- Navigation Étudiant -->
                    <a href="{{ route('etudiant.dashboard') }}" class="nav-link flex items-center px-6 py-3 text-white {{ request()->routeIs('etudiant.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('etudiant.absences') }}" class="nav-link flex items-center px-6 py-3 text-white {{ request()->routeIs('etudiant.absences') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        Mes Absences
                    </a>
                    <a href="{{ route('etudiant.emploi_temps') }}" class="nav-link flex items-center px-6 py-3 text-white {{ request()->routeIs('etudiant.emploi_temps') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        Emploi du Temps
                    </a>
                @endif

                @if(Auth::user()->role->nom_role === 'Coordinateur Pédagogique')
                    <!-- Navigation Coordinateur -->
                    <a href="{{ route('coordinateur.dashboard') }}" class="nav-link flex items-center px-6 py-3 text-white {{ request()->routeIs('coordinateur.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('coordinateur.gestion-absences') }}" class="nav-link flex items-center px-6 py-3 text-white {{ request()->routeIs('coordinateur.gestion-absences') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        Gestion Absences
                    </a>
                @endif
            </nav>

            <!-- User Info -->
            <div class="absolute bottom-0 w-64 p-6">
                <div class="flex items-center text-white">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center mr-3">
                        <span class="text-purple-600 text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs opacity-75">{{ Auth::user()->role->nom_role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-white bg-red-600 rounded hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                        </svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 main-content">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <h1 class="text-2xl font-semibold text-gray-900">
                            @yield('title', 'Dashboard')
                        </h1>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500">{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
