@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Gestion des enfants de {{ $parent->user->prenom }} {{ $parent->user->nom }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.parents') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <!-- Enfants actuels -->
                    <h4>Enfants associés</h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Classe</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parent->etudiants as $enfant)
                                <tr>
                                    <td>{{ $enfant->user->nom }}</td>
                                    <td>{{ $enfant->user->prenom }}</td>
                                    <td>{{ $enfant->classe->nom_classe_complet ?? 'Non assigné' }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.parent.remove-enfant', [$parent, $enfant]) }}"
                                              style="display:inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette association ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Retirer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun enfant associé</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Associer un nouvel enfant -->
                    <h4>Associer un nouvel enfant</h4>
                    <form method="POST" action="{{ route('admin.parent.associate', $parent) }}">
                        @csrf
                        <div class="form-group">
                            <label for="etudiant_id">Sélectionner un étudiant :</label>
                            <select name="etudiant_id" id="etudiant_id" class="form-control" required>
                                <option value="">-- Choisir un étudiant --</option>
                                @foreach($etudiants as $etudiant)
                                    @if(!$parent->etudiants->contains($etudiant->id))
                                        <option value="{{ $etudiant->id }}">
                                            {{ $etudiant->user->prenom }} {{ $etudiant->user->nom }}
                                            @if($etudiant->classe)
                                                ({{ $etudiant->classe->nom_classe_complet }})
                                            @endif
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus"></i> Associer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
