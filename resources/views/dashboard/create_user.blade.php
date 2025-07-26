<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Utilisateur - IFRAN TRACK</title>
    <!-- Tailwind CSS CDN (pour le développement rapide) -->
    <!-- Pour la production, assurez-vous d'avoir Tailwind CSS compilé localement
         et remplacez cette ligne par :
         @vite(['resources/css/app.css', 'resources/js/app.js']) (si vous utilisez Vite)
         ou
         <link href="{{ asset('css/app.css') }}" rel="stylesheet"> (si vous utilisez Laravel Mix)
    -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #e2e8f0;
        }
        .form-input-custom {
            @apply mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm
                   focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-all duration-200 ease-in-out;
        }
        .form-select-custom {
            @apply mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 rounded-lg shadow-sm
                   focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-all duration-200 ease-in-out;
        }
        .form-checkbox-custom {
            @apply h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-all duration-200 ease-in-out;
        }
        .form-button-custom {
            @apply inline-flex items-center justify-center py-2.5 px-6 border border-transparent shadow-md text-base font-semibold
                   rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2
                   focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 ease-in-out;
        }
        .form-button-secondary {
            @apply inline-flex items-center justify-center py-2.5 px-6 border border-gray-300 shadow-sm text-base font-semibold
                   rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2
                   focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-100 antialiased flex flex-col items-center justify-center min-h-screen p-6">
    <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-xl w-full max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Créer un Utilisateur</h1>
            <a href="{{ route('admin.dashboard') }}" class="form-button-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Retour au Dashboard
            </a>
        <div>
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" required>
        </div>
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <strong class="font-bold">Erreur!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <strong class="font-bold">Erreur de validation!</strong>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.utilisateur.store') }}" class="space-y-6">
            @csrf

            <!-- Champ Rôle -->
            <div>
                <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                <select name="role_id" id="role_id" required class="form-select-custom">
                    <option value="">Sélectionner un rôle</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->nom_role }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Champ Nom -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-input-custom" placeholder="Ex: Jean Dupont">
                </div>
            </div>

            <!-- Champs Email-->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-input-custom" placeholder="Ex: jean.dupont@ifran.com">
                </div>
            </div>

            <!-- Champs Mot de passe -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input type="password" name="password" id="password" required class="form-input-custom" placeholder="Minimum 8 caractères">
                </div>
            </div>

            <!-- Champs spécifiques Étudiant -->
            <div id="etudiantFields" style="display: none;" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="numero_etudiant" class="block text-sm font-medium text-gray-700 mb-1">Numéro Étudiant</label>
                        <input type="text" name="numero_etudiant" id="numero_etudiant" class="form-input-custom" placeholder="Ex: ETU2025001">
                    </div>
                    <div>
                        <label for="classe_id" class="block text-sm font-medium text-gray-700 mb-1">Classe</label>
                        <select name="classe_id" id="classe_id" class="form-select-custom">
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes ?? [] as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-1">Date de Naissance</label>
                        <input type="date" name="date_naissance" id="date_naissance" class="form-input-custom">
                    </div>
                </div>
            </div>

            <!-- Champs spécifiques Enseignant -->
            <div id="enseignantFields" style="display: none;" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="specialite" class="block text-sm font-medium text-gray-700 mb-1">Spécialité</label>
                        <input type="text" name="specialite" id="specialite" class="form-input-custom" placeholder="Ex: Mathématiques">
                    </div>
                </div>
            </div>

            <!-- Champs spécifiques Parent -->
            <div id="parentFields" style="display: none;" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="lien_avec_etudiant" class="block text-sm font-medium text-gray-700 mb-1">Lien avec l'étudiant</label>
                        <select name="lien_avec_etudiant" id="lien_avec_etudiant" class="form-select-custom">
                            <option value="">Sélectionner un lien</option>
                            <option value="Pere">Père</option>
                            <option value="Mere">Mère</option>
                            <option value="Tuteur">Tuteur</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Champs spécifiques Coordinateur -->
            <div id="coordinateurFields" style="display: none;" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="departement" class="block text-sm font-medium text-gray-700 mb-1">Département</label>
                        <select name="departement" id="departement" class="form-select-custom">
                            <option value="">Sélectionner un département</option>
                            <option value="B1">B1</option>
                            <option value="B2">B2</option>
                            <option value="B3">B3</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Bouton de soumission -->
            <div class="flex justify-end pt-4">
                <button type="submit" class="form-button-custom">
                    <i class="fas fa-user-plus mr-2"></i> Créer l'utilisateur
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role_id');
            const etudiantFields = document.getElementById('etudiantFields');
            const enseignantFields = document.getElementById('enseignantFields');
            const parentFields = document.getElementById('parentFields');
            const coordinateurFields = document.getElementById('coordinateurFields');

            // Fonction pour masquer tous les champs spécifiques
            function hideAllFields() {
                etudiantFields.style.display = 'none';
                enseignantFields.style.display = 'none';
                parentFields.style.display = 'none';
                coordinateurFields.style.display = 'none';
            }

            roleSelect.addEventListener('change', function() {
                hideAllFields();

                // Récupérer le texte du rôle sélectionné
                const selectedRole = roleSelect.options[roleSelect.selectedIndex].text;

                // Afficher les champs appropriés selon le rôle
                if (selectedRole.toLowerCase().includes('etudiant')) {
                    etudiantFields.style.display = 'block';
                } else if (selectedRole.toLowerCase().includes('enseignant')) {
                    enseignantFields.style.display = 'block';
                } else if (selectedRole.toLowerCase().includes('parent')) {
                    parentFields.style.display = 'block';
                } else if (selectedRole.toLowerCase().includes('coordinateur')) {
                    coordinateurFields.style.display = 'block';
                }
            });

            // Vérifier le rôle sélectionné au chargement de la page
            if (roleSelect.value) {
                roleSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>
