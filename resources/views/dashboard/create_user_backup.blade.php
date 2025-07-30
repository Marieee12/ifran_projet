@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-user-plus mr-3 text-indigo-600"></i>Créer un Utilisateur
                    </h1>
                    <p class="text-gray-600 mt-2">Ajoutez un nouvel utilisateur au système IFRAN TRACK</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Retour au Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-8">        <!-- Messages de notification -->
        @if (session('success'))
            <div class="notification-custom bg-green-50 text-green-800 border-green-400" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    <div>
                        <strong class="font-bold">Succès!</strong>
                        <span class="block sm:inline ml-2">{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="notification-custom bg-red-50 text-red-800 border-red-400" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    <div>
                        <strong class="font-bold">Erreur!</strong>
                        <span class="block sm:inline ml-2">{{ session('error') }}</span>
                    </div>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="notification-custom bg-red-50 text-red-800 border-red-400" role="alert">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle mr-3 text-red-500 mt-1"></i>
                    <div>
                        <strong class="font-bold">Erreur de validation!</strong>
                        <ul class="mt-3 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.utilisateur.store') }}" class="space-y-8">
            @csrf

            <!-- Section Rôle -->
            <div class="field-section">
                <div class="relative">
                    <div class="relative">
                        <select name="role_id" id="role_id" required class="form-select-custom pl-12 appearance-none">
                            <option value="">Sélectionner un rôle</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->nom_role }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section Informations Personnelles -->
            <div class="field-section">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <label for="nom" class="label-custom">
                            <i class="fas fa-user mr-2 text-indigo-500"></i>
                            Nom de famille
                        </label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                               class="form-input-custom" placeholder="Ex: Dupont" required>
                    </div>

                    <div class="relative">
                        <label for="prenom" class="label-custom">
                            <i class="fas fa-user mr-2 text-indigo-500"></i>
                            Prénom
                        </label>
                        <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}"
                               class="form-input-custom" placeholder="Ex: Jean" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="relative">
                        <label for="email" class="label-custom">
                            <i class="fas fa-envelope mr-2 text-indigo-500"></i>
                            Adresse email
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="form-input-custom" placeholder="Ex: jean.dupont@ifran.com" required>
                    </div>

                    <div class="relative">
                        <label for="password" class="label-custom">
                            <i class="fas fa-lock mr-2 text-indigo-500"></i>
                            Mot de passe
                        </label>
                        <input type="password" name="password" id="password"
                               class="form-input-custom" placeholder="Minimum 8 caractères" required>
                    </div>
                </div>
            </div>

            <!-- Section Étudiant -->
            <div id="etudiantFields" style="display: none;" class="field-section">
                <h3 class="field-section-title">
                    <i class="fas fa-graduation-cap mr-3 text-indigo-600"></i>
                    Informations Étudiant
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="numero_etudiant" class="label-custom">
                            <i class="fas fa-id-badge mr-2 text-indigo-500"></i>
                            Numéro Étudiant
                        </label>
                        <input type="text" name="numero_etudiant" id="numero_etudiant" class="form-input-custom" placeholder="Ex: ETU2025001">
                    </div>
                    <div>
                        <label for="classe_id" class="label-custom">
                            <i class="fas fa-users mr-2 text-indigo-500"></i>
                            Classe
                        </label>
                        <select name="classe_id" id="classe_id" class="form-select-custom">
                            <option value="">Sélectionner une classe</option>
                            @if(isset($classes))
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="date_naissance" class="label-custom">
                            <i class="fas fa-calendar mr-2 text-indigo-500"></i>
                            Date de Naissance
                        </label>
                        <input type="date" name="date_naissance" id="date_naissance" class="form-input-custom">
                    </div>
                    <div>
                        <label for="telephone" class="label-custom">
                            <i class="fas fa-phone mr-2 text-indigo-500"></i>
                            Téléphone
                        </label>
                        <input type="tel" name="telephone" id="telephone" class="form-input-custom" placeholder="Ex: +212 6 12 34 56 78">
                    </div>
                </div>
            </div>

            <!-- Section Enseignant -->
            <div id="enseignantFields" style="display: none;" class="field-section">
                <h3 class="field-section-title">
                    <i class="fas fa-chalkboard-teacher mr-3 text-indigo-600"></i>
                    Informations Enseignant
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="specialite" class="label-custom">
                            <i class="fas fa-star mr-2 text-indigo-500"></i>
                            Spécialité
                        </label>
                        <input type="text" name="specialite" id="specialite" class="form-input-custom" placeholder="Ex: Mathématiques">
                    </div>
                </div>
            </div>

            <!-- Section Parent -->
            <div id="parentFields" style="display: none;" class="field-section">
                <h3 class="field-section-title">
                    <i class="fas fa-heart mr-3 text-indigo-600"></i>
                    Informations Parent
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="lien_avec_etudiant" class="label-custom">
                            <i class="fas fa-link mr-2 text-indigo-500"></i>
                            Lien avec l'étudiant
                        </label>
                        <select name="lien_avec_etudiant" id="lien_avec_etudiant" class="form-select-custom">
                            <option value="">Sélectionner un lien</option>
                            <option value="Pere">Père</option>
                            <option value="Mere">Mère</option>
                            <option value="Tuteur">Tuteur</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section Coordinateur -->
            <div id="coordinateurFields" style="display: none;" class="field-section">
                <h3 class="field-section-title">
                    <i class="fas fa-users-cog mr-3 text-indigo-600"></i>
                    Informations Coordinateur
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="departement" class="label-custom">
                            <i class="fas fa-building mr-2 text-indigo-500"></i>
                            Département
                        </label>
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
            <div class="flex justify-center pt-8 border-t border-gray-200">
                <button type="submit" class="form-button-custom text-lg">
                    <i class="fas fa-user-plus mr-3"></i>
                    Créer l'utilisateur
                    <i class="fas fa-arrow-right ml-3"></i>
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

            // Fonction pour cacher tous les champs spécifiques
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

                // Afficher les bons champs selon le rôle avec animation
                if (selectedRole.toLowerCase().includes('etudiant')) {
                    etudiantFields.style.display = 'block';
                    etudiantFields.style.animation = 'fadeIn 0.5s ease-in-out';
                } else if (selectedRole.toLowerCase().includes('enseignant')) {
                    enseignantFields.style.display = 'block';
                    enseignantFields.style.animation = 'fadeIn 0.5s ease-in-out';
                } else if (selectedRole.toLowerCase().includes('parent')) {
                    parentFields.style.display = 'block';
                    parentFields.style.animation = 'fadeIn 0.5s ease-in-out';
                } else if (selectedRole.toLowerCase().includes('coordinateur')) {
                    coordinateurFields.style.display = 'block';
                    coordinateurFields.style.animation = 'fadeIn 0.5s ease-in-out';
                }
            });

            // Vérifier le rôle sélectionné au chargement de la page
            if (roleSelect.value) {
                roleSelect.dispatchEvent(new Event('change'));
            }
        });

        // Animation CSS pour l'apparition des sections
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
