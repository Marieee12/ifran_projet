@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestion des Parents</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Nombre d'enfants</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parents as $parent)
                                <tr>
                                    <td>{{ $parent->id }}</td>
                                    <td>{{ $parent->user->nom }}</td>
                                    <td>{{ $parent->user->prenom }}</td>
                                    <td>{{ $parent->user->email }}</td>
                                    <td>{{ $parent->telephone ?? 'Non renseigné' }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $parent->etudiants()->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.parent.enfants', $parent) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-users"></i> Gérer enfants
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Aucun parent trouvé</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
