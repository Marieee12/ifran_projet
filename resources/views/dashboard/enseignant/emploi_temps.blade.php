@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-gray-800">Mon Emploi du Temps</h1>
        <a href="{{ route('enseignant.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour au tableau de bord
        </a>
    </div>

    @if(count($planning) > 0)
        <!-- Emploi du temps par jour -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $jour)
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg">
                        <h3 class="text-lg font-semibold">{{ $jour }}</h3>
                    </div>
                    <div class="p-4">
                        @if(isset($planning[$jour]) && count($planning[$jour]) > 0)
                            <div class="space-y-3">
                                @foreach($planning[$jour] as $seance)
                                    <div class="border-l-4 border-blue-500 pl-4 py-2 bg-gray-50 rounded-r">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-800">{{ $seance->matiere->nom_matiere }}</h4>
                                                <p class="text-sm text-gray-600">{{ $seance->classe->nom_classe }}</p>
                                                <p class="text-sm font-medium text-blue-600">
                                                    {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($seance->date_seance)->format('d/m/Y') }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                @if(\Carbon\Carbon::parse($seance->date_seance)->isToday())
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                        Aujourd'hui
                                                    </span>
                                                @elseif(\Carbon\Carbon::parse($seance->date_seance)->isFuture())
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                                        À venir
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="mt-2 flex space-x-2">
                                            @if(\Carbon\Carbon::parse($seance->date_seance)->isToday() || \Carbon\Carbon::parse($seance->date_seance)->isPast())
                                                @php
                                                    $deuxSemainesApres = \Carbon\Carbon::parse($seance->date_seance . ' ' . $seance->heure_debut)->addWeeks(2);
                                                    $peutModifier = now()->lte($deuxSemainesApres);
                                                @endphp

                                                @if($peutModifier)
                                                    <a href="{{ route('enseignant.seance.presences', $seance->id) }}"
                                                       class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md transition-colors duration-200">
                                                        <i class="fas fa-clipboard-check mr-1"></i>
                                                        Marquer présences
                                                    </a>
                                                @else
                                                    <span class="text-xs bg-gray-300 text-gray-600 px-3 py-1 rounded-md cursor-not-allowed">
                                                        <i class="fas fa-lock mr-1"></i>
                                                        Délai dépassé
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-times text-3xl mb-2"></i>
                                <p>Aucun cours programmé</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Liste chronologique des prochains cours -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Prochains cours (vue chronologique)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matière</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($seances->take(10) as $seance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($seance->date_seance)->format('d/m/Y') }}
                                    <br>
                                    <span class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($seance->date_seance)->locale('fr')->dayName }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $seance->matiere->nom_matiere }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $seance->classe->nom_classe }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(\Carbon\Carbon::parse($seance->date_seance)->isToday())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Aujourd'hui
                                        </span>
                                    @elseif(\Carbon\Carbon::parse($seance->date_seance)->isFuture())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            À venir
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Passé
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if(\Carbon\Carbon::parse($seance->date_seance)->isToday() || \Carbon\Carbon::parse($seance->date_seance)->isPast())
                                        @php
                                            $deuxSemainesApres = \Carbon\Carbon::parse($seance->date_seance . ' ' . $seance->heure_debut)->addWeeks(2);
                                            $peutModifier = now()->lte($deuxSemainesApres);
                                        @endphp

                                        @if($peutModifier)
                                            <a href="{{ route('enseignant.seance.presences', $seance->id) }}"
                                               class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-clipboard-list mr-1"></i>
                                                Présences
                                            </a>
                                        @else
                                            <span class="text-gray-400 cursor-not-allowed">
                                                <i class="fas fa-lock mr-1"></i>
                                                Délai dépassé
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-calendar-alt text-6xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Aucun cours programmé</h3>
            <p class="text-gray-600">Vous n'avez pas de cours assignés pour les prochaines semaines.</p>
        </div>
    @endif
</div>

<!-- Légende -->
<div class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg p-4 border">
    <h4 class="text-sm font-semibold text-gray-800 mb-2">Légende</h4>
    <div class="space-y-1">
        <div class="flex items-center text-xs">
            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full mr-2">●</span>
            Cours aujourd'hui
        </div>
        <div class="flex items-center text-xs">
            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full mr-2">●</span>
            Cours à venir
        </div>
        <div class="flex items-center text-xs text-gray-600">
            <i class="fas fa-info-circle mr-2"></i>
            Délai de saisie : 2 semaines
        </div>
    </div>
</div>
@endsection
