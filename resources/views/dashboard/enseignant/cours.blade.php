@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-gray-800">Mes Cours</h1>
        <a href="{{ route('enseignant.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour au tableau de bord
        </a>
    </div>

    @if($cours->count() > 0)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
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
                        @foreach($cours as $seance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($seance->date_seance)->format('d/m/Y') }}
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
                                    @if(\Carbon\Carbon::parse($seance->date_seance)->isFuture())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            À venir
                                        </span>
                                    @elseif(\Carbon\Carbon::parse($seance->date_seance)->isToday())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Aujourd'hui
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Terminé
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if(\Carbon\Carbon::parse($seance->date_seance)->isToday() || \Carbon\Carbon::parse($seance->date_seance)->isPast())
                                        <a href="{{ route('enseignant.seance.presences', $seance->id) }}"
                                           class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            <i class="fas fa-clipboard-list mr-1"></i>
                                            Présences
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $cours->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-book text-6xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Aucun cours trouvé</h3>
            <p class="text-gray-600">Vous n'avez pas encore de cours assignés pour cette période.</p>
        </div>
    @endif
</div>
@endsection
