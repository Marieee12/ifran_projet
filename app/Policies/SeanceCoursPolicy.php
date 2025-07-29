<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SeanceCours;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeanceCoursPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SeanceCours $seanceCours): bool
    {
        $roleName = $user->role->nom_role;

        switch ($roleName) {
            case 'Administrateur':
                return true;

            case 'Coordinateur Pédagogique':
                return true;

            case 'Enseignant':
                // L'enseignant peut voir ses propres cours
                return $seanceCours->id_enseignant === $user->enseignant?->id;

            case 'Étudiant':
                // L'étudiant peut voir les cours de sa classe
                return $seanceCours->id_classe === $user->etudiant?->classe?->id;

            case 'Parent':
                // Le parent peut voir les cours des classes de ses enfants
                $parent = $user->parent;
                if (!$parent) return false;

                return $parent->etudiants()->whereHas('classe', function ($query) use ($seanceCours) {
                    $query->where('id', $seanceCours->id_classe);
                })->exists();

            default:
                return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $roleName = $user->role->nom_role;
        return in_array($roleName, ['Administrateur', 'Coordinateur Pédagogique']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SeanceCours $seanceCours): bool
    {
        $roleName = $user->role->nom_role;
        return in_array($roleName, ['Administrateur', 'Coordinateur Pédagogique']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SeanceCours $seanceCours): bool
    {
        $roleName = $user->role->nom_role;
        return in_array($roleName, ['Administrateur', 'Coordinateur Pédagogique']);
    }

    /**
     * Determine whether the user can manage presences for this seance.
     */
    public function managePresences(User $user, SeanceCours $seanceCours): bool
    {
        $roleName = $user->role->nom_role;

        switch ($roleName) {
            case 'Administrateur':
                return true;

            case 'Coordinateur Pédagogique':
                // Coordinateur peut gérer E-learning et Workshop
                return in_array($seanceCours->type_cours, ['E-learning', 'Workshop']);

            case 'Enseignant':
                // Enseignant peut gérer seulement ses cours en présentiel
                return $seanceCours->type_cours === 'Presentiel' &&
                       $seanceCours->id_enseignant === $user->enseignant?->id;

            default:
                return false;
        }
    }
}
